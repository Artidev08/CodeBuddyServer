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
use App\Models\Conversation;
use App\Models\SupportTicket;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Conversations';
    }
    public function index()
    {
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
        $this->validate(
            $request,
            [
            'comment'     => 'required',
            ]
        );

            $conversation  = new Conversation();
            $conversation->user_id = auth()->id();
            $conversation->type    = SupportTicket::class;
            $conversation->type_id = $request->type_id;
            $conversation->comment = $request->comment;
            $conversation->save();
             
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $conversation->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

            return back()->with('success', 'Message Sent Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conversation $order
     * @return \Illuminate\Http\Response
     */
    public function show(Conversation $supportTicket)
    {
        return view('user.support-ticket.show', compact('supportTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Conversation $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Conversation $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Conversation $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversation $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conversation $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversation $conversation)
    {
        try {
            $conversation->delete();
            return back()->with('success', 'Message deleted');
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
