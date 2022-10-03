<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\RouteCity;
use App\Models\RouteImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index', 'show', 'carousel']]);
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
            'message' => 'Get routes list successfully',
            'data' => Route::where('status', '=', 'Active')->paginate(12),
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
            'message' => 'Get routes list successfully',
            'data' => Route::paginate(12),
        ]);
    }

    public function carousel(int $count): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get content list successfully',
            'data' => Route::inRandomOrder()->limit($count)->get(),
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
            'departure' => 'required|string',
            'destination' => 'required|string',
            'bus_id' => 'required|integer',
            'departure_time' => 'required|string',
            'route_path_image_id' => 'required|integer',
            'ukraine_city' => 'present|array',
            'ukraine_city.*.name' => 'required',
            'ukraine_city.*.lat' => 'required',
            'ukraine_city.*.lng' => 'required',
            'foreign_city' => 'present|array',
            'foreign_city.*.name' => 'required',
            'foreign_city.*.lat' => 'required',
            'foreign_city.*.lng' => 'required',
            'status' => 'required|string|in:Active,Archive',
            'departure_days' => 'present|array',
            'departure_days.mon' => 'required',
            'departure_days.tue' => 'required',
            'departure_days.wed' => 'required',
            'departure_days.thu' => 'required',
            'departure_days.fri' => 'required',
            'departure_days.sat' => 'required',
            'departure_days.sun' => 'required',
            'driver_phones' => 'present|array',
            'driver_phones.*.name' => 'required',
            'driver_phones.*.phone' => 'required',
            'route_time' => 'present|array',
            'route_time.*.time' => 'required',
            'route_time.*.city' => 'required',
            'route_time.*.is_reverse' => 'required',
            'images' => 'present|array'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        $route = Route::create([
            'departure' => $request->departure,
            'destination' => $request->destination,
            'bus_id' => $request->bus_id,
            'departure_time' => $request->departure_time,
            'route_path_image_id' => $request->route_path_image_id,
            'departure_days' => $request->departure_days,
            'driver_phones' => $request->driver_phones,
            'route_time' => $request->route_time,
            'status' => $request->status,
        ]);

        if (isset($request->images)) {
            foreach ($request->images as $image) {
                RouteImage::create([
                    'image_id' => $image,
                    'route_id' => $route->id,
                ]);
            }
        }
        foreach ($request->ukraine_city as $city) {
            RouteCity::create([
                'route_id' => $route->id,
                'name' => $city['name'],
                'lat' => $city['lat'],
                'lng' => $city['lng'],
                'type' => 'Ukraine',
            ]);
        }
        if (!empty($request->foreign_city)) {
            foreach ($request->foreign_city as $city) {
                RouteCity::create([
                    'route_id' => $route->id,
                    'name' => $city['name'],
                    'lat' => $city['lat'],
                    'lng' => $city['lng'],
                    'type' => 'Foreign',
                ]);
            }
        }

        $route = Route::find($route->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Create route successfully',
            'data' => $route,
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
        $route = Route::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get route successfully',
            'data' => $route,
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
            'departure' => 'required|string',
            'destination' => 'required|string',
            'bus_id' => 'required|integer',
            'departure_time' => 'required|string',
            'route_path_image_id' => 'required|integer',
            'ukraine_city' => 'present|array',
            'ukraine_city.*.name' => 'required',
            'ukraine_city.*.lat' => 'required',
            'ukraine_city.*.lng' => 'required',
            'foreign_city' => 'present|array',
            'foreign_city.*.name' => 'required',
            'foreign_city.*.lat' => 'required',
            'foreign_city.*.lng' => 'required',
            'images' => 'present|array',
            'status' => 'required|string|in:Active,Archive',
            'departure_days' => 'present|array',
            'departure_days.mon' => 'required',
            'departure_days.tue' => 'required',
            'departure_days.wed' => 'required',
            'departure_days.thu' => 'required',
            'departure_days.fri' => 'required',
            'departure_days.sat' => 'required',
            'departure_days.sun' => 'required',
            'driver_phones' => 'present|array',
            'driver_phones.*.name' => 'required',
            'driver_phones.*.phone' => 'required',
            'route_time' => 'present|array',
            'route_time.*.time' => 'required',
            'route_time.*.city' => 'required',
            'route_time.*.is_reverse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        $route = Route::findOrFail($id);
        if ($route) {
            $route->departure = $request->departure;
            $route->destination = $request->destination;
            $route->bus_id = $request->bus_id;
            $route->departure_time = $request->departure_time;
            $route->route_path_image_id = $request->route_path_image_id;
            $route->departure_days = $request->departure_days;
            $route->driver_phones = $request->driver_phones;
            $route->route_time = $request->route_time;
            $route->status = $request->status;
            $route->save();
        }

        if (isset($request->images)) {
            RouteImage::where('route_id', $route->id)->delete();
            foreach ($request->images as $image) {
                RouteImage::create([
                    'image_id' => $image,
                    'route_id' => $route->id,
                ]);
            }
        }

        RouteCity::where('route_id', $id)->delete();
        foreach ($request->ukraine_city as $city) {
            RouteCity::create([
                'route_id' => $route->id,
                'name' => $city['name'],
                'lat' => $city['lat'],
                'lng' => $city['lng'],
                'type' => 'Ukraine',
            ]);
        }
        if (!empty($request->foreign_city)) {
            foreach ($request->foreign_city as $city) {
                RouteCity::create([
                    'route_id' => $route->id,
                    'name' => $city['name'],
                    'lat' => $city['lat'],
                    'lng' => $city['lng'],
                    'type' => 'Foreign',
                ]);
            }
        }

        $route = Route::find($route->id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get route successfully',
            'data' => $route,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        Route::find($id)->delete();
        RouteImage::where('route_id', $id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete routes successfully',
            'data' => []
        ]);
    }
}
