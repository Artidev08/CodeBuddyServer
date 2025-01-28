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
use App\Models\WebsiteEnquiry;
use Illuminate\Http\Request;

class UserEnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $userEnquiry = WebsiteEnquiry::get();
            if ($userEnquiry) {
                return $this->success($userEnquiry);
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
            'name' => 'required|string',
            'subject' => 'required|string',
            'description' => 'required|string',
            ]
        );
        try {
            $userEnquiry = WebsiteEnquiry::create(
                [
                'name' => $request->name,
                'subject' => $request->get('subject'),
                'description' => $request->get('description'),
                'status' => 0,
                ]
            );
            return $this->successMessage('Enquiry Created Successfully! Soon, one of our team members will contact you.');
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
    public function show($id)
    {
        try {
            $userEnquiry = WebsiteEnquiry::where('id', $id)->first();
            if ($userEnquiry) {
                return $this->success($userEnquiry);
            } else {
                return $this->errorOk('This User Enquiry is not exists!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
}
