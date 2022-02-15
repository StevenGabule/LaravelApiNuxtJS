<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->users = $users;
    }

    public function verify(Request $request, User $user)
    {
        // check if the url is valid signed url
        if (!URL::hasValidSignature($request)) {
            return response()->json(['errors' => [
                'message' => 'Invalid verification link'
            ]], 422);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['errors' => [
                'message' => 'Email address already verified'
            ]], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));
        return response()->json(['message' => 'Email successfully verified']);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required'],
        ]);

        $user = $this->users->findWhereFirst('email', $request->email);
        if (!$user) {
            return response()->json(['errors' => [
                'email' => 'No user could be found with this email address'
            ]], 422);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['errors' => [
                'message' => 'Email address already verified'
            ]], 422);
        }

        $user->sendEmailVerificationNotification();
    }
}
