<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\SendMail;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetRequestController extends Controller
{

    public function sendPasswordResetEmail(Request $request): JsonResponse
    {
        // If email does not exist
        if(!$this->validEmail($request->email)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Email does not exist',
                'data'=>[]
            ], Response::HTTP_NOT_FOUND);
        } else {
            // If email exists
            $this->sendMail($request->email);
            return response()->json([
                'status' => 'success',
                'message' => 'Check your inbox, we have sent a link to reset email.',
                'data'=>[]
            ], Response::HTTP_OK);
        }
    }

    public function sendMail($email): void
    {
        $token = $this->generateToken($email);
        Mail::to($email)->send(new SendMail($token));
    }
    public function validEmail($email): bool
    {
        return !!User::where('email', $email)->first();
    }

    public function generateToken($email){
        $isOtherToken = DB::table('password_resets')->where('email', $email)->first();
        if($isOtherToken) {
            return $isOtherToken->token;
        }
        $token = Str::random(80);
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email): void
    {
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }

}
