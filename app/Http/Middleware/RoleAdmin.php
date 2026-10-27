<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if (!Auth::check() || !$user || !$user->hasRole('admin')) {
            abort(403, 'Hanya Admin yang boleh masuk!');
        }

        return $next($request);
    }
}
