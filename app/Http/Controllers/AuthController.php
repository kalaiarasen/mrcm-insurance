<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if agent is approved
            if ($user->hasRole('Agent') && $user->approval_status !== 'approved') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your agent account is pending approval. Please wait for admin confirmation.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            // Check user role and redirect accordingly
            if ($user->hasRole('Client')) {
                return redirect()->intended(route('dashboard'))->with('success', 'Welcome back!');
            } else {
                // For Super Admin, Admin, Agent - redirect to for-your-action
                return redirect()->intended(route('for-your-action'))->with('success', 'Welcome back!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm(): View
    {
        // Get all users with 'agent' role
        $agents = User::role('Agent')->orderBy('name')->get();
        return view('pages.auth.register', compact('agents'));
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'agent_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Generate unique client code with random 5-digit number
        do {
            $randomNumber = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
            $clientCode = '#C' . $randomNumber;
            $exists = User::where('client_code', $clientCode)->exists();
        } while ($exists);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'agent_id' => $request->agent_id,
            'client_code' => $clientCode,
        ]);

        // Assign Client role to newly registered users
        $user->assignRole('Client');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome aboard!');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm(): View
    {
        return view('pages.auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
                    ? back()->with(['success' => __($status)])
                    : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the reset password form.
     */
    public function showResetPasswordForm(string $token): View
    {
        return view('pages.auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle reset password request.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Show the agent registration form.
     */
    public function showAgentRegisterForm(): View
    {
        return view('pages.auth.register-agent');
    }

    /**
     * Handle agent registration request.
     */
    public function registerAgent(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_no' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'location' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'subscribe_newsletter' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'date_of_birth' => $request->date_of_birth,
            'location' => $request->location,
            'bank_account_number' => $request->bank_account_number,
            'password' => Hash::make($request->password),
            'subscribe_newsletter' => $request->has('subscribe_newsletter'),
            'approval_status' => 'pending',
        ]);

        // Assign Agent role to newly registered agent
        $user->assignRole('Agent');

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Your agent application has been submitted successfully! You will receive an email notification once your account is approved by our admin team.');
    }
}
