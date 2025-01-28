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

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationRequest;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        return response(
            [
            'conversations' => Conversation::get(),
            'success' => 1
            ]
        );
    }
    public function show(Conversation $conversation)
    {
        if ($conversation) {
            return response(['conversation' => $conversation,'success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }
    public function store(Request $request)
    {
        $conversation = Conversation::create(
            [
            'type_id' => $request->type_id,
            'type' => $request->type,
            'enquiry_id' => $request->enquiry_id,
            'user_id' => auth()->id(),
            'comment'=>$request->comment,
            ]
        );
        if ($conversation) {
            return response(
                [
                'message' => 'Ticket created successfully!',
                'conversation' => $conversation,
                'success' => 1
                ]
            );
        }
        return response(
            [
            'message' => 'Sorry! Failed to create permission!',
            'success' => 0
            ]
        );
    }

    public function destroy(Conversation $conversation)
    {
        if ($conversation) {
            $conversation->delete();
            return response(['message' => 'Ticket Conversation has been deleted','success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }
}
