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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Auth;

class RolesController extends Controller
{
    public function list(Request $request)
    {
        return response(['roles' => Role::get(), 'success' => 1 ]);
    }


    public function store(Request $request)
    {
        $request->validate(
            [
            'name'     => 'required| unique:roles'
            ]
        );
        
        // store user information
        $role = Role::create(['guard_name' => 'web','name' => $request->name]);

        // assign permission to role
        if (isset($request->permissions)) {
            $role->syncPermissions($request->permissions);
        }

        if ($role) {
            return response(
                [
                'message' => 'Role created succesfully!',
                'role'    => $role,
                'success' => 1
                ]
            );
        }

        return response(
            [
                'message' => 'Sorry! Failed to create role!',
                'success' => 0
            ]
        );
    }

    
    public function show(Role $role, Request $request)
    {
        if ($role) {
            return response(['role' => $role,'success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }


    public function delete(Role $role, Request $request)
    {
        if ($role) {
            $role->delete();
            return response(['message' => 'Role has been deleted','success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }

    public function changePermissions(Role $role, Request $request)
    {
        $request->validate(
            [
            'permissions'     => 'required'
            ]
        );
        
        // update role permissions
        if ($role) {
            // assign permission to role
            $role->syncPermissions($request->permissions);
            return response(
                [
                'message' => 'Permission changed successfully!',
                'success' => 1
                ]
            );
        }

        return response(
            [
                'message' => 'Sorry! Role not found',
                'success' => 0
            ]
        );
    }
}
