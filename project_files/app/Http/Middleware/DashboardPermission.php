<?php

namespace App\Http\Middleware;

use Closure;
use App\Permission;
class DashboardPermission
{
    use \App\Http\Controllers\Dashboard\NotificationTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$permission)
    {
        if (checkPermissions($permission)){
            return $next($request);
        }
//       if (checkDashboardPermissionFromCache($permission)){
//           return $next($request);
//       }
        toast('You Dont\'t Have Permission To Access This Page','error',AlertPosition());
        return redirect()->route('dashboard');
    }
}
