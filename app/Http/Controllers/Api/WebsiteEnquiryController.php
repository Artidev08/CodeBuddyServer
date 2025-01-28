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

namespace App\Http\Controllers\Api;

use App\Models\WebsiteEnquiry;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteEnquiryRequest;
use Illuminate\Http\Request;

class WebsiteEnquiryController extends Controller
{
    public function store(Request $request)
    {
        $websiteEnquiry = WebsiteEnquiry::create(
            [
            'name' => $request->name,
            'status' => $request->status,
            'subject' => $request->subject,
            'type' => $request->type,
            'value_type' => $request->value_type,
            'description' => $request->description,
            ]
        );


        if ($websiteEnquiry) {
            return response(
                [
                'message' => 'Support Ticket created succesfully!',
                'websiteEnquiry'    => $websiteEnquiry,
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
}
