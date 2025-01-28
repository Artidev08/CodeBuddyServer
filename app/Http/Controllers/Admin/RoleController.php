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
            return view('admin.roles.index', compact('permissions', 'roles', 'label'));
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
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function edit($id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
          $role = Role::find($id);
        if ($role) {
            $role_permission = $role->permissions()->pluck('id')->toArray();
            $permissions = DB::table('permissions')
                ->select('group', DB::raw('GROUP_CONCAT(id) as permission_ids'))
                ->groupBy('group')
                ->get()
                ->toArray();
            $label = $this->label;
            return view('admin.roles.edit', compact('role', 'role_permission', 'permissions', 'label'));
        } else {
            return redirect('404');
        }
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
        try {
            $role = Role::find($id);
            $role->update(
                [
                'name' => $request->role,
                'display_name' => $request->display_name,
                'description' => $request->description,
                ]
            );
            if ($request->has('permissions') && $request->has('permissions') != null) {
                $role->syncPermissions($request->permissions);
            }
            return redirect()->route('admin.roles.index')->with('success', 'Role info updated Successfully!');
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
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
