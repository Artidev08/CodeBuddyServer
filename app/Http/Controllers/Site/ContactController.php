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

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteEnquiry;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $metas = getSeoData('contact');
        $app_settings = getSetting(['app_core']);
        $contents = getParagraphContent(['contact_title','contact_description']);
        return view('site.contact.index', compact('metas', 'contents', 'app_settings'));
    }
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
            'name' => 'required',
            'type' => 'required',
            'subject' => 'required',
            'value_type' => 'required',
            'description' => 'required',
            'phone' => 'required|min:10|max:15', // Maximum 15 characters for the 'phone' field
            ]
        );
        // try {
            $data = new WebsiteEnquiry();
            $data->name=$request->name;
            $data->email=$request->value_type;
            $data->phone=$request->phone;
            $data->status=0;
            $data->subject=$request->subject;
            $data->description=$request->description;
            $data->save();

            $data['user_id'] =  auth()->id() ?? '0';
            $data['title'] = "Your Enquiry status updated";
            $data['link'] = route('user.notification.index');
            $data['notification'] = "Thank you for contacting us We look forward to hearing from you";
            pushOnSiteNotification($data);

            $title = 'Thank you for contacting';
            $sub_title = 'Our team of experts will get in touch with you shortly.';
            return redirect(route('page.custom')."?title=$title&sub_title=$sub_title");
                
        // } catch (\Exception $e) {
        //     return back()->with('error', 'Error: ' . $e->getMessage());
        // }
    }
}
