<?php

class LoginController extends BaseController {	

	public function login_page()
	{
		return View::make('login');
	}
	
	public function login_action()
	{
		$username = Input::get('username');
		$password = Input::get('password');
		
		if (Auth::attempt(['username' => $username, 'password' => $password]))
		{
			return Redirect::intended('admin/eieol_series');
		}
		
		return Redirect::back()
		->withInput()
		->withErrors('That username/password combo does not exist.');
	}
	
	public function logout()
	{
		Auth::logout();
		Session::flush();
	 	return Redirect::to('/');
	}

}