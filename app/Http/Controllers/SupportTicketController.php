<?php

namespace App\Http\Controllers;

use App\Mail\SupportMailManager;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Log;
use Mail;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_support_tickets'])->only('admin_index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::where(function ($query)  {
                $query->where('user_id', Auth::user()->id)
                    ->orWhereHas('ticketReplies', function ($q) {
                        $q->where('reply_to', Auth::user()->id);
                    });
                })->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.support_ticket.index', compact('tickets'));
    }

    public function admin_index(Request $request)
    {
        $sort_search = null;
        $search_status = $request->has('search_status') ? (
            str($request->search_status)->contains(',') === true ?
            str($request->search_status)->explode(',')
            : $request->search_status
        ) : null;
        $search_sub_order_status = null;

        $isSearchStatusQueryParamExists = $search_status !== null;
        $tickets = Ticket::orderBy('created_at', 'desc')
            ->orderByRaw("FIELD(status , 'pending', 'submitted', 'resolved', 'rejected') ASC");


        $tickets = Ticket::when(
            $isSearchStatusQueryParamExists,
            function ($query) use ($search_status) {
                if (is_string($search_status) === true) {
                    $query->where('status', $search_status);
                } else {
                    $query->whereIn('status', $search_status->toArray());
                }
            }, function ($query) {
                $query->where('status', 'pending');
            });

        if ($request->has('search_sub_order_status')) {
            $search_sub_order_status = str($request->search_sub_order_status)->contains(',') === true ?
            str($request->search_sub_order_status)->explode(',') : $request->search_sub_order_status;

            $tickets = $tickets->whereHas(
                'orderDetails', function ($query) use ($search_sub_order_status) {
                    if (is_string($search_sub_order_status) === true) {
                        $query->where('delivery_status', $search_sub_order_status);
                    } else {
                        $query->whereIn('delivery_status', $search_sub_order_status->toArray());
                    }
                },
            );
        }

        if ($request->has('search')) {
            $sort_search = $request->search;

            $tickets = $tickets->where(function ($query) use ($sort_search) {
                $query->where('code', 'like', "%$sort_search%")
                    ->orWhere('subject', 'like', "%$sort_search%")
                    ->orWhere('order_details_id', $sort_search)
                    ->orWhereHas('orderDetails', function ($q) use ($sort_search) {
                        $q->where('order_id', $sort_search)
                            ->orWhereHas('product', function ($q) use ($sort_search) {
                                $q->where('name', 'like', "%$sort_search%");
                            })->orWhereHas('order', function ($q) use ($sort_search) {
                                $q->whereHas('vendor', function ($q) use ($sort_search) {
                                    $q->where('name', 'like', "%$sort_search%")
                                        ->orWhereHas('shop', function ($q) use ($sort_search) {
                                            $q->where('name', 'like', "%$sort_search%");
                                        });
                                });
                            });
                    });
            });
        }

        $tickets = $tickets->orderBy('created_at', 'desc')
            ->paginate(15);

        return view(
            'backend.support.support_tickets.index',
            compact('tickets', 'search_status', 'search_sub_order_status')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ticket = new Ticket;
        $ticket->code = strtotime(date('Y-m-d H:i:s')).Auth::user()->id;
        $ticket->user_id = Auth::user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;

        if ($ticket->save()) {
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();

            return redirect()->route('support_ticket.index');
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function send_support_mail_to_admin($ticket)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is').':- '.$ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A ticket has been created. Please check the ticket.');
        $array['link'] = route('support_ticket.admin_show', encrypt($ticket->id));
        $array['sender'] = $ticket->user->name;
        $array['details'] = $ticket->details;

        try {
            Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new SupportMailManager($array));
        } catch (Exception $e) {
            Log::error("Error while sending support email to admin, with message: {$e->getMessage()}");
        }
    }

    public function send_support_reply_email_to_user($ticket, $tkt_reply)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is').':- '.$ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. You have a new response for this ticket. Please check the ticket.');
        $array['link'] = $ticket->user->user_type == 'seller' ? route('seller.support_ticket.show', encrypt($ticket->id)) : route('support_ticket.show', encrypt($ticket->id));
        $array['sender'] = $tkt_reply->user->name;
        $array['details'] = $tkt_reply->reply;

        try {
            Mail::to($ticket->user->email)->queue(new SupportMailManager($array));
        } catch (Exception $e) {
            Log::error("Error while sending support reply email to user, with message: {$e->getMessage()}");
        }
    }

    public function admin_store(Request $request)
    {
        $ticket = Ticket::findOrFail($request->ticket_id);
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $request->ticket_id;
        $ticket_reply->user_id = Auth::user()->id;
        $ticket_reply->reply = $request->reply;
        $ticket_reply->files = $request->attachments;
        $ticket_reply->ticket->client_viewed = 0;
        $ticket_reply->ticket->status = ($request->submit_as ? $request->submit_as :  ($ticket_reply->ticket->status == "pending" ? "Submitted" : $ticket_reply->ticket->status));
        $ticket_reply->reply_to = $request->submit_to == "vendor" ? $ticket->orderDetails->seller->id : $ticket->orderDetails->order->user->id ;
        $ticket_reply->ticket->save();

        if ($ticket_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();
            $this->send_support_reply_email_to_user($ticket_reply->ticket, $ticket_reply);

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function seller_store(Request $request)
    {
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $request->ticket_id;
        $ticket_reply->user_id = $request->user_id;
        $ticket_reply->reply = $request->reply;
        $ticket_reply->files = $request->attachments;
        $ticket_reply->ticket->viewed = 0;
        $ticket_reply->ticket->status = 'pending';
        $ticket_reply->ticket->save();

        if ($ticket_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();

            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function show($id)
    {
        try {
            $ticket = Ticket::findOrFail(decrypt($id));
            $ticket->client_viewed = 1;
            $ticket->save();
            $ticket_replies = $ticket->ticketReplies;

            return view('frontend.user.support_ticket.show', compact('ticket', 'ticket_replies'));
        } catch (Exception) {
            abort(500);
        }
    }

    public function admin_show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        if($ticket->is_locked == "no"){
            $ticket->is_locked = "yes";
            $ticket->locked_for =  Auth::user()->id;
        }
        $ticket->viewed = 1;
        $ticket->save();
        return view('backend.support.support_tickets.show', compact('ticket'));
    }

    public function adminClose(Ticket $ticket){
        $ticket->is_locked = "no";
        $ticket->locked_for =  null;
        $ticket->save();
        return redirect()->route('support_ticket.admin_index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function saveTicketRelatedToOrder(Request $request){
        $ticket = new Ticket;
        $ticket->code    = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->user_id = is_null(Auth::user()->owner_id) ? Auth::user()->id : Auth::user()->owner_id ;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files   = $request->attachments;
        $ticket->order_details_id = $request->order_details;
        if($ticket->save()){
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();
            return is_null(Auth::user()->owner_id)
             ? redirect()->route('support_ticket.index')
             : redirect()->route('seller.support_ticket.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
    }
}
