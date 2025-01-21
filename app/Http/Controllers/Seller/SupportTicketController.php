<?php

namespace App\Http\Controllers\Seller;

use App\Mail\SupportMailManager;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\Tour;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Mail;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:seller_view_support_tickets'])->only('index');
        $this->middleware(['permission:seller_add_support_tickets'])->only('store');
        $this->middleware(['permission:seller_show_support_tickets'])->only('show');
        $this->middleware(['permission:seller_reply_support_tickets'])->only('ticket_reply_store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        seller_lease_creation($user = Auth::user());
        $ticket_status = request()->has('ticket_status') ? (
            str(request()->ticket_status)->contains(',') === true ?
            str(request()->ticket_status)->explode(',')
            : request()->ticket_status
        ) : null;
        $sub_order_status = request()->has('sub_order_status') ? (
            str(request()->sub_order_status)->contains(',') === true ?
            str(request()->sub_order_status)->explode(',')
            : request()->sub_order_status
        ) : null;

        $tickets = Ticket::where('user_id', Auth::user()->owner_id);

        $tour_steps = Tour::orderBy('step_number')->get();

        if ($ticket_status !== null) {
            $tickets->where(function ($query) use ($ticket_status) {
                if (is_string($ticket_status)) {
                    $query->where('status', $ticket_status);
                } else {
                    $query->whereIn('status', $ticket_status->toArray());
                }
            });
        }

        if ($sub_order_status !== null) {
            $tickets = $tickets->whereHas(
                'orderDetails', function ($query) use ($sub_order_status) {
                    if (is_string($sub_order_status) === true) {
                        $query->where('delivery_status', $sub_order_status);
                    } else {
                        $query->whereIn('delivery_status', $sub_order_status->toArray());
                    }
                },
            );
        }

        $tickets = $tickets->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('seller.support_ticket.index', compact(
            'tickets', 'tour_steps',
            'ticket_status', 'sub_order_status'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $ticket = new Ticket;
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->user_id = Auth::user()->owner_id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;

        if ($ticket->save()) {
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();

            return redirect()->route('seller.support_ticket.index');
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
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        // Get the current authenticated user
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketReplies;

        return view('seller.support_ticket.show', compact('ticket', 'ticket_replies'));
    }

    public function ticket_reply_store(Request $request)
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

    public function saveTicketRelatedToOrder(Request $request)
    {
        $ticket = new Ticket;
        $ticket->code = max(100000, (Ticket::latest()->first() != null ? Ticket::latest()->first()->code + 1 : 0)).date('s');
        $ticket->user_id = Auth::user()->owner_id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;
        $ticket->order_details_id = $request->order_details;
        dd($ticket);
        if ($ticket->save()) {
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();

            return redirect()->route('seller.support_ticket.index');
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }
}
