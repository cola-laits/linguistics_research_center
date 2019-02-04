<?php

namespace App\Http\Controllers;

use App\EieolSeries;
use App\User;
use App\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

function getRoles() {
    $roles = array();
    $roles['ADMIN'] = 'Administrator';
    $serieses = EieolSeries::get()->sortBy('order');
    foreach ($serieses as $series) {
        $roles[$series->id] = $series->title;
    }
    return $roles;
}

class UserController extends Controller
{

    public function index() {
        $users = User::all();
        return view('user.user_index', ['users' => $users]);
    }

    public function create() {
        return view('user.user_form', ['action' => 'Create',
            'roles' => getRoles(),
            'selected_permissions' => null]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function store(Request $request) {
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:user',
            'email' => 'required|email|unique:user',
            'password' => 'required|min:8|confirmed',
            'permissions' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/user/create')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        DB::transaction(function () use ($request) {
            $user = new User;
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->created_by = Auth::user()->username;
            $user->updated_by = Auth::user()->username;
            $user->save();

            //add permissions
            foreach ($request->get('permissions') as $permission) {
                $permission_rec = new UserPermission;
                $permission_rec->permission = $permission;
                $permission_rec->created_by = Auth::user()->username;
                $permission_rec->updated_by = Auth::user()->username;
                $user->permissions()->save($permission_rec);
            }
        }); //end transaction

        $request->session()->flash('message', 'User has been created');
        return redirect('/admin2/user');

    }

    public function edit($id) {
        $user = User::find($id);
        return view('user.user_form', ['user' => $user,
            'action' => 'Edit',
            'roles' => getRoles(),
            'selected_permissions' => $user->permissions->pluck('permission')]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function update(Request $request, $id) {
        $rules = array(
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:user,username,' . $id,
            'email' => 'required|email|unique:user,email,' . $id,
            'permissions' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/user/' . $id . '/edit')
                ->withErrors($validator->messages())
                ->withInput($request->except('password'));
        }

        DB::transaction(function () use ($request, $id) {
            $user = User::find($id);
            $user->first_name = $request->get('first_name');
            $user->last_name = $request->get('last_name');
            $user->username = $request->get('username');
            $user->email = $request->get('email');
            $user->updated_by = Auth::user()->username;
            $user->save();

            //build list of all permissions sent in
            $input_permissions = array();
            foreach ($request->get('permissions') as $permission) {
                $input_permissions[] = $permission;
            }

            //build list of all permissions for user, if a permission is on file but not in input, delete it
            $table_permissions = array();
            foreach ($user->permissions as $permission) {
                if (!in_array($permission->permission, $input_permissions, false)) {
                    $permission->delete();
                } else {
                    $table_permissions[] = $permission->permission;
                }
            }

            //if a permission is in the input but not on file, add it
            foreach ($input_permissions as $permission) {
                if (!in_array($permission, $table_permissions, false)) {
                    $permission_rec = new UserPermission;
                    $permission_rec->permission = $permission;
                    $permission_rec->created_by = Auth::user()->username;
                    $permission_rec->updated_by = Auth::user()->username;
                    $user->permissions()->save($permission_rec);
                }
            }
        }); //end transaction

        $request->session()->flash('message', 'User has been updated');
        return redirect('/admin2/user');
    }

    public function destroy(Request $request, $id) {
        $user = User::find($id);
        $user->permissions()->delete();

        User::destroy($id);
        $request->session()->flash('message', 'User has been deleted');
        return redirect('/admin2/user');
    }

    public function password_form($id) {
        return view('user.password_form', ['id' => $id]);
    }

    public function change_password(Request $request, $id) {
        $rules = array(
            'password' => 'required|min:8|confirmed',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect('/admin2/user/password_form/' . $id)
                ->withErrors($validator->messages());
        }

        $user = User::find($id);

        $user->password = Hash::make($request->get('password'));
        $user->updated_by = Auth::user()->username;

        $user->save();

        $request->session()->flash('message', 'Password has been updated for ' . $user->getFullName());
        return redirect('/admin2/user');
    }
}
