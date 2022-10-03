<?php

namespace App\Http\Controllers;

use App\Models\RequestOrder;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;


class RequestOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get order request list successfully',
            'data' => RequestOrder::paginate(10),
        ]);
    }

    public function list(int $status): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get messages list successfully',
            'data' => RequestOrder::where(['status'=> $status])->get(),
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'count' => 'required|integer',
            'phone_number' => 'required|string|min:9|max:13',
            'departure' => 'required|string',
            'bus_id' => 'required|integer',
            'destination' => 'nullable|string',
            'route_id' => 'integer',
            'status' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $status = Status::where(['type' => 'request', 'is_default' => 1])->get();
        $status = isset($status[0]) ? $status[0]->id : 1;
        $requestOrder = RequestOrder::create([
            'name' => $request->name,
            'count' => $request->count,
            'phone_number' => $request->phone_number,
            'departure' => $request->departure,
            'bus_id' => $request->bus_id,
            'destination' => $request->destination ?? '',
            'route_id' => $request->route_id ?? 0,
            'status' => $request->status ?? $status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Request Order created successfully',
            'data' => $requestOrder,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $requestOrder = RequestOrder::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get request order successfully',
            'data' => $requestOrder,
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'integer',
            'departure' => 'string',
            'bus_id' => 'integer',
            'destination' => 'nullable|string',
            'route_id' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }

        $requestOrder = RequestOrder::findOrFail($id);
        if ($requestOrder) {
            $requestOrder->status = $request->status ?? $requestOrder->status;
            $requestOrder->departure = $request->departure ?? $requestOrder->departure;
            $requestOrder->bus_id = $request->bus_id ?? $requestOrder->bus_id;
            $requestOrder->destination = $request->destination ?? $requestOrder->destination;
            $requestOrder->route_id = $request->route_id ?? $requestOrder->route_id;
            $requestOrder->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update request order successfully',
            'data' => $requestOrder,
        ]);
    }
}
