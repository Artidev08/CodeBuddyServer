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
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\SettingRequest;
use App\Models\UserKyc;
use App\Models\Country;
use App\Models\User;
use App\Models\PayoutDetail;
use App\Models\MailSmsTemplate;
use App\Models\UserAddress;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return "s";
        $addresses = UserAddress::whereUserId(auth()->id())->get();
        $user = auth()->user();
        $user_kyc = UserKyc::whereUserId($user->id)->first();
        $payoutDetails = PayoutDetail::whereUserId(auth()->id())->get();
        $countries = Country::get();
        if ($user_kyc) {
            $ekyc = json_decode($user_kyc->details, true) ?? 0;
        } else {
            $ekyc = null;
        }
        return view('user.setting.index', compact('payoutDetails', 'addresses', 'user', 'user_kyc', 'ekyc', 'countries'));
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
    public function store(SettingRequest $request, $id)
    {
        $user = User::whereId($id)->first();
        $user->update($request->all());
        return back()->with('success', 'User Information Updated!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }
    public function ekycVerify(Request $request)
    {
        $request->validate(
            [
            'document_front_attachment' => 'required|image',
            'document_back_attachment' => 'required|image',
            ]
        );
        if ($request->hasFile("document_front_attachment")) {
            $document_front = $this->uploadFile($request->file("document_front_attachment"), "kyc")->getFilePath();
        } else {
            $document_front = null;
        }
        
        // return $request->all();
        if ($request->hasFile("document_back_attachment")) {
            $document_back = $this->uploadFile($request->file("document_back_attachment"), "kyc")->getFilePath();
        } else {
            $document_back = null;
        }

        $ekyc_info = [
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'document_front' => $document_front,
            'document_back' => $document_back,
            'admin_remark' => null,
        ];
                
        UserKyc::create(
            [
            'user_id' => auth()->id(),
            'details' => json_encode($ekyc_info),
            'status' => 3,
            ]
        );
        $admin_notification = [
            'title' => auth()->user()->full_name." has submitted a kyc.",
            'notification' => 'Updated At '.$request->created_at,
            'link' => route('admin.users.show', auth()->id()),
            'user_id' => 1,
        ];
        pushOnSiteNotification($admin_notification);

      
        // $user = auth()->user();
        // $mailcontent_data = MailSmsTemplate::where('code','=',"Submit-KYC")->first();
        // if($mailcontent_data){
        //     $arr=[
        //         '{id}'=> $user->id,
        //         '{name}'=>$user->full_name,
        //         ];
        //     $action_button = null;
        //     TemplateMail($user->name,$mailcontent_data,$user->email,$mailcontent_data->type, $arr, $mailcontent_data, $chk_data = null ,$mail_footer = null, $action_button);
        // }
        // $onsite_notification['user_id'] =  $user->id;
        // $onsite_notification['title'] = "Your eKYC has been submitted succesfully. Our team glad to see you in & we are contact you soon.";
        // $onsite_notification['link'] = route('customer.setting')."?active=account";
        // pushOnSiteNotification($onsite_notification);

        return redirect()->back()->with('success', 'Your eKYC verification request has been submitted successfully!');
    }

    public function updatePassword(Request $request)
    {
        if ($request->password !== $request->confirm_password) {
            return back()->with('error', 'Password and confirm password don\'t match !');
        }
        try {
            $user_password = User::where('id', auth()->id())->first()->password;
            $hashedPassword = $user_password;
            $plainPassword = $request->current_password; // Replace this with the user's entered password
            if (password_verify($plainPassword, $hashedPassword)) {
                User::find(auth()->user()->id)->update(
                    [
                    'password' => Hash::make($request->password),
                    ]
                );
                return back()->with('success', 'Password updated successfully !');
            }else{
                return back()->with('error', 'Current Password is Invalid');
            }

        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    public function updateProfileImg(ProfileRequest $request, $id)
    {
        $user = User::findOrFail($id);
        try {
            if ($request->hasFile('avatar')) {
                if ($user->avatar != null) {
                    unlinkFile(storage_path() . '/app/public/backend/users', $user->avatar);
                }
                $image = $request->file('avatar');
                $path = storage_path() . '/app/public/backend/users/';
                $imageName = 'profile_image_' . $user->id.rand(000, 999).'.' . $image->getClientOriginalExtension();
                $image->move($path, $imageName);
                $user->avatar=$imageName;
            } else {
                return back()->with('error', 'Please select an image to upload!');
            }
            $user->update(['avatar' => $imageName]);
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'data' => $id,
                        'message' => 'Success',
                        'title' => 'Profile image updated Updated Successfully!'
                        ]
                );
            }
            return back()->with('success', 'Profile image updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
