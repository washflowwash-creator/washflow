<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset code request.
     */
    public function store(Request $request): RedirectResponse
    {
        Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ])->validate();

        $email = (string) $request->input('email');
        $user = User::query()->where('email', $email)->firstOrFail();
        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($code),
                'created_at' => now(),
            ]
        );

        Mail::mailer(config('mail.default'))->raw(
            "Hi {$user->name},\n\nYour WashFlow password reset code is: {$code}\n\nThis code will expire in 15 minutes.",
            static function ($message) use ($email): void {
                $message->to($email)->subject('WashFlow Password Reset Code');
            }
        );

        return redirect()->route('password.reset', ['email' => $email])
            ->with('status', 'A password reset code was sent to your email.');
    }
}
