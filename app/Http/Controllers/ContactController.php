<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
            'website' => 'nullable|string|max:255',
            'recaptcha_token' => 'required|string',
        ]);

        if (!empty($validated['website'])) {
            return back()->withErrors(['spam' => 'Spam detected. Please try again.'])->withInput();
        }

        if (! $this->verifyRecaptcha($validated['recaptcha_token'])) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.'])->withInput();
        }

        // Create a record in the database
        $contact = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        // Try to send email
        try {
            $email = (new ContactMail($validated))
                ->replyTo($validated['email']);

            Mail::to('hello@murfy.uk')
                ->send($email);

            $contact->update(['status' => 'sent']);
        } catch (\Exception $e) {
            $contact->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }

        return back()->with('success', 'Message received! We will get back to you soon.');
    }
}
