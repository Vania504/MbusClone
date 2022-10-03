<?php

namespace App\Http\Controllers;

use App\Models\BusImage;
use App\Models\ContentImage;
use App\Models\Image;
use App\Models\RouteCity;
use App\Models\RouteImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function list(string $type): JsonResponse
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Get Image list successfully',
            'data' => Image::where('type', $type)->paginate(10),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        Log::debug(json_encode(['request' => $request->all(), 'file' => $request->file('image'), 'files' => $_FILES]));
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:Bus,Content,Route,RoutePath',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $image_path = $request->file('image')->store($request->type, 'public');
        $data = Image::create([
            'name' => '',
            'type' => $request->type,
            'path' => $image_path,
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Create content successfully',
            'data' => $data,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:Bus,Content,Route,RoutePath',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $image = Image::find($id);
        if ($image) {
            $image_path = $request->file('image')->store($request->type, 'public');
            Storage::delete($image->path);
            $image->type = $request->type;
            $image->path = $image_path;
            $image->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Create content successfully',
            'data' => $image,
        ], 201);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $image = Image::find($id);
        Storage::delete($image->path);
        $image->delete();
        ContentImage::where('image_id', $id)->delete();
        RouteImage::where('image_id', $id)->delete();
        BusImage::where('image_id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete Image  successfully',
            'data' => []
        ]);
    }
}
