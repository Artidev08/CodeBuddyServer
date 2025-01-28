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
use App\Models\Permission;
use Auth;

class PermissionController extends Controller
{
    public function list(Request $request)
    {
        return response(['permissions' => Permission::get(), 'success' => 1 ]);
    }


    public function store(Request $request)
    {
        $request->validate(
            [
            'name'     => 'required| unique:permissions'
            ]
        );
        
        // store user information
        $permission = Permission::create(['guard_name' => 'web','name' => $request->name]);


        if ($permission) {
            return response(
                [
                'message' => 'Permission created succesfully!',
                'permission'    => $permission,
                'success' => 1
                ]
            );
        }

        return response(
            [
                'message' => 'Sorry! Failed to create permission!',
                'success' => 0
            ]
        );
    }

    
    public function show(Permission $permission, Request $request)
    {
        if ($permission) {
            return response(['permission' => $permission,'success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }


    public function delete(Permission $permission, Request $request)
    {
        if ($permission) {
            $permission->delete();
            return response(['message' => 'Permission has been deleted','success' => 1]);
        } else {
            return response(['message' => 'Sorry! Not found!','success' => 0]);
        }
    }
}
