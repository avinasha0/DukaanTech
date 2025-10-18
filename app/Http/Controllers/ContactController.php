<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use ReCaptcha\ReCaptcha;

class ContactController extends Controller
{
    public function show()
    {
        return view('pages.contact-us');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'g-recaptcha-response' => 'required|string',
        ]);

        // Verify reCAPTCHA
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret_key'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

        if (!$response->isSuccess()) {
            return back()->withErrors(['captcha' => 'Please complete the reCAPTCHA verification.'])->withInput();
        }

        try {
            // Prepare email data
            $emailData = [
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'company' => $request->input('company'),
                'subject' => $request->input('subject'),
                'message' => $request->input('message'),
                'ip' => $request->ip(),
                'userAgent' => $request->userAgent(),
                'timestamp' => now()->format('Y-m-d H:i:s'),
            ];

            // Send email (you can customize this based on your email configuration)
            Mail::send('emails.contact-form', $emailData, function ($message) use ($emailData) {
                $message->to(config('mail.contact_email', 'info@dukaantech.com'))
                        ->subject('New Contact Form Submission: ' . $emailData['subject'])
                        ->replyTo($emailData['email'], $emailData['firstName'] . ' ' . $emailData['lastName']);
            });

            // Log the contact form submission
            Log::info('Contact form submitted successfully', [
                'email' => $emailData['email'],
                'subject' => $emailData['subject'],
                'ip' => $emailData['ip'],
                'timestamp' => $emailData['timestamp']
            ]);

            return back()->with('success', 'Thank you for your message! We will get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'ip' => $request->ip()
            ]);

            return back()->withErrors(['error' => 'Sorry, there was an error sending your message. Please try again later.'])->withInput();
        }
    }
}
