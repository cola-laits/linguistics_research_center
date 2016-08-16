<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	
	public function __construct()
	{
		//share lets us pass data to all routes
		View::share('static_site',Config::get('lrc_settings.static_site'));
		View::share('lesson_menu', EieolSeries::where('published', '=', True)->get()->sortBy('menu_order'));
	}
	
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
