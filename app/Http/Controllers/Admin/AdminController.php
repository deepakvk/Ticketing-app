<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Session;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Display given permissions to role.
     *
     * @return void
     */
    public function getGiveRolePermissions()
    {
        $roles = Role::select('id', 'name', 'display_name')->get();
        $permissions = Permission::select('id', 'name', 'display_name')->get();
		$relations = Role::with('permissions')->whereName('administrator')->first();
		$relation_permissions = $relations['relations']['permissions'];
		//echo "<pre>";
		//dd($relation_permissions);
		//dd($relations['relations']['permissions'][0]['original']['id']); echo "</pre>"; exit;
        return view('admin.permissions.role-give-permissions', compact('roles', 'permissions','relation_permissions'));
    }

    /**
     * Store given permissions to role.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function postGiveRolePermissions(Request $request)
    {
        $this->validate($request, ['role' => 'required', 'permissions' => 'required']);

        $role = Role::with('permissions')->whereName($request->role)->first();
        $role->permissions()->detach();

        foreach ($request->permissions as $permission_name) {
            $permission = Permission::whereName($permission_name)->first();
            $role->attachPermission($permission);
        }

        Session::flash('flash_message', 'Permission granted!');

        return redirect('admin/roles');
    }
	
	/**
	* Fetch Role permissions via ajax on change of roles
	**/
	public function getRolePermissionAjax(Request $request){
		$relations = Role::with('permissions')->whereName($request->role_name)->first();
		$relation_permissions = $relations['relations']['permissions'];
		return response()->json([
                'status'   => 'success',
                'messages' => $relation_permissions,
        ]);
	}
}
