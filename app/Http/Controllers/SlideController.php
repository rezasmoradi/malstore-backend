<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSlideRequest;
use App\Http\Resources\SlideResource;
use App\Models\Slide;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['slides' => SlideResource::collection(Slide::all())]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSlideRequest $request)
    {
        try {
            $file = $request->file('photo');
            $fileName = md5(time() . $file->getClientOriginalName());
            $file->storeAs('', $fileName, 'slides');
            /*$slide = Slide::query()->create([
                'photo' => $fileName,
                'url' => $request->url,
                'type' => $request->type,
                'first_feature' => $request->first_feature,
                'second_feature' => $request->second_feature,
                'third_feature' => $request->third_feature,
                'published_at' => $request->published_at,
                'expired_at' => $request->expired_at
            ]);*/
            $slide = Slide::query()->create(['photo' => $fileName, ...$request->except('photo')]);
            return response(['slide' => new SlideResource($slide)], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Slide $slide
     * @return \Illuminate\Http\Response
     */
    public function show(Slide $slide)
    {
        $slides = Slide::query()
            ->where('published_at', '>=', now())
            ->where('expired_at', '>', now())
            ->get();
        return response(['slides' => SlideResource::collection($slides)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Slide $slide
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slide $slide)
    {
        $fileName = $slide->photo;
        if ($request->has('photo')) {
            $file = $request->file('photo');
            $fileName = md5(time() . $file->getClientOriginalName());
            $file->storeAs('', $fileName, 'slides');
        }
        $slide->update(['photo' => $fileName, ...$request->except('photo')]);
        return response(['slide' => new SlideResource($slide)], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Slide $slide
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slide $slide)
    {
        try {
            $slide->delete();
            return \response(['message' => 'slide has been deleted successfully']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return \response(['message' => 'An error has occurred on the server'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
