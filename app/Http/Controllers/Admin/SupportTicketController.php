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

namespace App\Http\Controllers\Admin\Auth;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportTicketRequest;
use App\Models\SupportTicket;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class SupportTicketController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Support Tickets';
    }
    public function index(Request $request)
    {
        // return "s";
        $length = 10;
        if (request()->get('length')) {
            $length = $request->get('length');
        }
        $supportTickets = SupportTicket::query();

        if ($request->get('from') && $request->get('to')) {
            $supportTickets->whereBetween('created_at', [\Carbon\Carbon::parse($request->from)->format('Y-m-d').' 00:00:00',\Carbon\Carbon::parse($request->to)->format('Y-m-d')." 23:59:59"]);
        }
        if ($request->get('search')) {
            $supportTickets->where('subject', 'like', '%'.$request->get('search').'%')
                ->orWhereHas(
                    'user',
                    function ($q) use ($request) {
                        $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%'.trim($request->search).'%');
                    }
                );
        }
        if ($request->get('asc')) {
            $supportTickets->orderBy($request->get('asc'), 'asc');
        }
        if ($request->get('desc')) {
            $supportTickets->orderBy($request->get('desc'), 'desc');
        }
        if (request()->has('status') && request()->get('status') != null) {
            $supportTickets->where('status', request()->get('status'));
        }
        $supportTickets= $supportTickets->latest()->paginate($length);
        if ($request->ajax()) {
            return view('admin.support-tickets.load', ['supportTickets' => $supportTickets])->render();
        }
        $statuses = SupportTicket::STATUSES;
        $label = $this->label;
        return view('admin.support-tickets.index', compact('supportTickets', 'statuses', 'label'));
    }


    public function print(Request $request)
    {
        $supportTickets_arr = collect($request->records['data'])->pluck('id');
        $supportTickets = SupportTicket::whereIn('id', $supportTickets_arr)->get();
        return view('admin.support-tickets.print', ['supportTickets' => $supportTickets])->render();
    }



    /**
     * media
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = SupportTicket::STATUSES;
        $priorities = SupportTicket::PRIORITIES;
        $users = User::whereRoleIs('user')->get();
        $categories = getCategoriesByCode('SupportTicketCategories');
        $label = \Str::singular($this->label);
        return view('admin.support-tickets.create', compact('statuses', 'users', 'categories', 'priorities', 'label'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupportTicketRequest $request)
    {
        try {
            $supportTicket = SupportTicket::create(
                [
                'user_id' => $request->get('user_id'),
                'subject' => $request->get('subject'),
                'message' => $request->get('message'),
                'priority' => $request->get('priority'),
                'ticket_type_id'=>$request->get('ticket_type_id'),
                'status' => SupportTicket::STATUS_UNDER_WORKING,
                ]
            );
            
            // $filename = null;
            // if($request->has('attachment')){
            //     $img = $this->uploadFile($request->file("attachment"), "support-ticket")->getFilePath();
            //     $filename = $request->file('attachment')->getClientOriginalName();
            //     $extension = pathinfo($filename, PATHINFO_EXTENSION);
            // }
            $ticket = Conversation::create(
                [
                'type_id' => $supportTicket->id,
                'user_id' => $supportTicket->user_id,
                'type' => SupportTicket::class,
                'comment' => $supportTicket->message
                ]
            );
            
            if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
                if ($ticket->getMedia('attachment')->count()) {
                    $ticket->clearMediaCollection('attachment');
                }
                $ticket->addMediaFromRequest('attachment')->toMediaCollection('attachment');
            }
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Ticket raised successfully!'
                    ]
                );
            }
            return redirect(route('admin.support-tickets.index'))->with('success', "Ticket raised successfully!");
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $supportTicket = SupportTicket::whereId($id)->firstOrFail();

        $statuses = SupportTicket::STATUSES;
        $receiver = $supportTicket->user_id;
        $sender = auth()->id();
        return view('admin.support-tickets.show', compact('supportTicket', 'statuses', 'sender', 'receiver'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $supportTicket = SupportTicket::whereId($id)->firstOrFail();
        $statuses = SupportTicket::STATUSES;
        $priorities = SupportTicket::PRIORITIES;
        $users = User::whereRoleIs('user')->get();
        $categories = getCategoriesByCode('SupportTicketCategories');
        $label = Str::singular($this->label);
        return view('admin.support-tickets.edit', compact('supportTicket', 'statuses', 'users', 'categories', 'priorities', 'label'));
    }
    public function status(SupportTicket $supportTicket, $status)
    {
        try {
            if ($supportTicket) {
                $supportTicket->update(
                    [
                    'status' => $status
                    ]
                );
                $data['user_id'] =  $supportTicket->user_id;
                $data['title'] = "Your raise ticket is updated by admin";
                $data['link'] = route('user.notification.index');
                $data['notification'] = "Admin give you reply to ticket raised by you";
                pushOnSiteNotification($data);
                return back()->with('success', 'Status Updated Successfully!');
            }
            return back()->with('error', 'SupportTicket not found');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function bulkAction(SupportTicket $supportTicket, Request $request)
    {
        // return $request->all();
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json(
                    [
                    'status'=>'error',
                    ]
                );
                return back()->with('error', 'Hands Up!","Atleast one row should be selected');
            }
            switch ($request->action) {
                // Delete
                case ('delete'):
                    SupportTicket::whereIn('id', $request->ids)->delete();
                    $msg = 'Bulk delete!';
                    $title = "Deleted ".count($request->ids)." records successfully!";
                    break;
                $type = "error";
                $title = 'No action selected!';
            }
        
            if (request()->ajax()) {
                return response()->json(
                    [
                    'status'=>'success',
                    'column'=>$request->column,
                    'action'=>$request->action,
                    'data' => $request->ids,
                    'title' => $title,
                    ]
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SupportTicket $supportTicket)
    {
        try {
            $supportTicket->user_id=$request->user_id;
            $supportTicket->subject=$request->subject;
            $supportTicket->message=$request->message;
            $supportTicket->ticket_type_id=$request->ticket_type_id;
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
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function reply(SupportTicketRequest $request)
    {
        try {
            $supportTicket = SupportTicket::whereId($request->id)->first();
            if ($supportTicket) {
                $supportTicket->reply = $request->reply;
                $supportTicket->status = 1;
                $supportTicket->save();
                return redirect()->route('admin.support-tickets.index')->with(
                    'success',
                    'Replied
                Successfully!'
                );
            } else {
                return back()->with('error', 'SupportTicket not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function destroy(SupportTicket $supportTicket)
    {
        try {
            if ($supportTicket) {
                // Conversation Delete
                if ($supportTicket->conversations) {
                    foreach ($supportTicket->conversations as $key => $conversation) {
                        if ($conversation) {
                            $conversation->delete();
                        }
                    }
                }

                // Attachment Media Delete
                if ($supportTicket->getMedia('file')->count() > 0) {
                    $supportTicket->clearMediaCollection('file');
                }

                $supportTicket->delete();
                return back()->with('success', 'SupportTicket Deleted Successfully!');
            } else {
                return back()->with('error', 'SupportTicket not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
