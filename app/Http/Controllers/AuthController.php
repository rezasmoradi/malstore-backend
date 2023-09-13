<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyRegisteredException;
use App\Http\Requests\CodeVerificationRequest;
use App\Http\Requests\GenerateCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResendConfirmCodeRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(GenerateCodeRequest $request)
    {
        try {
            $email = $request->post('email');
            $code = random_int(100000, 999999);
            $expiration = (int)config('auth.code_expiration');
            $codeExpiredAt = now()->addMinutes($expiration);

            if ($user = User::query()->where('email', $email)->first()) {
                if ($user->confirm_code && now()->lessThan($user->code_expired_at)) {
                    throw new AlreadyRegisteredException('لطفاً ایمیل خود را چک کنید', Response::HTTP_CREATED);
                }
                if ($user->confirmed_at) {
                    throw new AlreadyRegisteredException('Email has already been verified.');
                } else {
                    $user->confirm_code = $code;
                    $user->email = $email;
                    $user->code_expired_at = $codeExpiredAt;
                    $user->save();
                }
            } else {
                User::query()->create([
                    'confirm_code' => $code,
                    'email' => $email,
                    'code_expired_at' => $codeExpiredAt
                ]);
            }

            Mail::send(['html' => 'verify'], ['code' => $code], function (Message $message) use ($email) {
                $message->to($email);
                $message->from(env('MAIL_USERNAME'));
                $message->subject('کد تأیید فروشگاه مال استور');
            });

            return response(['message' => 'Verification code was sent to your email'], Response::HTTP_CREATED);

        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'An error occurred in sending the code']);
        }
    }

    public function resend(ResendConfirmCodeRequest $request)
    {
        try {
            $email = $request->post('email');
            $code = random_int(100000, 999999);
            $expiration = (int)config('auth.code_expiration');
            $codeExpiredAt = now()->addMinutes($expiration);

            if ($user = User::query()->where('email', $email)->first()) {
                if ($user->confirmed_at) {
                    throw new AlreadyRegisteredException('ایمیل قبلاً تأیید شده است');
                } else {
                    $user->confirm_code = $code;
                    $user->email = $email;
                    $user->code_expired_at = $codeExpiredAt;
                    $user->save();
                }
            } else {
                return \response(['message' => 'کاربر یافت نشد'], Response::HTTP_NOT_FOUND);
            }

            /*Mail::send(['html' => 'verify'], ['code' => $code], function (Message $message) use ($email) {
                $message->to($email);
                $message->from(env('MAIL_USERNAME'));
                $message->subject('کد تأیید فروشگاه مال استور');
            });*/

            return response(['message' => 'کد تأیید مجدداً به ایمیل شما ارسال شد'], Response::HTTP_OK);

        } catch (\Exception $exception) {
            Log::error($exception);
            return response(['message' => 'An error occurred in sending the code']);
        }
    }

    public function verify(CodeVerificationRequest $request)
    {
        if ($user = User::query()->where('email', $request->post('email'))->first()) {

            if ($user->confirm_code !== $request->post('code')) {
                return response(['message' => 'کد وارد شده اشتباه است'], Response::HTTP_BAD_REQUEST);
            }
            if (now()->lessThan($user->code_expired_at)) {
                $user->confirm_code = null;
                $user->code_expired_at = null;
                $user->confirmed_at = now();
                $user->save();

                $tokenResponse = $user->createToken('Personal Access Token');
                $token = $tokenResponse->token;

                $expirationDays = env('TOKEN_EXPIRATION_IN_DAYS', 30);
                $token['expires_at'] = now()->addDays($expirationDays);
                $token->save();

                return \response([
                    'user' => new UserResource($user),
                    'token' => $tokenResponse->accessToken,
                    'token_type' => 'Bearer',
                    'expire_at' => $token->expires_at->toDateTimeString()
                ], Response::HTTP_CREATED);
            } else {
                return \response(['message' => 'زمان ارسال کد به پایان رسیده است؛ مجدداً تلاش کنید'], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return \response(['message' => 'کاربری با این ایمیل وجود ندارد'], Response::HTTP_NOT_FOUND);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::query()->where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                $tokenResponse = $user->createToken('Personal Access Token');
                $token = $tokenResponse->token;
                $expiration = env('TOKEN_EXPIRATION', 30);
                $token->expires_at = now()->addDays($expiration);
                $token->save();

                return \response([
                    'token' => $tokenResponse->accessToken,
                    'token_type' => 'Bearer',
                    'expire_at' => $token->expires_at->toDateTimeString()
                ]);
            } else {
                return \response(['message' => 'کاربری با این مشخصات یافت نشد'], Response::HTTP_NOT_FOUND);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred in user data processing'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout()
    {
        auth('api')->user()->token()->revoke();

        return \response(['message' => 'User was logged out successfully']);
    }
}
