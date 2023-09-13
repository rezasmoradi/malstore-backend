<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\DeletePhotoRequest;
use App\Http\Requests\DeleteRatingRequest;
use App\Http\Requests\RatingRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductFeatureValue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->where('category_id', $request->route()->parameter('category_id'))
            ->get(['id', 'name', 'model', 'category_id', 'display_name', 'slug']);

        return new ProductCollection($products);
    }

    public function store(CreateProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product_data = $request->except(['images', 'features', 'colors', 'tags', 'best_features', 'meta_keywords']);
            $product_data['slug'] = Str::slug($product_data['model']);

            $product_data['width'] = $product_data['dimensions']['width'];
            $product_data['length'] = $product_data['dimensions']['length'];
            if (array_key_exists('height', $product_data['dimensions'])) {
                $product_data['height'] = $product_data['dimensions']['height'];
            }

            $product_data['best_features'] = json_encode($request->best_features);
            $product_data['meta_keywords'] = implode(',', $request->meta_keywords);

            $product = Product::query()->create($product_data);

            foreach ($request->images as $image) {
                $originalFileName = $image['file']->getClientOriginalName();
                $fileName = md5(time() . $originalFileName);
                $image['file']->storeAs($product->id, $fileName, 'product_images');
                $product->images()->create(['name' => $fileName, 'main' => (int)$image['main']]);
            }

            foreach ($request->colors as $color) {
                $product->colors()->firstOrCreate([
                    'code' => $color['code'],
                    'name' => $color['name'],
                    'stock' => (int)$color['stock']
                ]);
            }

            foreach ($request->post('tags') as $tag) $product->tags()->firstOrCreate(['name' => $tag]);

            foreach ($request->post('features') as $feature) {
                $product->features()->firstOrCreate(['name' => $feature['name'], 'value' => $feature['value']]);
            }

            DB::commit();
            return response(['product' => new ProductResource($product)], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request)
    {
        if ($request->product) {
            return new ProductResource($request->product);
        }

        return response(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
    }

    public function update(UpdateProductRequest $request)
    {
        try {
            $product = $request->product;
            DB::beginTransaction();

            if ($request->has('images')) {
                foreach ($request->file('images') as $file) {
                    $originalFileName = $file->getClientOriginalName();
                    $fileName = md5(time() . $originalFileName);
                    $file->storeAs($product->id, $fileName, 'product_images');
                    if ($originalFileName === $request->main_photo) {
                        $product->images()->create(['name' => $fileName, 'main' => 1]);
                    } else {
                        $product->images()->create(['name' => $fileName]);
                    }
                }
            }

            $product_data = $request->except(['images', 'features', 'colors']);
            if (count($product_data)) $product->update($product_data);

            if ($request->tags) {
                foreach ($request->tags as $tag) {
                    $product->tags()->firstOrCreate(['name' => $tag]);
                }
            }

            if ($request->colors) {
                foreach ($request->colors as $color) {
                    $product->colors()->firstOrCreate(['name' => $color]);
                }
            }

            if ($request->has('features')) {
                foreach ($request->post('features') as $feature) {
                    $productFeature = ProductFeature::query()->firstOrCreate(['name' => $feature['name']]);
                    ProductFeatureValue::query()->create([
                        'product_id' => $product->id,
                        'feature_id' => $productFeature->id,
                        'value' => $feature['value'],
                        'stock' => $request->stock,
                        'active' => $request->active
                    ]);
                }
            }
            DB::commit();
            return response(['product' => new ProductResource($product)], Response::HTTP_ACCEPTED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred in update the product'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deletePhoto(Request $request)
    {
        $product = $request->product;
        $image = $product->images()->where('name', $request->image)->first();

        if ($image) {
            Storage::disk('product_images')->delete($product->id . '/' . $image->name);
            $image->delete();

            return response(['product' => new ProductResource($product)], Response::HTTP_OK);
        } else {
            return \response(['message' => 'Product image not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function delete(Request $request)
    {
        try {
            $request->product->delete();
            return response(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function restore(Request $request)
    {
        try {
            $product = Product::withTrashed()->where('slug', $request->slug)->first();
            if ($product) $product->restore();

            return response(['message' => 'Product has been restored successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $product = Product::withTrashed()->where('slug', $request->slug)->first();
            if ($product) {
                Storage::disk('product_images')->deleteDirectory($product->id);
                $product->images()->delete();
                $product->colors()->delete();
                $product->tags()->delete();
                $product->forceDelete();
            }

            return response(['message' => 'Product has been successfully deleted permanently'], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function rating(RatingRequest $request)
    {
        try {
            $request->product->rating()->create(['user_id' => auth()->id(), 'rate' => $request->rate]);
            return response(['message' => 'rating for the product has been registered successfully'], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'rating for the product has already been registered'], Response::HTTP_BAD_REQUEST);
        }
    }
}
