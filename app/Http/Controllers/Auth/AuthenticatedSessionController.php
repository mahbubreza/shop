<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on role
        if (Auth::user()->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended('/'); // customer or other roles
    }

    public function destroy(Request $request): RedirectResponse
    {
        $role = Auth::user()->role ?? null;

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // customer goes to homepage
    }

}
