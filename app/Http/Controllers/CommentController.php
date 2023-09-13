<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\DeleteCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request)
    {
        $comment = auth()->user()->comments()->create([
            'body' => $request->body,
            'reply_to' => $request->reply_to,
        ]);

        return response(['comment' => new CommentResource($comment)]);
    }

    public function destroy(DeleteCommentRequest $request)
    {
        try {
            $request->comment->delete();
            return response(['message' => 'comment has been deleted successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
