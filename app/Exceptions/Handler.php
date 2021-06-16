<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // see the exception: show in postman
        $this->reportable(function (Throwable $e) {

        });

        // * luk: added here
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return Route::respondWithRoute('api.fallback');
        });

         // * luk: added here
        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            return response()->json(["message" => $e->getMessage(), 403]);
        });
    }

    // // luk: added here old laravel less than 8
    // public function render($request, Throwable $exception)
    // {
    //     if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
    //         return Route::respondWithRoute('api.fallback');
    //         // and call in in api.php Route::fallback()->name("api.fallback")
    //     }

    //     return parent::render($request, $exception);
    // }
}
