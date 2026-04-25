<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['email' => $request->string('email')->toString()]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $email = (string) $request->input('email');
        $code = (string) $request->input('code');
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $resetRecord) {
            return back()->withInput($request->only('email'))
                ->withErrors(['code' => 'No reset code was found for this email.']);
        }

        if (now()->diffInMinutes($resetRecord->created_at) > 15) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            return back()->withInput($request->only('email'))
                ->withErrors(['code' => 'The reset code has expired. Please request a new one.']);
        }

        if (! Hash::check($code, $resetRecord->token)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['code' => 'The reset code is invalid.']);
        }

        $user = User::query()->where('email', $email)->firstOrFail();
        $user->forceFill([
            'password' => Hash::make((string) $request->input('password')),
            'remember_token' => Str::random(60),
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Your password has been reset.');
    }
}
