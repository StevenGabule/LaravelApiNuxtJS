<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

  use ResetsPasswords;

  protected function sendResetResponse(Request $request, $response)
  {
    return response()->json(['status' => trans($response), 200]);
  }

  protected function sendResetFailedResponse(Request $request, $response)
  {
    return response()->json(['email' => trans($response)], 200);
  }
}
