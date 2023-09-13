<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Http\Resources\CustomerGroupResource;
use App\Models\CustomerGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CustomerGroupController extends Controller
{
    public function index()
    {
        return CustomerGroupResource::collection(CustomerGroup::all());
    }

    public function store(CreateCustomerGroupRequest $request)
    {
        try {
            $customerGroup = CustomerGroup::query()->create([
                'groupable_id' => $request->group_member_id,
                'groupable_type' => $request->group_type,
                'name' => $request->name,
            ]);
            return response(['customer_group' => new CustomerGroupResource($customerGroup)], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateCustomerGroupRequest $request)
    {
        $customerGroup = $request->customer_group;
        if ($request->group_member_id) $customerGroup->groupable_id = $request->group_member_id;
        if ($request->group_type) $customerGroup->groupable_type = $request->group_type;
        if ($request->name) $customerGroup->name = $request->name;
        $customerGroup->save();

        return response(['customer_group' => new CustomerGroupResource($customerGroup)], Response::HTTP_ACCEPTED);
    }

    public function destroy(Request $request)
    {
        try {
            $request->customer_group->delete();
            return \response(['message' => 'customer group has been deleted successfully']);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
