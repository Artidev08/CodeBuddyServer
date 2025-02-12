<?php
/**
 *
 * @category Hq.ai
 *
 * @ref     Defenzelite Product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <Hq.ai: 202309-V1.2>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;

class ProfileController extends Controller
{

    /**start-hqai-m-index**/
    public function index()
    {
        $user = auth()->user();
        $countries = Country::all();
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $google2fa = app('pragmarx.google2fa');
        $auth["google2fa_secret"] = $google2fa->generateSecretKey();
        $QR_Image = $google2fa->getQRCodeInline(
            'Hq.ai',
            $user->email,
            $auth['google2fa_secret']
        );
        $secret = $auth["google2fa_secret"];
        return view('panel.admin.profile.index', compact('user', 'countries', 'timezones', 'QR_Image', 'secret'));
    }
    /**end-hqai-m-index**/
    

    /**start-hqai-m-update**/
    public function update(ProfileRequest $request, $id)
    {
        try {
            if ($request->email_verified_at) {
                $email_verified_at = now();
            } else {
                $email_verified_at = null;
            }
            $user = User::find($id)->update(
                [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'timezone' => $request->timezone,
                'phone' => $request->phone,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'country_id' => $request->country_id,
                'bio' => $request->bio,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'pincode' => $request->pincode,
                'address' => $request->address,
                ]
            );
            $user = User::whereId($id)->first();
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'data' => $id,
                        'message' => 'Success',
                        'title' => 'Profile Updated Successfully'
                    ]
                );
            }
            return redirect()->back()->with('success', 'Profile Updated Successfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
    /**end-hqai-m-update**/
     

    // public function updatePassword(ProfileRequest $request, $id)
    // {
    //     if ($request->password !== $request->confirm_password) {
    //         return back()->with('error', 'Password and confirm password don\'t match !');
    //     }

    //     // return Hash::make($request->password);
    //     try {
    //         $user_password = User::where('id', $id)->first()->password;
    //         $hashedPassword = $user_password;
    //         $plainPassword = $request->current_password;
    //         if (password_verify($plainPassword, $hashedPassword)) {
    //             User::find($id)->update(
    //                 [
    //                 'password' => Hash::make($request->password),
    //                 ]
    //             );
    //             if (request()->ajax()) {
    //                 return response()->json(
    //                     [
    //                         'status'=>'success',
    //                         'data' => $id,
    //                         'message' => 'Success',
    //                         'title' => 'Password Updated Successfully'
    //                         ]
    //                 );
    //             }
    //             return back()->with('success', 'Password updated successfully !');
    //         }else{
    //             return back()->with('error', 'Old Password is Invalid');
    //         }
            
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'There was an error: ' . $e->getMessage());
    //     }
    // }

    /**start-hqai-m-updatePassword**/
    public function updatePassword(ProfileRequest $request, $id)
    {
        if ($request->password !== $request->confirm_password) {
            return back()->with('error', 'Password and confirm password don\'t match !');
        }
        try {
            User::find($id)->update([
                'password' => Hash::make($request->password),
            ]);
            if (request()->ajax()) {
                return response()->json(
                    [
                        'status'=>'success',
                        'data' => $id,
                        'message' => 'Success',
                        'title' => 'Password Updated Successfully'
                        ]
                );
            }
            return back()->with('success', 'Password updated successfully !');
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
    /**end-hqai-m-updatePassword**/

    /**start-hqai-m-updateProfileImg**/
    public function updateProfileImg(ProfileRequest $request, $id)
    {
        // return $request->all();
        $user = User::findOrFail($id);
        try {
            if ($request->hasFile('avatar')) {
                if ($user->avatar != null) {
                    unlinkFile(storage_path().'/app/public/backend/users/', $user->avatar);
                }
        
                $image = $request->file('avatar');
                $extension = $image->getClientOriginalExtension();
                $imageName = 'profile_image_' . $user->id . '_' . uniqid() . '.' . $extension;
                $destinationPath = storage_path('app/public/backend/users/') . $imageName;
                $image->storeAs('public/backend/users', $imageName);

                // Resize the uploaded image using the trait method

                $resizedImagePath = $destinationPath; // Use the destination path as the resized image path
                $this->resizeImage($resizedImagePath, 300, 200);

                $user->avatar = $imageName;
                $user->save();
            } else {
                return back()->with('error', 'Please select an image to upload!');
            }
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
    /**end-hqai-m-updateProfileImg**/
}
