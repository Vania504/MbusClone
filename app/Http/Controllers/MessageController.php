<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Status;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
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
            'message' => 'Get messages list successfully',
            'data' => Message::paginate(10),
        ]);
    }

    public function list(int $status): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get messages list successfully',
            'data' => Message::where(['status'=> $status])->get(),
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
            'email' => 'required|string|email|max:255',
            'phone_number' => 'required|string|min:9|max:13',
            'message' => 'required|string',
            'status' => 'integer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $status = Status::where(['type' => 'message', 'is_default' => 1])->get();
        $status = isset($status[0]) ? $status[0]->id : 1;
        $message = Message::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'status' => $request->status ?? $status,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Message created successfully',
            'data' => $message,
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
        $message = Message::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get message successfully',
            'data' => $message,
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
            'name' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $message = Message::findOrFail($id);
        if ($message) {
            $message->status = $request->status ?? $message->status;
            $message->name = $request->name ?? $message->name;
            $message->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get message successfully',
            'data' => $message,
        ]);
    }
}
