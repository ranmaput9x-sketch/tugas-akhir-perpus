<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
// Remove this line; HasRoles should be used in the User model, not here.

class DashboardController extends Controller
{
    public function index()
    {
        // /** @var \App\Models\user */
        // $user = Auth::user();

        // // Check if user is admin by role property
        // // Cek role admin dengan Spatie

        // if ($user->hasRole('admin')) {
        //     return view('dashboard.admin'); // pakai AdminLTE
        // }

        // return view('dashboard.user'); // pakai layouts.app
    }
}
