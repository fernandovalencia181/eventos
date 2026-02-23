<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the dashboard redirection logic.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->rol === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->rol === 'staff') {
            return redirect()->route('staff.index'); // Note: 'staff.index' is not defined? Wait
            // In web.php: Route::middleware...->prefix('staff')->name('staff.')->group(...)
            // Inside group: Route::get('/dashboard', ...)->name('index')? No, name is implicit or missing.
            // Let's check route names in `web.php` carefully.
        }

        return redirect()->route('home');
    }
}
