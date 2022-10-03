<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function listStatus(string $type): JsonResponse
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Get status list successfully',
            'data' => Status::where('type', '=', $type)->get(),
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
            'type' => 'required|string|in:message,request,ticket,callback',
            'name' => 'required|string|max:255',
            'is_default' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        if ($request->is_default === 1) {
            Status::where('type', '=', $request->type)->update(['is_default' => 0]);
        }
        $status = Status::create([
            'name' => $request->name,
            'type' => $request->type,
            'is_default' => $request->is_default ?? 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Create status successfully',
            'data' => $status,
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
        $status = Status::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get status successfully',
            'data' => $status,
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
            'type' => 'required|string|in:message,request,ticket,callback',
            'name' => 'required|string|max:255',
            'is_default' => 'required|boolean',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        if ($request->is_default === 1) {
            Status::where('type', '=', $request->type)->update(['is_default' => 0]);
        }

        $status = Status::findOrFail($id);
        if ($status) {
            $status->name = $request->name;
            $status->type = $request->type;
            $status->is_default = $request->is_default ?? 0;
            $status->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get message successfully',
            'data' => $status,
        ]);
    }
}
