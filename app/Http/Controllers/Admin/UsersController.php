<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Session;

class UsersController extends Controller
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
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 15;

        if (!empty($keyword)) {
            $users = User::where([['username', 'LIKE', "%$keyword%"],['Status', '=', '1']])->orWhere('Firstname', 'LIKE', "%$keyword%")->orWhere('Lastname', 'LIKE', "%$keyword%")->orWhere('email', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $users = User::with('roles')->where('Status', '=', 1)->paginate($perPage);
        }
		

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $roles = Role::where('Status', '=', '1')->select('id', 'name', 'display_name')->get();
        $roles = $roles->pluck('name', 'id');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['Firstname' => 'required', 'Lastname' => 'required', 'username' => 'required', 'email' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
		$data['Email'] = $request->email;
        $user = User::create($data);

        foreach ($request->roles as $role) {
            $user->attachRole($role);
        }

        Session::flash('flash_message', 'User added!');

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $roles = Role::select('id', 'name', 'display_name')->get();
        $roles = $roles->pluck('display_name', 'id');

        $user = User::with('roles')->select('id', 'username', 'Title', 'Firstname', 'Lastname', 'email')->findOrFail($id);
        $user_roles = [];
        foreach ($user->roles as $role) {
            $user_roles[] = $role->name;
			$user_roles_id = $role->id;
        }
        return view('admin.users.edit', compact('user', 'roles', 'user_roles', 'user_roles_id', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['Title' => 'required', 'Firstname' => 'required', 'Lastname' => 'required', 'username' => 'required', 'email' => 'required', 'roles' => 'required']);
		
        $data = $request->except('password');
		$data['Email'] = $request->email;
		
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }
		
        $user = User::findOrFail($id);
        $user->update($data);

        $user->roles()->detach();
        foreach ($request->roles as $role) {
            $user->attachRole($role);
        }

        Session::flash('flash_message', 'User updated!');

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        User::destroy($id);

        Session::flash('flash_message', 'User deleted!');

        return redirect('admin/users');
    }
}
