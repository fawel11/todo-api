<?php

namespace App\Exceptions;

use Error;
use ErrorException;
use Exception;
use HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // this handler manages all exceptions, we don't need to use try catch in any function

        $this->renderable(function (Exception $exception, $request) {
            if ($request->is('api/*')) {// this is to manage seamless api response
                if ($exception instanceof NotFoundHttpException) {
                    $statusCode = $exception->getStatusCode() ?: 404;
                    $message = $exception->getMessage() ? str_replace('/', '//', $exception->getMessage()) : 'Endpoint not found';
                    // $message = str_contains($exception->getMessage(), 'The route') ? 'Endpoint not found.' : (str_contains($exception->getMessage(), 'No query results') ? str_replace(']', '', last(explode('\\', $exception->getMessage()))) . ' not found.' : $exception->getMessage());
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => $message
                    ], $statusCode);
                } elseif ($exception instanceof ValidationException) {
                    $statusCode = $exception->status ?: 422;
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => 'The given data was invalid',
                        'errors' => $exception->errors()
                    ], $statusCode);
                } elseif ($exception instanceof AuthenticationException) {
                    return response()->json([
                        'responseCode' => 401,
                        'message' => $exception->getMessage()
                    ], 401);
                } elseif ($exception instanceof QueryException) {
                    return response()->json([
                        'responseCode' => 500,
                        'message' => $exception->getMessage(),
                    ], 500);
                } elseif ($exception instanceof AccessDeniedHttpException) {
                    $statusCode = $exception->getStatusCode() ?: 403;
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => $exception->getMessage(),
                    ], $statusCode);
                } elseif ($exception instanceof AuthorizationException) {
                    $statusCode = $exception->getStatusCode() ?: 403;
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => $exception->getMessage(),
                    ], $statusCode);
                } elseif ($exception instanceof BadRequestHttpException) {
                    $statusCode = $exception->getStatusCode() ?: 400;
                    $message = $exception->getMessage() ?: 'Invalid request';
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => $message
                    ], $statusCode);
                } elseif ($exception instanceof HttpException) {
                    $statusCode = $exception->getStatusCode() ?: 400;
                    $message = $exception->getMessage() ?: 'Invalid request';
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => $message
                    ], $statusCode);
                } elseif ($exception instanceof ErrorException) {
                    $statusCode = $exception->getCode() ?: 500;
                    $message = $exception->getMessage() ?: 'Failed to get service';
                    return response()->json([
                        'responseCode' => $statusCode,
                        'message' => $message,
                    ], $statusCode);
                } elseif ($exception instanceof Exception) {// all other exception
                    return response()->json([
                        'responseCode' => $exception->getCode() ?: 500,
                        'message' => $exception->getMessage()
                    ], $exception->getCode() ?: 500);
                } elseif ($exception instanceof Error) {// any php error that above exception unable to get
                    // Handle PHP fatal errors
                    return response()->json([
                        'responseCode' => 500,
                        'message' => $exception->getMessage() ?: 'An error occurred!'
                    ], 500);
                } else {// any php error that above exception unable to get
                    // Handle PHP fatal errors
                    return response()->json([
                        'responseCode' => 500,
                        'message' => $exception->getMessage() ?: 'An error occurred!'
                    ], 500);
                }
            }
        });


        $this->reportable(function (Throwable $e) {

        });
    }
}
