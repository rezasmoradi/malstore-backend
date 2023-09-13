<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    public function index()
    {
        return SupplierResource::collection(Supplier::all());
    }

    public function store(CreateSupplierRequest $request)
    {
        try {
            DB::beginTransaction();

            $supplier = Supplier::query()->create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company' => $request->company,
                'phones' => json_encode($request->phones),
                'business_id' => $request->business_id
            ]);
            $supplier->addresses()->create($request->except(['first_name', 'last_name', 'company', 'business_id']));

            DB::commit();
            return response(['supplier' => new SupplierResource($supplier)], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request)
    {
        return \response(['supplier' => new SupplierResource($request->supplier)]);
    }

    public function update(UpdateSupplierRequest $request)
    {
        $supplier = $request->supplier;
        if ($request->has('first_name')) $supplier->first_name = $request->first_name;
        if ($request->has('last_name')) $supplier->last_name = $request->last_name;
        if ($request->has('company')) $supplier->company = $request->company;
        if ($request->has('business_id')) $supplier->business_id = $request->business_id;
        if ($request->has('phones')) $supplier->phones = json_encode($request->phones);
        $supplier->save();

        return \response(['supplier' => new SupplierResource($supplier)], Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request)
    {
        try {
            $supplier = $request->supplier;
            $supplier->addresses()->delete();
            $supplier->delete();
            return \response(['message' => 'supplier deleted successfully']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Request $request)
    {
        try {
            $supplier = $request->supplier;
            $supplier->addresses()->restore();
            $supplier->restore();
            return \response(['message' => new SupplierResource($supplier)]);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $supplier = $request->supplier;
            $supplier->addresses()->forceDelete();
            $supplier->forceDelete();
            DB::commit();

            return \response(['message' => 'supplier successfully deleted permanently']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
