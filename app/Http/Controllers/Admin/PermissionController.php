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
use App\Http\Requests\PermissionRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        try {
            $length = 10;
            if (request()->get('length')) {
                $length = $request->get('length');
            }
            $roles = Role::get();
            $permissions = Permission::query();
            if ($request->get('search')) {
                $permissions->where('name', 'like', '%'.$request->search.'%');
            }
            $permissions= $permissions->paginate($length);
            if ($request->ajax()) {
                return view('admin.permissions.load', ['permissions' => $permissions,'roles' => $roles])->render();
            }
            return view('admin.permissions.index', compact('roles', 'permissions'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        try {
            $permission = Permission::create(
                [
                'name' => $request->permission,
                'group' => $request->group
                ]
            );
            $permission->roles()->sync($request->roles);
            // $permission->attachRoles([$request->roles]);
            if ($permission) {
                return redirect()->route('admin.permissions.index')->with('success', 'Permission created succesfully!');
            } else {
                return redirect()->route('admin.permissions.index')->with('error', 'Failed to create permission! Try again.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission   = Permission::find($id);
        if ($permission) {
            $delete = $permission->delete();
            $perm   = $permission->roles()->delete();

            return redirect(route('admin.permissions.index'))->with('success', 'Permission deleted!');
        } else {
            return redirect('404');
        }
    }
}
