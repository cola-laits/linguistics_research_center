<?php

namespace App\Http\Controllers;

class LoginController extends Controller {

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
			return Redirect::intended('admin');
		}
		
		return Redirect::back()
		->withInput()
		->withErrors('That username/password combo does not exist.');
	}
	
	public function logout()
	{
		Auth::logout();
		Session::flush();
	 	return redirect('/');
	}

}
