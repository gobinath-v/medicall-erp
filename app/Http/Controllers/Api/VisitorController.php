<?php

namespace App\Http\Controllers\Api;

use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|string',
            'email' => 'required|email',
            'organization' => 'required|string',
            'designation' => 'nullable|string',
            'address' => 'nullable|string',
            'source' => 'nullable|string',
        ]);
        // [
        //     'mobile_number.unique' => 'Mobile number already exists. Please try with another mobile number',
        //     'email.unique' => 'Email already exists. Please try with another email',
        // ]
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'status' => 'fail',
                'errors' => $validator->errors(),
            ], 422);
        }



        $salutation = $request->salutation ?? 'Mr';
        $name = $request->name ?? '';
        $mobileNumber = $request->mobile_number ?? '';
        $email = $request->email ?? '';
        $organization = $request->organization ?? '';
        $designation = $request->designation ?? '';
        $address = $request->address ?? '';
        $source = $request->source ?? 'web';

        $requiredFieldsAreMissing = !$name || !$mobileNumber || !$email || !$organization;

        if ($requiredFieldsAreMissing) {
            return response()->json([
                'message' => 'Required fields are missing',
                'status' => 'fail',
            ], 422);
        }

        $username = str_replace(' ', '-', $name);
        $username = strtolower($username);
        $username = $username . '-' . rand(1000, 9999);

        $visitor = Visitor::where('mobile_number', $mobileNumber)
            ->orWhere('email', $email)
            ->first();

        if ($visitor) {
            $currentEvent = getCurrentEvent();
            $eventVisitor = $visitor->eventVisitors()->where('event_id', $currentEvent->id ?? 0)->first();

            if ($eventVisitor) {
                return response()->json([
                    'message' => 'Visitor already registered',
                    'status' => 'fail',
                ], 422);
            } else {
                $visitor->eventVisitors()->create([
                    'event_id' => $currentEvent->id ?? 0,
                ]);

                // TODO: Use job & queue to send welcome message
                sendWelcomeMessageThroughWhatsappBot($mobileNumber, 'visitor');
                return response()->json([
                    'message' => 'Visitor successfully registered',
                    'status' => 'success',
                ], 201);
            }
        }

        $visitorData = [
            'salutation' => $salutation,
            'username' => $username,
            'password' => Hash::make(config('app.default_user_password')),
            'name' => $name,
            'mobile_number' => $mobileNumber,
            'email' => $email,
            'organization' => $organization,
            'designation' => $designation,
            'registration_type' => $source,
        ];

        try {
            $visitor = Visitor::create($visitorData);

            if (!$visitor) {
                return response()->json([
                    'message' => 'Something went wrong',
                    'status' => 'error',
                ], 500);
            }

            // TODO: Use job & queue to send welcome message
            sendWelcomeMessageThroughWhatsappBot($mobileNumber, 'visitor');

            $visitor->address()->create([
                'address' => $address,
            ]);

            $currentEvent = getCurrentEvent();
            $visitor->eventVisitors()->create([
                'event_id' => $currentEvent->id ?? 0,
            ]);

            return response()->json([
                'message' => 'Visitor successfully registered',
                'status' => 'success',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
