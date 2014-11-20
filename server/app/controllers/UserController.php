<?php

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
		return View::make('user.user_form', ['action' => 'Create']);
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
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin/user/create')
			->withErrors($validator->messages())
			->withInput(Input::except('password'));
		} else {
		
			$user = new User;
			
			$user->first_name = Input::get('first_name');
			$user->last_name  = Input::get('last_name');
			$user->username   = Input::get('username');
			$user->email      = Input::get('email');
			$user->password   = Hash::make(Input::get('password'));
			$user->created_by = Auth::user()->username;
			$user->updated_by = Auth::user()->username;
			
			$user->save();
			Session::flash('message', $user->getFullName() . ' has been created');
			return Redirect::to('/admin/user');
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
		return View::make('user.user_form', [ 'user' => $user, 'action' => 'Edit' ]);
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
		);
		$validator = Validator::make(Input::all(), $rules);
		
		if ($validator->fails()) {
			return Redirect::to('/admin/user/' . $id . '/edit')
			->withErrors($validator->messages())
			->withInput(Input::except('password'));
		} else {
			$user = User::find($id);
			
			$user->first_name = Input::get('first_name');
			$user->last_name  = Input::get('last_name');
			$user->username   = Input::get('username');
			$user->email      = Input::get('email');
			$user->updated_by = Auth::user()->username;
			
			$user->save();
			
			Session::flash('message', $user->getFullName() . ' has been updated');
			return Redirect::to('/admin/user');
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
		User::destroy($id);
		Session::flash('message', 'User has been deleted');
		return Redirect::to('/admin/user');
	}


}
