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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\State;
use App\Models\WebsiteEnquiry;
use App\Models\City;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validData = $request->validate(
            [
            'identifier' => 'required',
            'password' => 'required'
            ]
        );
        if (str_contains($request->identifier, '@')) {
            $user = User::where('email', $request->identifier);
            if (!$user->first()) {
                return $this->errorOk('This Email is not exist in our system pls enter a valid email!');
            }

            if (!Auth::attempt(['email' =>$request->identifier,'password'=>$request->password])) {
                return response()->json(
                    [
                    'message' => 'Invalid credentials!',
                    'success' => 0
                    ],
                    200
                );
            }
        } elseif (is_numeric($request->identifier)) {
            $user = User::where('phone', $request->identifier);
            if (!$user->first()) {
                return $this->errorOk('This phone number is not exist in our system pls enter a valid phone number!');
            }
            if (!Auth::attempt(['phone' =>$request->identifier,'password'=>$request->password])) {
                return response()->json(
                    [
                    'message' => 'Invalid credentials!',
                    'success' => 0
                    ],
                    200
                );
            }
        } else {
            $user = User::where('username', $request->identifier);
            if (!$user->first()) {
                return $this->errorOk('This username is not exist in our system pls enter a valid username!');
            }
            if (!Auth::attempt(['username' =>$request->identifier,'password'=>$request->password])) {
                return response()->json(
                    [
                    'message' => 'Invalid credentials!',
                    'success' => 0
                    ],
                    200
                );
            }
        }

        auth()->loginUsingId($user->first()->id);
        
        $accessToken = Auth::user()->createToken('authToken')->plainTextToken;

        return $this->success(
            [
            'user' => $user->first(),
            'token' => $accessToken,
            ]
        );
    }

    public function loginWithPhone(Request $request)
    {
        $request->validate(
            [
            'phone' => 'required',
            ]
        );

        $otp = rand(1000, 9999);

        $user = User::where('phone', $request->get('phone'));

        if (!$user->exists()) {
            return $this->errorOk('This User is not exist in out system');
        }

        $user->update(
            [
            'temp_otp' => $otp,
            ]
        );
        
        return $this->success($otp);
    }
    public function helpdesk(Request $request)
    {
        $request->validate(
            [
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|min:10|max:15',
            'message' => 'required|string',
            ]
        );
        try {
            WebsiteEnquiry::create(
                [
                'name' => $request->full_name,
                'status' => 0,
                'description' => $request->message.' '.$request->email.' '.$request->phone,
                ]
            );
            return $this->successMessage('Enquiry Created Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong ".$th->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(
            [
            'phone' => 'required|min:10|max:15',
            'otp' => 'required',
            ]
        );

        $user = User::where('phone', $request->get('phone'))->where('temp_otp', $request->get('otp'));
        
        if (!$user->exists()) {
            return $this->errorOk('Invalid OTP.');
        }

        auth()->loginUsingId($user->first()->id);
        
        $accessToken = Auth::user()->createToken('authToken')->plainTextToken;
        $userData = User::whereId(auth()->id())->latest()->first();
        return $this->success(
            [
            'user' => $userData,
            'token' => $accessToken,
            ]
        );
    }

    public function getStates(Request $request)
    {
        $states =  State::where('country_id', 101)->get(['id','name']);
        if ($states->count() > 0) {
            return $this->success($states);
        } else {
            return $this->error('No States Found!');
        }
    }

    public function getCities(Request $request)
    {
        $request->validate(
            [
            'state_id' => 'required',
            ]
        );

        $cities =  City::where('state_id', $request->state_id)->get(['id','name']);
        if ($cities->count() > 0) {
            return $this->success($cities);
        } else {
            return $this->error('No City Found!');
        }
    }

    public function register(Request $request)
    {
        try {
            $validData = $request->validate(
                [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|string|unique:users,email',
                'password' => 'required',
                'phone' => 'required|numeric|min:10|max:15|unique:users',
                ]
            );
            $userName = User::where('username', $request->username)->exists();
            if ($userName) {
                return $this->errorOk('This username is already exist in our system, plz enter the different username !');
            }
            if (str_contains($request->username, '@')) {
                return $this->errorOk('You can\'t use @ in username!');
            }
            $user = User::create(
                [
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'gender' => $request->gender,
                'dob' => $request->dob,
                ]
            );
            // Student
            $user->syncRoles([3]);
             /* mail start  user*/
            $this->sendMailTo($user->email, 'registration-welcome-mail', ['{name}' => $user->full_name,'{email}' =>$user->email,'{username}' => $user->first_name.' '.$user->last_name]);
            
            return $this->success("User Registered Successfully!");
        } catch (\Exception $th) {
            return $this->error("Sorry! Failed to create user! ".$th->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        $validData = $request->validate(
            [
            'email' => 'required|email|string',
            ]
        );
        try {
            $user = User::where('email', $request->get('email'))->first();
            if (!$user) {
                return $this->error('User not found!', 200);
            }
            $otp = rand(1000, 9999);
            $user->temp_otp = $otp;
            $user->save();
            $body = "To reset your password, please use the following One Time Password (OTP):" . $otp . "<br> Thank you for using." . config('app.name');
            StaticMail($user->name, $user->email, "Reset Password in" . config('app.name'), $body, $mail_footer = null);
            return $this->successMessage("OTP Sent Successfully!");
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to reset password! ".$th->getMessage());
        }
    }

    public function changeResetPassword(Request $request)
    {
        $validData = $request->validate(
            [
            'email' => 'required|email|string',
            'password' => 'required|string',
            ]
        );
        try {
            $user = User::where('email', $request->get('email'))->first();
            if (!$user) {
                return $this->error('User not found!', 200);
            }
            $user->temp_otp = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->successMessage("Password updated successfully!");
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to reset password! ".$th->getMessage());
        }
    }
    
    public function changePassword(Request $request)
    {
        $request->validate(
            [
            'old_password' => 'required|string',
            'password' => 'required|string'
            ]
        );

           // match old password
        if (Hash::check($request->old_password, Auth::user()->password)) {
            User::find(auth()->user()->id)
                ->update(
                    [
                    'password' => Hash::make($request->password)
                    ]
                );
            return $this->successMessage("Password has been changed!");
        }
        return $this->error("Password not matched!");
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames();
        $permission = $user->getAllPermissions();
        return response(
            [
            'user' => $user,
            'success' => 1
            ]
        );
    }

    public function updateProfile(Request $request)
    {
        $request->validate(
            [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|min:10|max:15',
            ]
        );
        try {
            $user = User::where('id', auth()->id())->first();
            if ($user) {
                if ($request->hasFile('avatar')) {
                    if ($user->avatar != null) {
                        unlinkFile(storage_path() . '/app/public/backend/users', $user->avatar);
                    }
                    $image = $request->file('avatar');
                    $path = storage_path() . '/app/public/backend/users/';
                    $imageName = 'profile_image_' . $user->id . rand(000, 999) . '.' . $image->getClientOriginalExtension();
                    $image->move($path, $imageName);
                } else {
                    $imageName = collect(explode('/', $user->avatar))->last();
                }
                $user->update(
                    [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'dob' => $request->dob,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'city_id' => $request->city_id,
                    'bio' => $request->bio,
                    'avatar' => $imageName,
                    ]
                );
                return $this->successMessage('Profile Updated Succesfully!');
            } else {
                return $this->errorOk('This User does\'t exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }


    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();

        return response(
            [
            'message' => 'Logged out succesfully!',
            'status'  => 0
            ]
        );
    }
    public function updateDeviceToken(Request $request)
    {
        return $request->all();
        auth()->user()->update(
            [
            'fcm_token' => $request->get('fcm_token'),
            ]
        );
        return $this->successMessage('Updated');
    }
}
