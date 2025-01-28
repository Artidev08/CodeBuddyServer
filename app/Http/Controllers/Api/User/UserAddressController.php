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
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\UserAddress;
use Auth;

class UserAddressController extends Controller
{
    public function index(Request $request)
    {
        try {
            $userAddress = UserAddress::where('user_id', auth()->id())->latest()->with(
                ['user'=>function ($q) {
                    $q->with(
                        ['country'=>function ($q) {
                            $q->select('id', 'name');
                        },'state'=>function ($qa) {
                            $qa->select('id', 'name');
                        },'city'=>function ($qr) {
                            $qr->select('id', 'name');
                        }]
                    );
                }]
            )->get();
            foreach ($userAddress as $address) {
                $address['country'] = Country::find($address->details['country'])->name??"";
                $address['state'] = State::find($address->details['state'])->name??"";
                $address['city'] = City::find($address->details['city'])->name??"";
            }
            return $this->success($userAddress);
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate(
            [
            'address' => 'required',
            ]
        );
        try {
            $address = [
                'full_name' => $request->full_name,
                'number' => $request->number,
                'address' => $request->address,
                'address2' => $request->address2,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'type' => $request->type,
            ];
            if ($request->mark_as_default == 1) {
                $userAddress = UserAddress::where('user_id', auth()->id())->get();
                foreach ($userAddress as $useradd) {
                    $useradd->update(
                        [
                        'mark_as_default' => 0,
                        ]
                    );
                }
            }
            UserAddress::create(
                [
                'details' => $address,
                'user_id' => auth()->id(),
                'mark_as_default' => $request->mark_as_default,
                ]
            );
            return $this->successMessage('User Address Created Successfully!');
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $request->validate(
            [
            'address' => 'required',
            ]
        );
        try {
            $userAddress = UserAddress::where('id', $id)->first();
            $address = [
                'full_name' => $request->full_name,
                'number' => $request->number,
                'address' => $request->address,
                'address2' => $request->address2,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'type' => $request->type,
            ];
            if ($request->mark_as_default == 1) {
                $userAddress = UserAddress::where('user_id', auth()->id())->get();
                foreach ($userAddress as $useradd) {
                    $useradd->update(
                        [
                        'mark_as_default' => 0,
                        ]
                    );
                }
            }
            if ($userAddress) {
                $userAddress->update(
                    [
                    'details' => $address,
                    'user_id' => auth()->id(),
                    'mark_as_default' => $request->mark_as_default,
                    ]
                );
                return $this->successMessage('User Address Updated Successfully!');
            } else {
                return $this->errorOk('This User Address is not exist in our system!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
    public function destroy(Request $request, $id)
    {
        try {
            $userAddress = UserAddress::where('id', $id)->first();
            if ($userAddress) {
                $userAddress->delete();
                return $this->successMessage('User Address Deleted Successfully!');
            } else {
                return $this->errorOk('This User Address is not exist in our system!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }
}
