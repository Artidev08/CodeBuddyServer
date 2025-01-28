<?php
/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202306-V1.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use App\Http\Requests\SupportTicketRequest;

class SupportTicketController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'SupportTickets';
    }
    public function index()
    {
        $supportTickets = SupportTicket::whereUserId(auth()->id())->latest()->simplePaginate(10);
        $label = $this->label;
        return view('user.support-ticket.index', compact('supportTickets', 'label'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $supportTicket = SupportTicket::create(
                [
                  'user_id' => auth()->id(),
                  'subject' => $request->get('subject'),
                  'message' => $request->get('message'),
                  'priority' => $request->get('priority'),
                  'status' => SupportTicket::STATUS_UNDER_WORKING,
                ]
            );
                $data['user_id'] =  1;
                $data['title'] = "User Raised a Ticket";
                $data['link'] = route('user.notification.index');
                $data['notification'] = auth()->user()->full_name." Raised a Ticket for".$request->get('subject');

                pushOnSiteNotification($data);
              return back()->with('success', "Ticket raised successfully!");
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            if ($ticket->getMedia('attachment')->count()) {
                $ticket->clearMediaCollection('attachment');
            }
            $ticket->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportTicket $order
     * @return \Illuminate\Http\Response
     */
    public function show(SupportTicket $supportTicket)
    {
        return view('user.support-ticket.show', compact('supportTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportTicket $order
     * @return \Illuminate\Http\Response
     */
    public function edit(SupportTicket $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportTicket  $order
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, SupportTicket $order)
    // {

    //     //
    // }
    public function update(Request $request, SupportTicket $supportTicket)
    {
        try {
            $supportTicket->user_id=$request->user_id;
            $supportTicket->subject=$request->subject;
            $supportTicket->message=$request->message;
            $supportTicket->support_ticket_type_id=$request->support_ticket_type_id;
            $supportTicket->priority=$request->priority;
            $supportTicket->save();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Support Ticket update successfully!'
                    ]
                );
            }
            return redirect(route('admin.support-tickets.index'))->with('success', 'Support Ticket update successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function addAttachment(SupportTicketRequest $request, SupportTicket $supportTicket)
    {
        if ($request->hasFile('file_name') && $request->file('file_name')->isValid()) {
            if ($supportTicket->getMedia('file_name')->count()) {
                $supportTicket->clearMediaCollection('file_name');
            }
            $supportTicket->addMediaFromRequest('file_name')->usingFileName(time().'.'.$request->file_name->extension())->toMediaCollection('file');
            return back()->with('success', 'Attachment Added successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupportTicket $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportTicket $order)
    {
        //
    }
}
