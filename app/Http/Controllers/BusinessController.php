<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class BusinessController extends Controller
{
    public function index()
    {
        return Business::all();
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validate($request, ['name' => 'required|string|min:2|max:100']);
            $business = Business::query()->create($validated);
            return response(['business' => $business], Response::HTTP_CREATED);

        } catch (ValidationException $e) {
            return \response(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $request->business->delete();
            return response(['message' => 'Business has been deleted successfully']);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
