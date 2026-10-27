<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RoleGuru
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = Auth::user();

        if (!Auth::check() || !$user || !$user->hasRole('guru')) {
            abort(403, 'Hanya Guru yang boleh masuk!');
        }

        return $next($request);
    }
}
