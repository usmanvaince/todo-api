<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use App\Mail\UserRegistered;
use Exception;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'verification_code' => random_int(100000, 999999)
            ]);

            Mail::to($user)->send(new UserRegistered($user));

            DB::commit();

            return response()->json(['message' => 'User registered successfully. Please verify your email address.'], Response::HTTP_CREATED);

        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function verify(EmailVerificationRequest  $request)
    {
        $user = User::where('verification_code', $request->verification_code)
                    ->where('email', $request->email)
                    ->first();
        if (! $user) {
            return response()->json(['message' => 'Invalid verification code'], Response::HTTP_BAD_REQUEST);
        }
        $user->verification_code = null;
        $user->email_verified_at = now();
        $user->save();

        $token = $user->createToken($request->email)->plainTextToken;

        return response()->json(['message' => 'User verified successfully.', 'token' => $token]);

    }
}
