<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\RouteCity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['startCity', 'nextCity','search']]);
    }

    public function startCity(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get cities list successfully',
            'data' => RouteCity::select('name')->distinct()->get(),
        ]);
    }

    public function nextCity(string $firstCity): JsonResponse
    {
        $routes = RouteCity::select('route_id')->where('name', $firstCity)->distinct()->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Get cities list successfully',
            'data' => RouteCity::select('name')->whereIn('route_id', $routes)->where('name', '!=', $firstCity)->distinct()->get(),
        ]);
    }

    public function search(string $firstCity, string $lastCity): JsonResponse
    {
        $routes = RouteCity::select('route_id')->whereIn('name', [$firstCity, $lastCity])->distinct()->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Get cities list successfully',
            'data' => Route::whereIn('id', $routes)->get(),
        ]);
    }
}
