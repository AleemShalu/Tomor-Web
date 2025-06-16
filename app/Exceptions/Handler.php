<?php

namespace App\Exceptions;

use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'code' => 'METHOD_NOT_ALLOWED | HTTP_NOT_FOUND',
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => __('locale.api.errors.not_found_model_message', [], $request->header('Accept-Language', config('app.locale'))),
                    'code' => 'NOT_FOUND_EXCEPTION'
                ], Response::HTTP_NOT_FOUND);
            }
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => __('locale.api.errors.user_unauthenticated', [], $request->header('Accept-Language', config('app.locale'))),
                    'code' => 'AUTHENTICATION_EXCEPTION'
                ], Response::HTTP_UNAUTHORIZED);
            }
        });

        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                FilamentExceptions::report($e);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
