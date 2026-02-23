<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\PasswordChangedMail;
use App\Mail\StudyReminderMail;
use App\Mail\XpAlertMail;

class SettingController extends Controller
{
    // ── Show Settings Page ─────────────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();
        return view('setting', compact('user'));
    }

    // ── Update Profile (name + email) ──────────────────────────────────────
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()
            ->with('success', 'Profile updated successfully!')
            ->with('_tab', $request->input('_tab', 'profile'));
    }

    // ── Change Password ────────────────────────────────────────────────────
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->with('_tab', 'security');
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Send security alert email to the user's registered email
        try {
            Mail::to($user->email)->send(new PasswordChangedMail($user->name));
        } catch (\Exception $e) {
            // Silently fail — don't break the flow if mail fails
        }

        return redirect()->route('settings')
            ->with('success', 'Password updated! A security alert was sent to ' . $user->email)
            ->with('_tab', 'security');
    }

    // ── Save Preferences (AJAX) ────────────────────────────────────────────
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'key' => 'required|in:dark_mode,compact_sidebar,notify_study,notify_xp',
            'value' => 'required|in:0,1',
        ]);

        $prefs = $user->preferences ?? [];
        $prefs[$request->key] = (bool) $request->value;
        $user->update(['preferences' => $prefs]);

        return response()->json(['success' => true, 'preferences' => $prefs]);
    }

    // ── Delete Account ─────────────────────────────────────────────────────
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'confirm_password' => 'required',
        ]);

        if (!Hash::check($request->confirm_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['confirm_password' => 'Password is incorrect.'])
                ->with('_tab', 'account');
        }

        // Logout first, then delete
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Delete related data then the user
        $user->delete();

        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }
}
