<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailComposer;
use App\Models\EmailLog;
use App\Models\RoundManagement;
use App\Models\Order;
use App\Models\MailSmsTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;

class EmailComposerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    try {
           $roles = Role::get();
           $users = User::where('email_verified_at', '!=', null)->get();
        return view('admin.email-composer.index', compact('roles', 'users'));
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}
public function mailer($type, $id)
{
    try {
         $templates = MailSmsTemplate::whereType(1)->get();
         $html = "<option> Select Emails</option>";
        if ($type == "RoundManagement") {
            $data = RoundManagement::find($id);
        }
        if ($type == "Order") {
            $data = Order::find($id);
        }
        $html .= '<option value="'.$data->candidate->email.'">'.$data->candidate->email." : ".$data->candidate->name.'(CA)</option>';
        $html .= '<option value="'.$data->agent->email.'">'.$data->agent->email." : ".$data->agent->name.'(A)</option>';
        $html .= '<option value="'.$data->vendor->email.'">'.$data->vendor->email." : ".$data->vendor->name.'(V)</option>';
        $html .= '<option value="'.$data->client->email.'">'.$data->client->email." : ".$data->client->name.'(CL)</option>';
            
        foreach ($data->candidate->contactEmails as $contact) {
            $html .= '<option value="'.$contact->email.'">'.$contact->email." : ".$contact->contact_person.'(CA)</option>';
        }
        foreach ($data->agent->contactEmails as $contact) {
            $html .= '<option value="'.$contact->email.'">'.$contact->email." : ".$contact->contact_person.'(A)</option>';
        }
        foreach ($data->vendor->contactEmails as $contact) {
            $html .= '<option value="'.$contact->email.'">'.$contact->email." : ".$contact->contact_person.'(V)</option>';
        }
        foreach ($data->client->contactEmails as $contact) {
            $html .= '<option value="'.$contact->email.'">'.$contact->email." : ".$contact->contact_person.'(CL)</option>';
        }
        return view('backend.admin.email-composer.mailer', compact('html', 'templates'));
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function MailerSend(Request $request)
{
    $request->validate(
        [
        'email' => 'required',
        'subject' => 'required',
        'message' => 'required',
            ]
    );
    try {
        foreach ($request->email as $email) {
            $mail_footer = null;
            manualEmail(null, $email, $request->get('subject'), null, $request->get('message'), $mail_footer, null, $request->get('cc'), $request->get('bcc'));
    
            // EmailLog Capture
            EmailLog::create(
                [
                'subject' => $request->get('subject'),
                'email' => $email,
                'user_id' => auth()->id(),
                'sent_by' => auth()->id(),
                'type' => 'manual',
                'datetime' => now(),
                    ]
            );
        }
        return back()->with('success', 'Mail sent successfully!');
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}
public function send(Request $request)
{
    $request->validate(
        [
        'email' => 'required',
        'subject' => 'required',
        'message' => 'required',
            ]
    );
    try {
        foreach (explode(',', $request->email) as $email) {
            $mail_footer = null;
            $body = $request->get('message')."<br>".$request->get('body');
                
            $name =  NameByEmail($email);
            $arr = [
                '{name}' => $name,
            ];
            $data['cc'] = $request->get('cc') ? explode(',', $request->get('cc')):[];
            $data['bcc'] = $request->get('bcc') ? explode(',', $request->get('bcc')):[];
            if ($request->hasFile('attachments')) {
                $data['attachments'] = $request->file('attachments');
            }
               
            manualEmail($email, $request->get('subject'), $body, $arr, $data);
    
            // EmailLog Capture
            EmailLog::create(
                [
                'subject' => $request->get('subject'),
                'email' => $email,
                'user_id' => auth()->id(),
                'sent_by' => auth()->id(),
                'type' => 'manual',
                'datetime' => now(),
                   ]
            );
        }
        return back()->with('success', 'Mail sent successfully!');
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }

}
    public function getEmails(Request $request)
    {
        if (AuthRole() == "Agent") {
            $data = User::role($request->role)->where('agent_id', auth()->id())->pluck('email')->toArray();
        } else {
            $data = User::role($request->role)->pluck('email')->toArray();
        }

        $emails = implode(',', $data);
        return response($emails, 200);
    }
    public function msgPrepare(Request $request)
    {
         $request->all();
         $user_emails = $request->user_emails;
        $html ='';
        foreach ($user_emails as $useremail) {
            $user = User::where('email', $useremail)->first();
            $html .='<p>Name: '.ucwords($user->name).', <br>  URL: '.route("guest.view", $user->unique_id).'</p>';
        }

        return response($html, 200);
    }
    public function contentPrepare(Request $request)
    {
         $request->all();
         $template_id = $request->template_id;
         $html ='';
         $rec = MailSmsTemplate::find($request->template_id);
        if ($rec) {
            $html = $rec->body;
        }

        return response($html, 200);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmailComposer $emailComposer
     * @return \Illuminate\Http\Response
     */
    public function show(EmailComposer $emailComposer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailComposer $emailComposer
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailComposer $emailComposer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailComposer $emailComposer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailComposer $emailComposer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailComposer $emailComposer
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailComposer $emailComposer)
    {
        //
    }
}
