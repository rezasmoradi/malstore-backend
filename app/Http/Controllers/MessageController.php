<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index()
    {
        return response(['messages' => MessageResource::collection(Message::query()->orderByDesc('created_at')->get())]);
    }

    public function show(Request $request)
    {
        return new MessageResource($request->message);
    }

    public function store(CreateMessageRequest $request)
    {
        try {
            Message::query()->create($request->toArray());
            return response(['message' => 'message has been registered successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->message->delete();
            return response(['message' => 'message has been registered successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
