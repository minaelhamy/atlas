<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\helper;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::user() && Auth::user()->type == 3) {
            $host = $_SERVER['HTTP_HOST'];
            if ($host  ==  env('WEBSITE_HOST')) {
                $user = User::where('slug', $request->vendor)->first();
                date_default_timezone_set(helper::appdata($user->id)->timezone);
                helper::language($user->id);
            } else {
                $settingdata = User::where('custom_domain', $host)->first();
                date_default_timezone_set(helper::appdata($settingdata->id)->timezone);
                helper::language($settingdata->id);
            }
            return $next($request);
        }
        return redirect($request->vendor);
    }
}
