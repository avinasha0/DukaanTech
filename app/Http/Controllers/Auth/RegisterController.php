<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Account;
use App\Rules\GmailOnly;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new GmailOnly()],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'string'],
        ]);

        // Verify reCAPTCHA
        $recaptcha = new \ReCaptcha\ReCaptcha(config('services.recaptcha.secret_key'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

        if (!$response->isSuccess()) {
            return back()->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA verification.'])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Will be verified after activation
            'activation_token' => Str::random(60),
        ]);

        // Create account/organization for the user
        $baseSlug = \Illuminate\Support\Str::slug($request->restaurant_name);
        $slug = $baseSlug;
        $counter = 1;
        
        // Ensure slug is unique
        while (Account::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $account = Account::create([
            'name' => $request->restaurant_name,
            'slug' => $slug,
            'phone' => $request->phone,
            'owner_id' => $user->id,
            'status' => 'trial',
        ]);

        // Assign the user to the tenant
        $user->update(['tenant_id' => $account->id]);

        // Assign admin role to the account owner (first user)
        $adminRole = \App\Models\Role::where('slug', 'admin')->first();
        if ($adminRole) {
            $user->assignRole($adminRole);
        }

        // Send activation email
        $this->sendActivationEmail($user);

        return redirect()->route('register.success')->with('email', $user->email);
    }

    protected function sendActivationEmail(User $user)
    {
        Mail::send('emails.activation', [
            'user' => $user,
            'activationUrl' => route('activate', $user->activation_token)
        ], function ($message) use ($user) {
            $message->to($user->email)
                   ->subject('Activate Your Account - POS SaaS');
        });
    }

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            // Login route uses guest middleware: already-signed-in users get bounced to HOME
            // without seeing the flash error, which looks like the link "still works".
            if (Auth::check()) {
                return redirect()->route('organization.setup')
                    ->with('info', 'This activation link is no longer valid. You are already signed in.');
            }

            return redirect()->route('login')->with('error', 'Invalid activation link.');
        }

        if ($user->email_verified_at) {
            if (Auth::check() && Auth::id() === $user->id) {
                return redirect()->route('organization.setup')
                    ->with('info', 'Your account is already activated.');
            }

            return redirect()->route('login')->with('error', 'Account already activated.');
        }

        $user->update([
            'email_verified_at' => now(),
            'activation_token' => null,
        ]);

        Auth::login($user);

        return redirect()->route('organization.setup');
    }

    public function registerSuccess()
    {
        return view('auth.register-success');
    }
}
