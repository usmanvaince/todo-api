<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verification_token' => md5(Str::random(32))
        ]);

        event(new UserRegistered($user));

        return response()->json(['message' => 'User registered successfully. Please verify your email address.'], Response::HTTP_CREATED);
    }

    public function verify(EmailVerificationRequest  $request)
    {
        $user = User::where('verification_token', $request->verification_token)
                    ->where('email', $request->email)
                    ->first();
        if (! $user) {
            return response()->json(['message' => 'Email not found'], Response::HTTP_UNAUTHORIZED);
        }
        $user->verification_token = null;
        $user->email_verified_at = now();
        $user->save();

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json(['message' => 'User verified successfully.', 'token' => $token]);

    }
}
