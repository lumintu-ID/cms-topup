<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\navigation;
use App\Models\user_access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class access
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
        $newPath = array();
        $url = $request->getPathInfo();

        if ($url !== '/administrator/logout') {
            $path = explode('/', $url);
            if (count($path) > 3) {

                for ($i = 0; $i < 3; $i++) {
                    array_push($newPath, $path[$i]);
                }
            }

            if ($newPath) {
                $url = implode('/', $newPath);
                $nav = navigation::where('url', $url)->first();
            } else {
                $nav = navigation::where('url', $url)->first();
            }



            $nav_id = $nav->nav_id;

            $role = Auth::user()->role_id;

            $access = user_access::where('nav_id', $nav_id)->where('role_id', $role)->first();


            if (!$access) {
                return redirect('administrator');
            };
        }


        return $next($request);
    }
}
