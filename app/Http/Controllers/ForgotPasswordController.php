<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Show forgot password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send OTP to user's registered email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()
                ->withErrors(['email' => 'No account found with this email address. Please check and try again.'])
                ->withInput();
        }

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing OTPs for this email
        PasswordResetOtp::where('email', $user->email)->delete();

        // Store OTP with 10 minute expiry
        PasswordResetOtp::create([
            'email' => $user->email,
            'otp' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Mask email for privacy display
        $parts = explode('@', $user->email);
        $name = $parts[0];
        $domain = $parts[1];
        $maskedEmail = substr($name, 0, 2) . str_repeat('*', max(strlen($name) - 2, 0)) . '@' . $domain;

        // Send OTP via email
        try {
            Mail::raw("Hi {$user->name},\n\nYour Mantra password reset OTP is: {$otp}\n\nThis OTP will expire in 10 minutes.\n\nIf you did not request this, please ignore this email.", function ($message) use ($user) {
                $message->to($user->email)->subject('MANTRA — Password Reset OTP');
            });
        } catch (\Exception $e) {
            // Mail failure won't block the flow in dev (log driver)
        }

        // Store email in session for next step
        session(['reset_email' => $user->email, 'masked_email' => $maskedEmail, 'display_otp' => $otp]);

        return redirect()->route('password.verify')->with('success', "OTP sent to {$maskedEmail}");
    }

    // Show OTP verification form
    public function showVerifyForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        // Find OTP record
        $otpRecord = PasswordResetOtp::where('email', $email)->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'No OTP found. Please request a new one.']);
        }

        // Check if expired
        if (Carbon::now()->isAfter($otpRecord->expires_at)) {
            $otpRecord->delete();
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Verify OTP
        if (!Hash::check($request->otp, $otpRecord->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // OTP is valid - mark as verified in session
        session(['otp_verified' => true]);

        // Delete used OTP
        $otpRecord->delete();

        return redirect()->route('password.reset');
    }

    // Show reset password form
    public function showResetForm()
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.confirmed' => 'Passwords do not match.',
        ]);

        $email = session('reset_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        // Update password
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Clear session
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('success', 'Password reset successfully! Please login.');
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        // Create a fake request with the email
        $request->merge(['email' => $email]);
        return $this->sendOtp($request);
    }
}
