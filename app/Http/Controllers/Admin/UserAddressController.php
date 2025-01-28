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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;

class UserAddressController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id'     => 'required',
            'name'     => 'required',
            'phone'     => 'required|min:10|max:15',
            'address_1'     => 'required',
        ]);
        try {
            $details = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'city' => $request->city_id,
                'pincode_id' => $request->pincode,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'type' => $request->type??0,
            ];
            $address = new UserAddress();
            $address->user_id = $request->user_id;
            $address->details = $details;
            $address->is_primary = 0;
            $address->save();
            if ($request->ajax()) {
                return response(['data'=>$address,200]);
            }
            return back()->with('success', 'User Address Created successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Somthing went wrong'.$th->getMessage());
        }
    }
    public function update(Request $request)
    {
        $this->validate($request,[
            'user_id'     => 'required',
            'name'     => 'required',
            'phone'     => 'required|min:10|max:15',
            'address_1'     => 'required',
        ]);
        try {
            $userAddress = UserAddress::find($request->id);
            $details = [
                'name' => $request->name,
                'phone' => $request->phone,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'city' => $request->city_id,
                'pincode_id' => $request->pincode,
                'state_id' => $request->state_id,
                'country_id' => $request->country_id,
                'type' => $request->type??0,
            ];
            $userAddress->user_id = $request->user_id;
            $userAddress->details = $details;
            $userAddress->is_primary = 0;
            $userAddress->save();
            return back()->with('success', 'User Address Created successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Somthing went wrong'.$th->getMessage());
        }
    }
  
    public function destroy(UserAddress $userAddress)
    {
        try {
            if ($userAddress) {
                $userAddress->delete();
                return back()->with('success', 'User Address deleted successfully');
            } else {
                return back()->with('error', 'User Address not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
