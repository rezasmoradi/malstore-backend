<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DiscountController extends Controller
{
    public function index()
    {
        return DiscountResource::collection(Discount::all());
    }

    public function show(Request $request)
    {
        return new DiscountResource($request->discount);
    }

    public function store(CreateDiscountRequest $request)
    {
        $discount = Discount::query()->create($request->all());

        return response(['discount' => new DiscountResource($discount)], Response::HTTP_CREATED);
    }

    public function update(UpdateDiscountRequest $request)
    {
        $discount = $request->discount->update($request->toArray());

        return \response(['discount' => new DiscountResource($discount)], Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request)
    {
        $request->discount->delete();
        return response(['discount has been deleted successfully'], Response::HTTP_OK);
    }

    public function restore(Request $request)
    {
        Discount::withTrashed()->find($request->discount_id)->restore();

        return response(['discount has been restored successfully'], Response::HTTP_OK);
    }

    public function delete(Request $request)
    {
        Discount::withTrashed()->find($request->discount_id)->forceDelete();

        return response(['discount has been successfully deleted permanently'], Response::HTTP_OK);
    }
}
