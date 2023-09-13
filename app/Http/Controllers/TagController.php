<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    public function show(Request $request)
    {
        $tags = Tag::query()->where('name', 'like', '%' . $request->search . '%')->get();

        return response(['tags' => $tags], Response::HTTP_OK);
    }
}
