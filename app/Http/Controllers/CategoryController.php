<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return response(['categories' => new CategoryCollection(Category::with(['subCategories'])->where('parent_id', '=', null)->get())]);
    }

    public function store(CreateCategoryRequest $request)
    {
        try {
            $name = $request->name;
            $thumbnailName = null;
            $imageName = null;
            $urlName = Str::slug($request->url_name);

            if ($request->has('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $thumbnailName = md5($name . $thumbnail->getClientOriginalName());
                $thumbnail->storeAs('', $thumbnailName, 'category_thumbnails');
            }

            if ($request->has('image')) {
                $image = $request->file('image');
                $imageName = md5($name . $image->getClientOriginalName());
                $image->storeAs('', $imageName, 'category_images');
            }

            $category = Category::query()->create([
                'name' => $name,
                'url_name' => $urlName,
                'thumbnail' => $thumbnailName,
                'image' => $imageName,
                'parent_id' => $request->parent_id
            ]);

            return response(['category' => new CategoryResource($category)], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred in create category'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request)
    {
        return \response(['category' => new CategoryResource($request->category)]);
    }

    public function update(UpdateCategoryRequest $request)
    {
        try {
            $request->category->update($request->toArray());
            return response(['category' => new CategoryResource($request->category)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->category->delete();
            return \response(['message' => 'category was deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
    }
}
