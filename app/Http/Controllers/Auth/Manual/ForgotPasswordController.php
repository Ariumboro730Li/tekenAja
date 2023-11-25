<?php

namespace App\Http\Controllers\Auth\Manual;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showForgetPasswordForm()
    {
        return view('auth.forget_pass');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(PasswordResetRequest $request)
    {
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        return view('auth.email.forget_pass', compact('token'));
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        return view('auth.forget_pass_link', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (!$updatePassword) {
            return back()
                ->withInput()
                ->with('error', 'Invalid token!');
        }

        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        $newUser = User::where('email', $request->email)->first();

        DB::table('password_resets')
            ->where(['email' => $request->email])
            ->delete();

        Auth::login($newUser);

        return redirect('/home')->with('message', 'Your password has been changed!');
    }
}
