<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShipperRequest;
use App\Http\Resources\ShipperCollection;
use App\Http\Resources\ShipperResource;
use App\Models\Shipper;
use App\Models\Shopper;
use App\Rules\PhoneNumber;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ShipperController extends Controller
{
    public function index()
    {
        return new ShipperCollection(Shipper::all());
    }

    public function show(Request $request)
    {
        return new ShipperResource($request->shipper);
    }

    public function store(CreateShipperRequest $request)
    {
        try {
            $shipper = Shipper::query()->create([
                'name' => $request->name,
                'phones' => json_encode($request->phones),
            ]);
            return \response(['shopper' => new ShipperResource($shipper)], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $this->validate($request, [
                'name' => 'nullable|string|min:2|max:255',
                'phone' => ['nullable', 'numeric', 'max:14', new PhoneNumber()],
            ]);

            $shipper = $request->shipper;
            if (array_key_exists('name', $validated)) $shipper->name = $validated['name'];
            if (array_key_exists('phones', $validated)) $shipper->phones = json_encode($validated['phones']);
            $shipper->save();
            return \response(['shopper' => new ShipperResource($shipper)], Response::HTTP_ACCEPTED);

        } catch (ValidationException $exception) {
            return \response(['message' => $exception->getMessage()], $exception->getCode());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->shipper->delete();
            return \response(['message' => 'shopper has been deleted successfully']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore(Request $request)
    {
        try {
            $shipper = Shipper::withTrashed()->find($request->shipper_id);
            if ($shipper) {
                $shipper->restore();
                return \response(['shipper' => new ShipperResource($shipper)]);
            } else {
                throw new ModelNotFoundException('Shipper not found', Response::HTTP_NOT_FOUND);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request)
    {
        try {
            $shipper = Shipper::withTrashed()->find($request->shipper_id);
            if ($shipper) {
                $shipper->forceDelete();
                return \response(['message' => 'Shipper successfully deleted permanently']);
            } else {
                throw new ModelNotFoundException('Shipper not found', Response::HTTP_NOT_FOUND);
            }

        } catch (ModelNotFoundException $e) {
            return \response(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
