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
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Mail\TestMail;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Twilio\Rest\Client;

class MailController extends Controller
{
    
    public $label;

    function __construct()
    {
        $this->label = 'Mail/SMS Setting';
    }
    public function index()
    {
        try {
            $label = $this->label;
            return view('admin.mail-sms-configuration.index', compact('label'));
        } catch (Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function storeMail(Request $request)
    {
        // return $request->all();
        try {
            foreach ($request->all() as $key => $value) {
                if ($key != '_token' && $key != 'group_name') {
                    $setting = Setting::where('key', $key)->first();
                    if ($setting) {
                        $setting->value = $value;
                        $setting->save();
                    } else {
                        $data = new Setting();
                        $data->key = $key;
                        $data->value = $value;
                        $data->group = $request->get('group_name');
                        $data->save();
                    }
                }
            }
           
            $envData = [
                'MAIL_MAILER'=>getSetting('mail_mailer'),
                'MAIL_HOST'=>getSetting('mail_host'),
                'MAIL_PORT'=>getSetting('mail_port'),
                'MAIL_USERNAME'=>getSetting('mail_username'),
                'MAIL_PASSWORD'=>getSetting('mail_password'),
                'MAIL_ENCRYPTION'=>getSetting('mail_encryption'),
                'MAIL_FROM_ADDRESS'=>getSetting('mail_from_address'),
                'MAIL_FROM_NAME'=>getSetting('mail_from_name')
             ];

             $this->env_key_update($envData);

            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Information Updated'
                    ]
                );
            }
      
            return back()->with('success', 'Information Updated!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function storeSMS(Request $request)
    {
        try {
            $setting = Setting::where('key', '=', 'sms_endpoint')->first();
            if ($setting) {
                $setting->value = $request->sms_endpoint;
                $setting->group = $request->group_name;
                $setting->save();
            } else {
                $data = new setting();
                $data->key = 'sms_endpoint';
                $data->value = $request->sms_endpoint;
                $data->group = $request->group_name;
                $data->save();
            }
 
            if (request()->ajax()) {
                return response()->json(
                    [
                         'status'=>'success',
                         'message' => 'Success',
                         'title' => 'Information Updated'
                     ]
                );
            }
       
             return back()->with('success', 'Information Updated!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function storePushNotification(Request $request)
    {
        try {
            $setting = Setting::where('key', '=', 'fcm_api_token')->first();
            if ($setting) {
                $setting->value = $request->fcm_api_token;
                $setting->group = $request->group_name;
                $setting->save();
            } else {
                $data = new setting();
                $data->key = 'fcm_api_token';
                $data->value = $request->fcm_api_token;
                $data->group = $request->group_name;
                $data->save();
            }
 
            if (request()->ajax()) {
                return response()->json(
                    [
                         'status'=>'success',
                         'message' => 'Success',
                         'title' => 'Information Updated'
                     ]
                );
            }
       
             return back()->with('success', 'Information Updated!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function testSend(Request $request)
    {
        // return $request->all();
        if ($request->type == 'Mail') {
            try {
                    \Mail::to(request('email'))->send(new TestMail());
                if ($request->ajax()) {
                    return  response()->json(
                        [
                        'status'=>'success',
                        'message' => 'Success',
                        'title' => 'Test Mail sent successfully!'
                        ]
                    );
                }
                    return back()->with('success', 'Mail Sent!');
            } catch (Exception $e) {
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        }
        if ($request->type == 'Sms') {
            try {
                // manualSms($request->phone,"this is test message.");
                // $accountSid = getSetting('twilio_account_sid');
                // $authToken  = getSetting('twilio_auth_token');
                // $accountnumber  = getSetting('twilio_account_number');
                // $client = new Client($accountSid, $authToken);
                // $client->messages->create('+91'.$request->phone,
                //     array(
                //         'from' => $accountnumber,
                //         'body' => "this is test message."
                //     )
                // );
                if ($request->ajax()) {
                    return  response()->json(
                        [
                            'status'=>'success',
                            'message' => 'Success',
                            'title' => 'Test Sms sent successfully!'
                        ]
                    );
                }
                return back()->with('success', 'SMS Sent!');
            } catch (Exception $e) {
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        }
    }
    
    /**
     * Update the API key's for other methods.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function env_key_update($data)
    {
        $file = DotenvEditor::load();
        foreach ($data as $key => $item) {
            $file->setKey($key, $item);
        }
        $file->save();
    }
}
