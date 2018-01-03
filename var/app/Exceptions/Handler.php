<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (strpos($request->getUri(), '/api/') == false) {
            return parent::render($request, $exception);
        } else {
            if ($exception instanceof ModelNotFoundException){
                return response()->json([
                    'data' => ['message' => 'Record not found']
                ], 404);
            } else if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'data' => ['message' => 'Page not found']
                ], 404);
            } else if ($exception instanceof PostTooLargeException) {
                return response()->json([
                    'data' => ['message' => 'File to large. Max file size is '.ini_get('post_max_size')]
                ], 400);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'data' => ['message' => 'Method is not allowed']
                ], 405);
            } else {
                return parent::render($request, $exception);
            }

        }

    }
}
