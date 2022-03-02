<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Config;

class ManageDonationSessionMarker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Config::get('settings.show_donation_popup') === "yes") {
            $page_ctr = 0;
            if ($request->session()->has('donation_page_ctr')) {
                $page_ctr = $request->session()->get('donation_page_ctr');
            }
            $page_ctr++;

            $request->session()->put('donation_page_ctr_limit', 1);
            $request->session()->put('donation_page_ctr', $page_ctr);
        }

        return $next($request);
    }
}
