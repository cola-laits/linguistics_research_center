<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;

class ManageDonationCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Log::info(Request::cookie('donation_page_ctr'));
        $page_ctr = 0;
        if ($request->hasCookie('donation_page_ctr')) {
            $page_ctr = (int)Request::cookie('donation_page_ctr');
        }
        $page_ctr++;

        $response = $next($request);
        return $response->withCookie(cookie('donation_page_ctr', $page_ctr, 1400));

    }
}
