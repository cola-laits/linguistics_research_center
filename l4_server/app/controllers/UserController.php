<?php

function getRoles() {
	$roles = array();
	$roles['ADMIN'] = 'Administrator';
	$serieses = EieolSeries::get()->sortBy('order');
	foreach($serieses as $series) {
		$roles[$series->id] = $series->title;
	}
	return $roles;
}

class UserController extends BaseController {	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::all();
        return View::make('user.user_index', ['users' => $users]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('user.user_form', ['action' => 'Create', 
											 'roles' => getRoles(),
											 'selected_permissions' => null]);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$rules = array(
				'first_name' => 'required',
				'last_name'  => 'required',
				'username'   => 'required|unique:user',
				'email'      => 'required|email|unique:user',
				'password'   => 'required|min:8|confirmed',
				'permissions'  => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/user/create')
			->withErrors($validator->messages())
			->withInput(Input::except('password'));
		} else {		
			$returned_user = DB::transaction(function() {
				$user = new User;
				$user->first_name = Input::get('first_name');
				$user->last_name  = Input::get('last_name');
				$user->username   = Input::get('username');
				$user->email      = Input::get('email');
				$user->password   = Hash::make(Input::get('password'));
				$user->created_by = Auth::user()->username;
				$user->updated_by = Auth::user()->username;
				$user->save();
						
				//add permissions
				foreach (Input::get('permissions') as $permission) {
					$permission_rec = new UserPermission;
					$permission_rec->permission = $permission;
					$permission_rec->created_by = Auth::user()->username;
					$permission_rec->updated_by = Auth::user()->username;
					$user->permissions()->save($permission_rec);
				}
			
				return $user;
			}); //end transaction
							
			Session::flash('message', $returned_user->getFullName() . ' has been created');
			return Redirect::to('/admin2/user/' . $returned_user->id . '/edit');
			
		}

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);
		return View::make('user.user_form', [ 'user' => $user, 
											  'action' => 'Edit', 
											  'roles' => getRoles(),
											  'selected_permissions' => $user->permissions->lists('permission')]);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$rules = array(
				'first_name' => 'required',
				'last_name'  => 'required',
				'username'   => 'required|unique:user,username,' . $id,
				'email'      => 'required|email|unique:user,email,' .$id,
				'permissions'  => 'required',
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin2/user/' . $id . '/edit')
			->withErrors($validator->messages())
			->withInput(Input::except('password'));
		} else {
			$returned_user = DB::transaction(function($id) use ($id) {
				$user = User::find($id);
				$user->first_name = Input::get('first_name');
				$user->last_name  = Input::get('last_name');
				$user->username   = Input::get('username');
				$user->email      = Input::get('email');
				$user->updated_by = Auth::user()->username;				
				$user->save();
				
				//build list of all permissions sent in
				$input_permissions = array();
				foreach (Input::get('permissions') as $permission) {
					$input_permissions[] = $permission;
				}
				
				//build list of all permissions for user, if a permission is on file but not in input, delete it
				$table_permissions = array();
				foreach ($user->permissions as $permission) {
					if (!in_array($permission->permission, $input_permissions)) {
						$permission->delete();
					} else {
						$table_permissions[] = $permission->permission;
					}
				}
				
				//if a permission is in the input but not on file, add it
				foreach($input_permissions as $permission) {
					if (!in_array($permission, $table_permissions)) {
						$permission_rec = new UserPermission;
						$permission_rec->permission = $permission;
						$permission_rec->created_by = Auth::user()->username;
						$permission_rec->updated_by = Auth::user()->username;
						$user->permissions()->save($permission_rec);
					}
				}
				
				return $user;
			}); //end transaction
			
			Session::flash('message', $returned_user->getFullName() . ' has been updated');
			return Redirect::to('/admin2/user/' . $id . '/edit');
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);
		$user->permissions()->delete();
		
		User::destroy($id);
		Session::flash('message', 'User has been deleted');
		return Redirect::to('/admin2/user');
	}
	
	public function password_form($id)
	{
		return View::make('user.password_form', ['id' => $id]);
	}
	
	public function change_password($id)
	{
		$rules = array(
				'password'   => 'required|min:8|confirmed',
		);
		$validator = Validator::make(Input::all(), $rules);
	
		if ($validator->fails()) {
			return Redirect::to('/admin2/user/password_form/' . $id)
			->withErrors($validator->messages());
		} else {
			$user = User::find($id);
				
			$user->password   = Hash::make(Input::get('password'));
			$user->updated_by = Auth::user()->username;
				
			$user->save();
				
			Session::flash('message', 'Password has been updated for ' . $user->getFullName());
			return Redirect::to('/admin2/user/' . $id . '/edit');
		}
	}

}