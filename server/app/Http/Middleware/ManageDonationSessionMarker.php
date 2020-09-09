<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Request;

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
        $now = new Carbon();
        $donation_begin = Carbon::create(2020, 9,15, 0,0,0, 'America/Chicago');
        $donation_end   = Carbon::create(2020, 9,17, 0,0,0, 'America/Chicago');
        if ($now->lessThan($donation_begin) || $now->greaterThan($donation_end)) {
            return $next($request);
        }

        $page_ctr = 0;
        if ($request->session()->has('donation_page_ctr')) {
            $page_ctr = $request->session()->get('donation_page_ctr');
        }
        $page_ctr++;

        $request->session()->put('donation_page_ctr_limit', 1);
        $request->session()->put('donation_page_ctr', $page_ctr);

        return $next($request);
    }
}
