<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ModelNotDefined;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class Handler extends ExceptionHandler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password',
    'password_confirmation',
  ];

  /**
   * Report or log an exception.
   *
   * @param Exception $exception
   * @return void
   *
   * @throws Exception
   */
  public function report(Exception $exception)
  {
    parent::report($exception);
  }

  /**
   * Render an exception into an HTTP response.
   *
   * @param  Request  $request
   * @param Exception $exception
   * @return Response
   *
   * @throws Exception
   */
  public function render($request, Exception $exception): Response
  {
    if ($exception instanceof AuthorizationException && $request->expectsJson()) {
      return response()->json(['error' => [
        'message' => 'You are not authorized to access this resource.'
      ]], 403);
    }

    if ($exception instanceof ModelNotFoundException && $request->expectsJson()) {
      return response()->json(['error' => [
        'message' => 'The resource you are trying to access is not found.'
      ]], 404);
    }

    if ($exception instanceof ModelNotDefined && $request->expectsJson()) {
      return response()->json(['error' => [
        'message' => 'No model defined'
      ]], 500);
    }

    return parent::render($request, $exception);
  }
}
