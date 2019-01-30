<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // from BaseController:
    /*
     * 		//share lets us pass data to all routes
		View::share('static_site',Config::get('lrc_settings.static_site'));
		View::share('lesson_menu', EieolSeries::where('published', '=', True)->get()->sortBy('menu_order'));

     */
}
