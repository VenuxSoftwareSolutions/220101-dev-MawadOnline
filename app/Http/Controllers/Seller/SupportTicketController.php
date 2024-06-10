<?php

namespace App\Http\Controllers\Seller;

use Auth;
use Mail;
use App\Models\Tour;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use App\Mail\SupportMailManager;
use Spatie\Permission\Models\Role;

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
        seller_lease_creation($user=Auth::user());

        $tour_steps=Tour::orderBy('step_number')->get();
        $tickets = Ticket::where('user_id', Auth::user()->owner_id)->orderBy('created_at', 'desc')->paginate(9);
        return view('seller.support_ticket.index', compact('tickets','tour_steps'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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

        if($ticket->save()){
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();
            return redirect()->route('seller.support_ticket.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
    }

    public function send_support_mail_to_admin($ticket){
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
        $currentUser = auth()->user();

        // Check if the ticket belongs to the current user
        if ($ticket->user_id !== $currentUser->id) {
            // Optionally, you can redirect to a 403 error page or a custom unauthorized page
            abort(403, 'Unauthorized action.');
        }
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketreplies;
        return view('seller.support_ticket.show', compact('ticket','ticket_replies'));
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
        if($ticket_reply->save()){

            flash(translate('Reply has been sent successfully'))->success();
            return back();
        }
        else{
            flash(translate('Something went wrong'))->error();
        }
    }

}
