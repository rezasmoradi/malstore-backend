<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyRegisteredException;
use App\Http\Requests\DeleteAddressRequest;
use App\Http\Requests\RegisterAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function index()
    {
        return response(['addresses' => AddressResource::collection(auth('api')->user()->addresses)]);
    }

    public function store(RegisterAddressRequest $request)
    {
        try {
            $address = Address::query()->where($request->except(['postal_code', 'plaque']))->first();

            if ($address) {
                throw new AlreadyRegisteredException('Address already has been registered', Response::HTTP_BAD_REQUEST);
            } else {
                $address = auth()->user()->addresses()->create([
                    'province' => $request->post('province'),
                    'city' => $request->post('city'),
                    'address' => $request->post('address'),
                    'postal_code' => $request->post('postal_code'),
                    'plaque' => $request->post('plaque') ?: 0,
                ]);

                return response(['address' => new AddressResource($address)]);
            }

        } catch (AlreadyRegisteredException $exception) {
            return \response(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response(['message' => 'An error has occurred on server, try again later'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request)
    {
        return response(['addresses' => new AddressResource($request->address)]);
    }

    public function update(UpdateAddressRequest $request)
    {
        $address = $request->route('address');
        if ($address) {
            $address->update($request->validated());
            return response(['address' => new AddressResource($address)], Response::HTTP_OK);
        } else {
            return response(['message' => 'Address not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy(DeleteAddressRequest $request)
    {
        $address = $request->address;
        if (Order::query()->where('address_delivery_id', $address->id)->exists()) {
            $address->delete();
        } else {
            $address->forceDelete();
        }

        return response(['message' => 'Address has been deleted successfully'], Response::HTTP_OK);
    }
}
