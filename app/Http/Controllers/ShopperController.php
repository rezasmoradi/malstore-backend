<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShopperRequest;
use App\Http\Requests\UpdateShopperRequest;
use App\Http\Resources\ShopperCollection;
use App\Http\Resources\ShopperResource;
use App\Models\Shopper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ShopperController extends Controller
{
    public function index()
    {
        return new ShopperCollection(Shopper::all());
    }

    public function show(Request $request)
    {
        return new ShopperResource($request->shopper);
    }

    public function store(CreateShopperRequest $request)
    {
        try {
            $shopper = Shopper::query()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'shop_name' => $request->shop_name,
                'phones' => json_encode($request->phones),
            ]);
            return response(['shopper' => new ShopperResource($shopper)], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateShopperRequest $request)
    {
        $shopper = $request->shopper;
        if ($request->has('first_name')) $shopper->first_name = $request->first_name;
        if ($request->has('last')) $shopper->last = $request->last;
        if ($request->has('shop_name')) $shopper->shop_name = $request->shop_name;
        if ($request->has('phones')) $shopper->phones = json_encode($request->phones);
        $shopper->save();
        return \response(['shopper' => new ShopperResource($shopper)], Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request)
    {
        try {
            $request->shopper->delete();
            return \response(['message' => 'shopper has been deleted successfully']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
