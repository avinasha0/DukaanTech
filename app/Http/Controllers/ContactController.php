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
        // Check if reCAPTCHA is configured
        $recaptchaSecretKey = config('services.recaptcha.secret_key');
        $recaptchaSiteKey = config('services.recaptcha.site_key');
        $isRecaptchaConfigured = $recaptchaSecretKey && $recaptchaSecretKey !== 'your_secret_key_here' && 
                                $recaptchaSiteKey && $recaptchaSiteKey !== 'your_site_key_here';

        // Validate the form data
        $validationRules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ];

        // Only require reCAPTCHA if it's properly configured
        if ($isRecaptchaConfigured) {
            $validationRules['g-recaptcha-response'] = 'required|string';
        }

        $request->validate($validationRules);

        // Verify reCAPTCHA only if configured
        if ($isRecaptchaConfigured) {
            $recaptcha = new ReCaptcha($recaptchaSecretKey);
            $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

            if (!$response->isSuccess()) {
                return back()->withErrors(['captcha' => 'Please complete the reCAPTCHA verification.'])->withInput();
            }
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

            // Check if email configuration is properly set
            $mailConfig = config('mail');
            $contactEmail = config('mail.contact_email', env('MAIL_CONTACT_EMAIL', 'info@dukaantech.com'));
            
            Log::info('Attempting to send contact form email', [
                'mail_driver' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'contact_email' => $contactEmail,
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name')
            ]);

            // Send email with proper error handling using raw HTML
            $htmlContent = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='utf-8'>
                <title>New Contact Form Submission</title>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #6E46AE, #00B6B4); color: white; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
                    .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
                    .field { margin-bottom: 15px; }
                    .field-label { font-weight: bold; color: #6E46AE; display: inline-block; width: 120px; }
                    .field-value { color: #333; }
                    .message-box { background: white; padding: 15px; border-left: 4px solid #00B6B4; margin: 15px 0; border-radius: 4px; }
                    .footer { margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='header'>
                    <h1>New Contact Form Submission</h1>
                    <p>DukaanTech</p>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='field-label'>Name:</span>
                        <span class='field-value'>{$emailData['firstName']} {$emailData['lastName']}</span>
                    </div>
                    <div class='field'>
                        <span class='field-label'>Email:</span>
                        <span class='field-value'>{$emailData['email']}</span>
                    </div>" . 
                    (!empty($emailData['phone']) ? "
                    <div class='field'>
                        <span class='field-label'>Phone:</span>
                        <span class='field-value'>{$emailData['phone']}</span>
                    </div>" : "") . 
                    (!empty($emailData['company']) ? "
                    <div class='field'>
                        <span class='field-label'>Company:</span>
                        <span class='field-value'>{$emailData['company']}</span>
                    </div>" : "") . "
                    <div class='field'>
                        <span class='field-label'>Subject:</span>
                        <span class='field-value'>" . ucfirst($emailData['subject']) . "</span>
                    </div>
                    <div class='message-box'>
                        <strong>Message:</strong><br>
                        {$emailData['message']}
                    </div>
                    <div class='footer'>
                        <p><strong>Submission Details:</strong></p>
                        <p>IP Address: {$emailData['ip']}</p>
                        <p>User Agent: {$emailData['userAgent']}</p>
                        <p>Timestamp: {$emailData['timestamp']}</p>
                    </div>
                </div>
            </body>
            </html>";

            Mail::html($htmlContent, function ($message) use ($emailData, $contactEmail) {
                $message->to($contactEmail)
                        ->subject('Dukaantech - New Contact Form Submission: ' . ($emailData['subject'] ?? 'No Subject'))
                        ->replyTo($emailData['email'] ?? 'noreply@example.com', ($emailData['firstName'] ?? '') . ' ' . ($emailData['lastName'] ?? ''));
            });

            // Log the contact form submission
            Log::info('Contact form submitted successfully', [
                'email' => $emailData['email'],
                'subject' => $emailData['subject'],
                'ip' => $emailData['ip'],
                'timestamp' => $emailData['timestamp'],
                'sent_to' => $contactEmail
            ]);

            return back()->with('success', 'Thank you for your message! We will get back to you within 24 hours.');

        } catch (\Swift_TransportException $e) {
            Log::error('Email transport error - check SMTP configuration', [
                'error' => $e->getMessage(),
                'mail_driver' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'ip' => $request->ip()
            ]);

            return back()->withErrors(['error' => 'Email service is currently unavailable. Please try again later or contact us directly.'])->withInput();
            
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'error_type' => get_class($e),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'ip' => $request->ip()
            ]);

            return back()->withErrors(['error' => 'Sorry, there was an error sending your message. Please try again later.'])->withInput();
        }
    }
}
