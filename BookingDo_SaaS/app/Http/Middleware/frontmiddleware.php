<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\helper\helper;
use App\Models\Settings;

class frontmiddleware
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
        if (!env('ATLAS_EMBEDDED') && !file_exists(storage_path() . "/installed")) {
            return redirect('install');
            exit;
        }
        // if the current host contains the website domain
        $host = $_SERVER['HTTP_HOST'];
        if ($host  ==  env('WEBSITE_HOST')) {
            if ($request->vendor != "" || $request->vendor != null) {
                $vendordata = @helper::vendor_data($request->vendor);
                if (empty($vendordata)) {
                   abort(404);
                }
                @helper::language($vendordata->id);
                if (helper::otherappdata($vendordata->id)->maintenance_on_off == 1) {

                    return response(view('errors.maintenance'));
                }
                $checkplan = @helper::checkplan($vendordata->id, '3');
                $v = json_decode(json_encode($checkplan));
                if (@$v->original->status == 2) {
                    return response(view('errors.accountdeleted'));
                }
                if (@$vendordata->is_available == 2) {
                    return response(view('errors.accountdeleted'));
                }
            }
        }
        // if the current host doesn't contain the website domain (meaning, custom domain)
        else {
            // if the current package doesn't have 'custom domain' feature || the custom domain is not connected
            $settingdata = Settings::where('custom_domain', $host)->first();

            date_default_timezone_set(@helper::appdata($settingdata->id)->timezone);
            if (@$settingdata->vendor_id != "" || @$settingdata->vendor_id != null) {
                $user = User::where('id', @$settingdata->vendor_id)->first();
                @helper::language($user->id);
                if (@helper::otherappdata($user->id)->maintenance_on_off == 1) {

                    return response(view('errors.maintenance'));
                }
                if (@$user->is_available == 2) {
                    return response(view('errors.accountdeleted'));
                }
            }
        }
        return $next($request);
    }
}
