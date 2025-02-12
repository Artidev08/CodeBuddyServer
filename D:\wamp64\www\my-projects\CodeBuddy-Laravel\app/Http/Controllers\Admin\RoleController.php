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

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use DB;

class RoleController extends Controller
{
    public $label;

    function __construct()
    {
        $this->label = 'Roles';
    }

    /**start-hqai-m-index**/
    public function index()
    {
    
        try {
            $permissions = DB::table('permissions')
                ->select('group', DB::raw('GROUP_CONCAT(name) as permission_names'))
                ->groupBy('group')
            //    ->orderBy('permission_count', 'asc')
                ->get()
                ->toArray();
            $roles = Role::groupBy('name')->get();
            $label = $this->label;
            return view('panel.admin.roles.index', compact('permissions', 'roles', 'label'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
    /**end-hqai-m-index**/

    /**start-hqai-m-store**/
    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create(
                [
                'name' => $request->role,
                'display_name' => $request->display_name,
                'description' => $request->description,
                ]
            );
            if ($request->has('permissions') && $request->has('permissions') != null) {
                $role->syncPermissions($request->permissions);
            }

            if ($role) {
                return back()->with('success', 'Role created successfully!');
            } else {
                return back()->with('error', 'Failed to create role! Try again.');
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            $label = Str::singular($this->label);
            return redirect()->back()->with('error', $bug, compact('label'));
        }
    }
    /**end-hqai-m-store**/

  
    /**start-hqai-m-edit**/
    public function edit($id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
          $role = Role::find($id);
        if ($role) {
            $role_permission = $role->permissions()->pluck('id')->toArray();
            $permissions = DB::table('permissions')
                ->select('group', DB::raw('GROUP_CONCAT(id) as permission_ids'), DB::raw('GROUP_CONCAT(name) as permission_names'))
                ->groupBy('group')
                ->get()
                ->toArray();
            $label = $this->label;
            return view('panel.admin.roles.edit', compact('role', 'role_permission', 'permissions', 'label'));
        } else {
            return redirect('404');
        }
    }
    /**end-hqai-m-edit**/

    /**start-hqai-m-update**/
    public function update(RoleRequest $request, $id)
    {
        try {
            // return $request->permissions;
            $role = Role::find($id);
            $role->update(
                [
                    'name' => $request->role,
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                ]
            );

            if ($request->has('permissions') && is_array($request->permissions)) {
                // Assuming $request->permissions is an array of permission IDs
                $role->syncPermissions($request->permissions);
            } elseif ($request->permissions === null) {
                // If $request->permissions is null, detach all current permissions
                $role->detachPermissions($role->permissions->pluck('id')->toArray());
            }

            return redirect()->route('panel.admin.roles.index')->with('success', 'Role info updated Successfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }
    /**end-hqai-m-update**/

    
    public function destroy(Role $role)
    {
        if ($role) {
            $role->delete();
            $role->detachPermissions($role->permissions->pluck('name'));
            return back()->with('success', 'Role deleted!');
        } else {
            return redirect('404');
        }
    }
}
