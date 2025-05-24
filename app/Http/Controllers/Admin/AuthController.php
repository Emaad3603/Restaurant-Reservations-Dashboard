<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * Show the login form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login request
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate login credentials
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Manual authentication to avoid throttling/cache issues
        $admin = AdminUser::where('email', $request->email)->first();
        
        if ($admin && Hash::check($request->password, $admin->password)) {
            // Login the admin user
            Auth::guard('admin')->login($admin, $request->boolean('remember'));
            
            // Regenerate the session
            $request->session()->regenerate();
            
            // Redirect to admin dashboard
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        }

        // Authentication failed
        return back()->withErrors([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the admin out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
