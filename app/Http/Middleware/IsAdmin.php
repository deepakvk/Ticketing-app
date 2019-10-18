<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Auth;
class IsAdmin
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
        
    // Get list of user roles
    $UserRoles = DB::table('roles')->join('role_user','role_id', '=', 'roles.id')->where('user_id', '=', Auth::user()->id)->pluck('name');
    // Check if this user has admin role
    $isAdmin = false;
    foreach($UserRoles as $role)
    {
        if($role == 'administrator')
        {
            $isAdmin = true;
        }
    }

    // Snippet below according to Laravel's doc
    if( ! $isAdmin )
    {
        if ($request->ajax()) {
            return response('Unauthorized.', 401);
        } else {
            return redirect()->back(); //maybe a modal window to say access denied here...
        }
    }

    return $next($request);
    }
}
