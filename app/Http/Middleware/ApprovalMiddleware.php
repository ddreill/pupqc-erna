<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;

class ApprovalMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (!auth()->user()->is_approved) {
                auth()->logout();

                return redirect()->route('login')->with('message', trans('global.yourAccountNeedsAdminApproval'));
            }
        }

        return $next($request);
    }
}
