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
use App\Http\Requests\SupportTicketRequest;
use App\Models\SupportTicket;
use App\Models\Conversation;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $supportTicket = SupportTicket::where('user_id', auth()->id())->with(
                'user',
                function ($q) {
                    $q->select('id', 'first_name', 'last_name', 'avatar', 'email', 'phone', 'gender', 'country_id', 'state_id', 'city_id');
                }
            )->latest()->get();
            if ($supportTicket) {
                return $this->success($supportTicket);
            } else {
                return $this->success([]);
            }
        } catch (\Exception | \Error $e) {
            return $this->errorOk('Something went wrong!');
        }
    }

    /**
     * media
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
            'subject' => 'required|string',
            'message' => 'required|string',
            ]
        );
        try {
            $supportTicket = SupportTicket::create(
                [
                'user_id' => auth()->id(),
                'subject' => $request->get('subject'),
                'priority' => $request->get('priority'),
                'message' => $request->get('message'),
                'ticket_type_id'=>$request->get('ticket_type_id'),
                'status' => 0,
                ]
            );
            return $this->successMessage('Ticket Raised Successfully! Soon, one of our team members will contact you.');
        } catch (\Throwable $th) {
            return $this->errorOk('Something went wrong!'. $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function chat($id, SupportTicket $supportTicket)
    {
        $supportTicket = SupportTicket::where('id', $id);
        if ($supportTicket->exists()) {
            $supportTicket = $supportTicket->with('ticketConversations', 'user')->first();
            return $this->success($supportTicket);
        } else {
            return $this->errorOk('Support Ticket not found!');
        }
    }
    public function show(SupportTicket $supportTicket, $id)
    {
        try {
            $supportTicket = SupportTicket::where('id', $id)->first();
            if ($supportTicket) {
                return $this->success($supportTicket);
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
    public function attachment(SupportTicket $supportTicket, $id)
    {
        try {
            $supportTicket = SupportTicket::where('id', $id);
            if ($supportTicket->exists()) {
                $supportTicket = $supportTicket->with('medias')->first();
                return $this->success($supportTicket);
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
}
