<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPasswordMail;

class ForgetPasswordManager extends Controller
{
    // Show forget password form
    public function forgetPassword()
    {
        return view('user.forget-password');
    }

    // Handle forget password form submission
    public function forgetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send email with reset link
        Mail::send('email.forget-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset your password');
        });

        // Success message
        return back()->with('status', 'We have e-mailed your password reset link!');
    }

    // Show reset password form
    public function resetPassword($token)
    {
        return view('user.new-password', ['token' => $token]);
    }

    

    // Handle password reset submission
    public function resetPasswordPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>=', now()->subHours(1))
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid or expired token!');
        }

        // Update password in the users table
        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        // Delete the token from password_resets table
        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('status', 'Your password has been changed!');
    }
}
