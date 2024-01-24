<?php

namespace App\Http\Controllers\Api;

use App\Models\Exhibitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|digits:10',
            'password' => 'required',
            'is_otp_login' => 'required|in:yes,no'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile number or password is incorrect or missing',
                'errors' => $validator->errors()
            ]);
        }

        $credentials = $request->only('mobile_number', 'password');

        if ($request->is_otp_login == 'yes') {
            $exhibitor = Exhibitor::where('mobile_number', $request->mobile_number)
                ->where('otp', $request->password)
                ->where('otp_expired_at', '>', now())
                ->first();

            if (!$exhibitor) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OTP/Mobile number is incorrect or expired'
                ]);
            }
            // let authenticate the exhibitor
            $exhibitor->otp = null;
            $exhibitor->otp_expired_at = null;
            $exhibitor->save();
            auth()->guard('exhibitor')->login($exhibitor);
        } else {
            if (!auth()->guard('exhibitor')->attempt($credentials)) {
                // Check if user exists in visitor table
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mobile number or password is incorrect'
                ]);
            }
        }

        $user = auth()->guard('exhibitor')->user();
        $token = $user->createToken('exhibitor_auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully',
            'token_type' => 'bearer',
            'token' => $token,
            'data' => [
                'name' => $user->name,
                'username' => $user->username,
                'mobile_number' => $user->mobile_number,
                'email' => $user->email,
                'exhibitor_id' => $user->id,
                'category' => $user->category->name ?? '',
            ]
        ]);
    }

    public function otpRequest(Request $request)
    {
        $mobileNumber = $request->mobile_number ?? '';
        if (empty($mobileNumber)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile number is missing'
            ]);
        }

        $exhibitor = Exhibitor::where('mobile_number', $mobileNumber)->first();
        if (!$exhibitor) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mobile number is not registered. Please give a registered mobile number'
            ]);
        }

        $otp = rand(1000, 9999);
        $sendOtp = sendLoginOtp($mobileNumber, $otp);

        if ($sendOtp['status'] == 'success') {

            $exhibitor->otp = $otp;
            $exhibitor->otp_expired_at = now()->addMinutes(10);
            $exhibitor->save();

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully, It will expire in 10 minutes.',
                'otp' => $otp,
                'otp_expired_at' => $exhibitor->otp_expired_at,
                'otp_expired_at_formatted' => $exhibitor->otp_expired_at->format('d-m-Y h:i:s A'),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong, please try again later',
            'error_message' => $sendOtp['message'] ?? ''
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    }
}
