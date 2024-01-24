<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Visitor;
use App\Models\Exhibitor;
use Illuminate\Http\Request;
use App\Models\EventExhibitor;
use App\Models\EventVisitor;
use App\Models\UserLoginActivity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;

class LoginController extends FortifyAuthenticatedSessionController
{

    protected function authenticate(Request $request)
    {
        $currentEventId = getCurrentEvent();
        // dd($currentEventId->id);

        $credentials = $request->only('email', 'password');
        $emailOrMobile = $request->input('email');
        $otp = $request->input('otp');
        if (Auth::guard('web')->check()) {
            session(['url.intended' => '/dashboard']);
        } elseif (Auth::guard('exhibitor')->check()) {
            session(['url.intended' => '/event_information?eventId=' . $currentEventId->id]);
        } elseif (Auth::guard('visitor')->check()) {
            session(['url.intended' => '/event_information?eventId=' . $currentEventId->id]);
        }
        // Attempt to authenticate using email
        // if (Auth::guard('exhibitor')->attempt(['email' => $emailOrMobile, 'password' => $credentials['password']])) {
        //     return redirect()->intended('/dashboard');
        // } elseif (Auth::guard('web')->attempt(['email' => $emailOrMobile, 'password' => $credentials['password']])) {
        //     return redirect()->intended('/dashboard');
        // } elseif (Auth::guard('visitor')->attempt(['email' => $emailOrMobile, 'password' => $credentials['password']])) {
        //     return redirect()->intended('/dashboard');
        // }

        $validatedVisitorOtp = Visitor::where('mobile_number', $emailOrMobile)->where('otp', $otp)->where('otp_expired_at', '>', now())->first();
        $exhibitorRequestedOtp = Exhibitor::where('mobile_number', $emailOrMobile)->where('otp', $otp)->where('otp_expired_at', '>', now())->first();
        $userRequestedOtp = User::where('mobile_number', $emailOrMobile)->where('otp', $otp)->where('otp_expired_at', '>', now())->first();

        // Attempt to authenticate using mobile number
        if (Auth::guard('exhibitor')->attempt(['mobile_number' => $emailOrMobile, 'password' => $credentials['password']]) || $exhibitorRequestedOtp) {
            if ($exhibitorRequestedOtp) {
                $exhibitorRequestedOtp->otp = null;
                $exhibitorRequestedOtp->otp_expired_at = null;
                $exhibitorRequestedOtp->save();

                Auth::guard('exhibitor')->login($exhibitorRequestedOtp);
            }

            UserLoginActivity::create([
                'userable_id' => auth()->guard('exhibitor')->user()->id,
                'userable_type' => Exhibitor::class,
                'last_login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $isExhibitorRegistered = EventExhibitor::where('event_id', $currentEventId->id)
                ->where('exhibitor_id', getAuthData()->id)
                ->exists();
            if ($isExhibitorRegistered) {
                return redirect()->intended('/event_information?eventId=' . $currentEventId->id);
            } else {
                return redirect()->intended('/dashboard');
            }
        } elseif (Auth::guard('web')->attempt(['mobile_number' => $emailOrMobile, 'password' => $credentials['password']]) || $userRequestedOtp) {
            if ($userRequestedOtp) {
                $userRequestedOtp->otp = null;
                $userRequestedOtp->otp_expired_at = null;
                $userRequestedOtp->save();

                Auth::guard('web')->login($userRequestedOtp);
            }

            UserLoginActivity::create([
                'userable_id' => auth()->guard('web')->user()->id,
                'userable_type' => User::class,
                'last_login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended('/dashboard');
        } elseif (Auth::guard('visitor')->attempt(['mobile_number' => $emailOrMobile, 'password' => $credentials['password']]) || $validatedVisitorOtp) {

            if ($validatedVisitorOtp) {
                $validatedVisitorOtp->otp = null;
                $validatedVisitorOtp->otp_expired_at = null;
                $validatedVisitorOtp->save();

                Auth::guard('visitor')->login($validatedVisitorOtp);
            }

            UserLoginActivity::create([
                'userable_id' => auth()->guard('visitor')->user()->id,
                'userable_type' => Visitor::class,
                'last_login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $isVisitorRegistered = EventVisitor::where('event_id', $currentEventId->id)
                ->where('visitor_id', getAuthData()->id)
                ->exists();
            if ($isVisitorRegistered) {
                return redirect()->intended('/event_information?eventId=' . $currentEventId->id);
            } else {
                return redirect()->intended('/dashboard');
            }
        }
        return redirect()->route('login')->with('mobile_no', $emailOrMobile)->with('error', 'Invalid credentials');
    }

    public function requestOtp(Request $request)
    {
        $mobileNo = $request->input('mobile_number');
        $visitor = Visitor::where('mobile_number', $mobileNo)->first();
        $exhibitor = Exhibitor::where('mobile_number', $mobileNo)->first();
        $user = User::where('mobile_number', $mobileNo)->first();

        if (!$visitor && !$exhibitor && !$user) {
            return redirect()->back()
                ->with('mobile_no', $mobileNo)
                ->with('requested_otp', 'yes')
                ->with('error', 'Mobile number not found');
        }

        $otp = rand(100000, 999999);

        $sendOtp = sendLoginOtp($mobileNo, $otp);


        if ($sendOtp['status'] === 'success') {

            if ($visitor) {
                $visitor->otp = $otp;
                $visitor->otp_expired_at = now()->addMinutes(10);
                $visitor->save();
            }

            if ($exhibitor) {
                $exhibitor->otp = $otp;
                $exhibitor->otp_expired_at = now()->addMinutes(10);
                $exhibitor->save();
            }

            if ($user) {
                $user->otp = $otp;
                $user->otp_expired_at = now()->addMinutes(10);
                $user->save();
            }

            if ($visitor) {
                return redirect()->back()
                    ->with('mobile_no', $mobileNo)
                    ->with('requested_otp', 'yes')
                    ->with('success', 'OTP sent successfully, It will expire in 10 minutes.');
            }
        }
        return redirect()->back()
            ->with('mobile_no', $mobileNo)
            ->with('requested_otp', 'yes')
            ->with('error', 'Something went wrong, try again later');
    }

    protected function authenticateAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $verifiedUser = false;
        // Attempt to authenticate using  mobile number
        if (Auth::guard('web')->attempt(['mobile_number' => $credentials['email'], 'password' => $credentials['password']])) {
            $verifiedUser = true;
        }

        // Attempt to authenticate using email  or mobile number
        if (Auth::guard('web')->attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $verifiedUser = true;
        }
        if ($verifiedUser) {
            UserLoginActivity::create([
                'userable_id' => auth()->guard('web')->user()->id,
                'userable_type' => User::class,
                'last_login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return redirect()->intended('/dashboard');
        }
        return redirect()->route('admin-login-form')->with('mobile_no', $credentials['email'])->with('error', 'Invalid credentials');
    }

    public function logout(Request $request)
    {
        Log::info('Logout');
        if (Auth::guard('web')->check()) {
            $lastUserLoginActivity = UserLoginActivity::where('userable_id', auth()->guard('web')->user()->id)
                ->where('userable_type', 'App\Models\User')
                ->where('last_logout_at', null)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastUserLoginActivity) {
                $lastUserLoginActivity->last_logout_at = now();
                $lastUserLoginActivity->save();
            }
        } elseif (Auth::guard('exhibitor')->check()) {
            $lastUserLoginActivity = UserLoginActivity::where('userable_id', auth()->guard('exhibitor')->user()->id)
                ->where('userable_type', 'App\Models\Exhibitor')
                ->where('last_logout_at', null)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastUserLoginActivity) {
                $lastUserLoginActivity->last_logout_at = now();
                $lastUserLoginActivity->save();
            }
        } elseif (Auth::guard('visitor')->check()) {
            $lastUserLoginActivity = UserLoginActivity::where('userable_id', auth()->guard('visitor')->user()->id)
                ->where('userable_type', 'App\Models\Visitor')
                ->where('last_logout_at', null)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastUserLoginActivity) {
                $lastUserLoginActivity->last_logout_at = now();
                $lastUserLoginActivity->save();
            }
        }

        Auth::guard('web')->logout();
        Auth::guard('exhibitor')->logout();
        Auth::guard('visitor')->logout();

        $request->session()->flush();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
