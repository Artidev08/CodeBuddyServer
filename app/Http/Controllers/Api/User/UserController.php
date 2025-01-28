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
use App\Models\WalletLog;
use App\Models\UserAddress;
use Auth;

class UserController extends Controller
{
    public function list(Request $request)
    {
        $user = User::get();
        return $this->success($user);
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                'first_name'     => ['required', 'string'],
                'last_name'     => 'string ',
                'email'    => 'required | email | unique:users',
                'password' => 'required | confirmed',
                'role'     => 'required'
            
                ]
            );
        
            // store user information
            $user = User::create(
                [
                'first_name'     => $request->first_name,
                'last_name'     => $request->last_name,
                'email'    => $request->email,
                'address' => $request->address,
                'password' => Hash::make($request->password)

                ]
            );

            // assign new role to the user
            $role = $user->attachRole($request->role);

            if ($user) {
                return response(
                    [
                    'message' => 'User created succesfully!',
                    'user'    => $user,
                    'success' => 1
                    ]
                );
            }

            return response(
                [
                'message' => 'Sorry! Failed to create user!',
                'success' => 0
                ]
            );
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! ".$e->getMessage());
        }
    }

    public function profile(User $user, Request $request)
    {
        $user = User::where('id', auth()->id())->with(
            ['country' => function ($q) {
                $q->select('id', 'name');
            },'state' => function ($qa) {
                $qa->select('id', 'name');
            },'city' => function ($qu) {
                $qu->select('id', 'name');
            }]
        )->first();
        return $this->success($user);
    }


    public function delete(User $user, Request $request)
    {
        if ($user) {
            $user->delete();
            return response(['message' => 'User has been deleted','success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }


    public function changeRole(User $user, Request $request)
    {
        $request->validate(
            [
            'roles'     => 'required'
            ]
        );
        
        // update user roles
        if ($user) {
            // assign role to user
            $user->syncRoles($request->roles);
            return response(
                [
                'message' => 'Roles changed successfully!',
                'success' => 1
                ]
            );
        }

        return response(
            [
                'message' => 'Sorry! User not found',
                'success' => 0
            ]
        );
    }
    public function userData(Request $request)
    {
        try {
            return $users = User::whereRoleIs('User')->get();
            return $this->success($users);
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
    public function walletBalanceIndex(Request $request)
    {
        try {
            $walletBalance = WalletLog::query();
            if (request()->has('type') && request()->get('type')) {
                $walletBalance->where('type', request()->get('type'));
            }
            if ($walletBalance->exists()) {
                $walletBalance = $walletBalance->where('user_id', auth()->id())->with(
                    'user',
                    function ($q) {
                        $q->select('id', 'first_name', 'last_name', 'email', 'phone', 'bio', 'gender');
                    }
                )->get();
                return $this->success($walletBalance);
            } else {
                return $this->errorOk('No wallet balance yet!');
            }
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to data! ".$th->getMessage());
        }
    }
}
