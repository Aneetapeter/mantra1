<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Handle Register
    public function register(Request $request)
    {
        // Check for existing user with same User ID
        $existingUserByName = User::where('name', $request->name)->first();

        if ($existingUserByName) {
            // User ID exists - check if email also matches
            if ($existingUserByName->email === $request->email) {
                // Same User ID and same email = already registered, redirect to login
                return redirect()->route('login')->with('info', 'You are already registered! Please login with your credentials.');
            } else {
                // Same User ID but different email = User ID is taken
                return back()->withErrors([
                    'name' => 'This User ID is already taken. Please choose a different User ID.',
                ])->withInput($request->except('password'));
            }
        }

        // Validate remaining input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required' => 'Please enter a User ID',
            'name.max' => 'User ID must not exceed 255 characters',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Redirect to login after register
        Auth::login($user);
        return redirect()->route('dashboard.intro');

    }

    public function login(Request $request)
    {
        // Rate limiting - max 5 attempts per minute
        $throttleKey = strtolower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->withInput($request->only('email'));
        }

        // Server-side validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Please enter your password',
            'password.min' => 'Password must be at least 8 characters',
        ]);

        $remember = $request->has('remember');
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user, $remember);
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->route('dashboard.intro');
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey, 60);

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
