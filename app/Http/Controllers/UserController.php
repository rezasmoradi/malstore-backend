<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function me()
    {
        return \response(['user' => new UserResource(auth('api')->user())]);
    }

    public function show(Request $request)
    {
        return new UserResource($request->user);
    }

    public function update(UpdateUserRequest $request)
    {
        try {
            $data = $request->toArray();
            $user = auth()->user();

            if (array_key_exists('password', $data)) {
                $data['password'] = bcrypt($data['password']);
            }

            $user->update($data);

            return response(['user' => new UserResource($user)], Response::HTTP_ACCEPTED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred in user updating'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAvatar(UpdateAvatarRequest $request)
    {
        try {
            $user = auth()->user();

            if ($user->avatar) {
                Storage::disk('avatars')->delete($user->avatar);
            }
            $file = $request->file('avatar');
            $fileName = md5($user->id . time() . $file->getClientOriginalName());
            $file->storeAs('', $fileName, 'avatars');

            $user->avatar = $fileName;
            $user->save();

            return \response(['avatar' => asset('storage/avatars/' . $user->avatar)]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred in update avatar']);
        }
    }

    public function promote(Request $request)
    {
        $user = $request->user;
        $user->role = User::ROLE_ADMIN;
        $user->save();

        return \response(['message' => 'User promoted successfully'], Response::HTTP_OK);
    }
}
