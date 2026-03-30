<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|min:5',
        ]);

        $data = $request->only('name', 'email', 'message');

        try {
            // Send the email to the admin email defined in .env
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new ContactMail($data));

            return back()->with('contact_success', 'Message sent successfully!');
        } catch (\Exception $e) {
            // Log the actual error message so we can see what's wrong if it fails
            \Illuminate\Support\Facades\Log::error('Contact Form Email Error: ' . $e->getMessage());
            return back()->with('contact_error', 'Sorry, there was an error sending your message. Please try again later. (' . $e->getMessage() . ')');
        }
    }
}
