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

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationRequest;
use App\Models\Conversation;
use App\Models\SupportTicket;
use App\Mail\DynamicMail;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(ConversationRequest $request)
    {
        try {
            if($request->comment == null && $request->file == null){
                return response()->json(
                    [
                        'status'=>'error',
                        'message' => 'Error',
                        'title' => 'Please Provide Comment Or File.'
                    ]
                );
            }
            $supportTicket = SupportTicket::find($request->type_id);
            if ($supportTicket && $request->type_id) {
                if ($supportTicket->user_id == auth()->id()) {
                    $supportTicket->status = SupportTicket::STATUS_UNDER_WORKING;
                } else {
                    $supportTicket->status = SupportTicket::STATUS_REPLY_RECEIVED;
                }
                $supportTicket->save();
            }
            $conversation = new Conversation();
            $conversation->user_id = auth()->id();
            $conversation->type = SupportTicket::class;
            $conversation->type_id  = $request->type_id;
            $conversation->comment = $request->comment;
            $conversation->save();
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $conversation->addMediaFromRequest('file')->toMediaCollection('file');
            }
            // Send Mail
            $email = auth()->user()->email;
            $fileHtml = false;
            if ($conversation->getFirstMediaUrl('file') != '') {
                if (str_contains($conversation->getFirstMedia('file')->getAttribute('mime_type'), 'image')) {
                    $fileHtml = '<img src="'.$conversation->getFirstMediaUrl('file').'" class="img-fluid" alt="" width="50%">';
                } else {
                    $fileHtml = $conversation->getFirstMedia('file')->getAttribute('file_name');
                }
            }
            // $this->sendMailTo($email,'registration-welcome-mail',['{name}' => auth()->user()->full_name,'{email}' =>auth()->user()->email,'{username}' => 'Username']);
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'conversation_id' => $conversation->id,
                        'fileHtml' => $fileHtml,
                        'title' => 'Comment added successfully.'
                    ]
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation $Conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Conversation $Conversation
     * @return \Illuminate\Http\Response
     */
    public function edit(Conversation $conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Conversation $Conversation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversation $conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation $Conversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $conversation = Conversation::find($request->id);
            if ($conversation) {
                $conversation->delete();
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'success',
                            'column'=>null,
                            'action'=>null,
                            'data' => null,
                            'message' => "Success",
                            'title' => "Ticket Conversation Deleted Successfully",
                            'html' => null,
                        ]
                    );
                }
                return back()->with('success', 'Conversation Deleted');
            } else {
                if (request()->ajax()) {
                    return response()->json(
                        [
                            'status'=>'error',
                            'message' => 'error',
                            'title' => 'Ticket Conversation not found'
                            ]
                    );
                }
                return back()->with('error', 'Conversation Not Found!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function delete(Conversation $conversation)
    {
        try {
            if ($conversation) {
                if ($conversation->getMedia('file')->count()) {
                    $conversation->clearMediaCollection('file');
                }
                $conversation->delete();
                return back()->with('success', 'Conversation Deleted');
            } else {
                return back()->with('error', 'Conversation Not Found!');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
