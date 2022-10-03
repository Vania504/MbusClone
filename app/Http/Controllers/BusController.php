<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\BusImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get Buses list successfully',
            'data' => Bus::where('status', '=', 'Active')->paginate(12),
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
            'message' => 'Get buses list successfully',
            'data' => Bus::all(),
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
            'status' => 'required|string|in:Active,Archive',
            'model' => 'required|string|max:255',
            'description' => 'required|string',
            'seats' => 'required|integer',
            'images' => 'present|array',
            'options' => 'present|array',
            'options.toilet' => 'boolean',
            'options.supply' => 'boolean',
            'options.socket' => 'boolean',
            'options.climate' => 'boolean',
            'options.wifi' => 'boolean',
            'options.tv' => 'boolean',
            'options.vip' => 'boolean',
            'options.ecology' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        $bus = Bus::create([
            'status' => $request->status,
            'model' => $request->model,
            'description' => $request->description,
            'seats' => $request->seats,
            'options' => $request->options,
        ]);
        if (isset($request->images)) {
            foreach ($request->images as $image) {
                BusImage::create([
                    'image_id' => $image,
                    'bus_id' => $bus->id,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Create bus successfully',
            'data' => $bus,
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
        $bus = Bus::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get bus successfully',
            'data' => $bus,
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
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Active,Archive',
            'model' => 'required|string|max:255',
            'description' => 'required|string',
            'seats' => 'required|integer',
            'images' => 'present|array',
            'options' => 'present|array',
            'options.toilet' => 'boolean',
            'options.supply' => 'boolean',
            'options.socket' => 'boolean',
            'options.climate' => 'boolean',
            'options.wifi' => 'boolean',
            'options.tv' => 'boolean',
            'options.vip' => 'boolean',
            'options.ecology' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        $bus = Bus::findOrFail($id);
        if ($bus) {
            $bus->status = $request->status ?? $bus->status;
            $bus->model = $request->model ?? $bus->model;
            $bus->description = $request->description ?? $bus->description;
            $bus->seats = $request->seats ?? $bus->seats;
            $bus->options = $request->options ?? $bus->options;
            $bus->save();
        }
        if (isset($request->images)) {
            BusImage::where('bus_id', $bus->id)->delete();
            foreach ($request->images as $image) {
                BusImage::create([
                    'image_id' => $image,
                    'bus_id' => $bus->id,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update bus successfully',
            'data' => $bus,
        ]);
    }
}
