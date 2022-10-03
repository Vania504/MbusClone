<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','list']]);
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
            'message' => 'Get settings list successfully',
            'data' => Setting::all(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(string $type): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Get status list successfully',
            'data' => Setting::where('type', '=', $type)->get(),
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
            'type' => 'required|string|in:common,contact,socials,text',
            'key' => 'required|string|unique:settings|max:191',
            'value' => 'required|string|max:191',
            'social_network' => 'nullable|string|in:Facebook,Twitter,Instagram,Viber,Telegram,Whatsapp'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $settings = Setting::create([
            'key' => $request->key,
            'value' => $request->value,
            'type' => $request->type,
            'social_network' => $request->social_network ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Create settings successfully',
            'data' => $settings,
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
        $settings = Setting::find($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Get status successfully',
            'data' => $settings,
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
            'type' => 'required|string|in:common,contact,socials,text',
            'key' => 'required|string|max:191|unique:settings,id,'.$id,
            'value' => 'required|string|max:191',
            'social_network' => 'nullable|string|in:Facebook,Twitter,Instagram,Viber,Telegram,Whatsapp'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'data' => $validator->errors(),
            ], 422);
        }
        $settings = Setting::findOrFail($id);
        if ($settings) {
            $settings->key = $request->key;
            $settings->value = $request->value;
            $settings->type = $request->type;
            $settings->social_network = $request->social_network ?? null;
            $settings->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Update settings successfully',
            'data' => $settings,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        Setting::find($id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete settings successfully',
            'data' => []
        ]);
    }
}
