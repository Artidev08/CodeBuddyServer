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
use App\Http\Requests\UserAddressRequest;
use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(UserAddressRequest $request)
    {
        try {
                $data = new UserAddress();
                $data->user_id = auth()->id();
                $data->is_primary = $request->is_primary ?? 0;
                $arr = [
                    'name'   => $request->name,
                    'address_1' => $request->address_1,
                    'address_2' => $request->address_2,
                    'phone' => $request->phone,
                    'type' => $request->type,
                    'pincode_id' => $request->pincode_id,
                    'country' => $request->country_id,
                    'state' => $request->state_id,
                    'city' => $request->city_id
                ];
                $data->details = $arr;
                $data->save();
                return redirect()->route('user.setting.index', ['active' => 'address_info'])->with('success', 'Address added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.setting.index', ['active' => 'address_info'])->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserAddress $userAddress
     * @return \Illuminate\Http\Response
     */
    public function show(UserAddress $userAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserAddress $userAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAddress $userAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\UserAddress  $userAddress
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAddress $userAddress)
    {
        $this->validate($request,[
            'user_id'     => 'required',
            'name'     => 'required',
            'phone'     => 'required|min:10|max:15',
            'address_1'     => 'required',
        ]);
        $address = UserAddress::whereId($request->id)->first();
        try {
            $arr = [
                'name'   => $request->name,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'country' => $request->country_id,
                'phone' => $request->phone,
                'type' => $request->type,
                'pincode_id' => $request->pincode_id,
                'state' => $request->state_id,
                'city' => $request->city_id
            ];
            $details = $arr;
            $address->update(
                [
                'details'=> $details
                ]
            );
            // return $request->all();
                return redirect()->route('user.setting.index', ['active' => 'address_info'])->with('success', 'updated added successfully!');
        } catch (\Exception $e) {
                return redirect()->route('user.setting.index', ['active' => 'address_info'])->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserAddress $userAddress
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAddress $userAddress)
    {
        try {
            if ($userAddress) {
                $userAddress->delete();
                return back()->with('success', 'User Address Deleted Successfully!');
            } else {
                return back()->with('error', 'User Address not found');
            }
        } catch (Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
