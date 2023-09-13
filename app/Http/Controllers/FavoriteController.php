<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFavoriteRequest;
use App\Http\Requests\DeleteFavoriteRequest;
use App\Http\Resources\FavoriteCollection;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function index()
    {
        return \response(['favorites' => new FavoriteCollection(auth()->user()->favorites)]);
    }

    public function store(Request $request)
    {
        $favorite = auth()->user()->favorites()->create(['product_id' => $request->product_id]);

        return response(['favorite' => $favorite], Response::HTTP_CREATED);
    }

    public function destroy(DeleteFavoriteRequest $request)
    {
        try {
            auth()->user()->favorites()->where('product_id', $request->product_id)->delete();
            return \response(['message' => 'product has been deleted from user favorites list successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'product not found in the favorites list'], Response::HTTP_NOT_FOUND);
        }
    }
}
