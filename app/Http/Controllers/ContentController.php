<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\ContentImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get content list successfully',
            'data' => Content::all(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get content list successfully',
            'data' => Content::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'text' => 'required|string',
            'section' => 'required|string',
            'images' => 'present|array',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $content = Content::create([
            'title' => $request->title,
            'content' => $request->text,
            'section' => $request->section,
        ]);
        if (isset($request->images)) {
            foreach ($request->images as $image) {
                ContentImage::create([
                    'image_id' => $image,
                    'content_id' => $content->id,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Create content successfully',
            'data' => $content,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $content = Content::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get content successfully',
            'data' => $content,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'title' => 'required|string',
            'text' => 'required|string',
            'section' => 'required|string',
            'images' => 'present|array',
        ]);

        $content = Content::findOrFail($id);
        if ($content) {
            $content->title = $request->title;
            $content->content = $request->text;
            $content->section = $request->section;
            $content->save();
        }
        if (isset($request->images)) {
            ContentImage::where('content_id', $content->id)->delete();
            foreach ($request->images as $image) {
                ContentImage::create([
                    'image_id' => $image,
                    'content_id' => $content->id,
                ]);
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Get content successfully',
            'data' => $content,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        Content::find($id)->delete();
        ContentImage::where('content_id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete settings successfully',
            'data' => []
        ]);
    }
}
