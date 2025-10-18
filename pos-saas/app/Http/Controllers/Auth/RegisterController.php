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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => null, // Will be verified after activation
            'activation_token' => Str::random(60),
        ]);

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
            return redirect()->route('login')->with('error', 'Invalid activation link.');
        }

        if ($user->email_verified_at) {
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
