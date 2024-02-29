<?php

namespace App\Exceptions;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {


        if ($request->expectsJson()) {
            return $this->handleApiException($request, $e);

        }

        return parent::render($request, $e);
    }

    private function handleApiException($request, Throwable $e): JsonResponse
    {


        $exception = $this->prepareException($e);


        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }


        switch ($statusCode) {
            case 401:
                $responseMessage = 'Unauthorized';
                break;
            case 403:
                $responseMessage = 'Forbidden';
                break;
            case 404:
                $responseMessage = $exception->getMessage() ?: 'Not Found';
                break;
            case 405:
                $responseMessage = 'Method Not Allowed';
                break;
            case 422:
                $responseMessage = $exception->original['message'];
                // $response['errors'] = $exception->original['errors'];
                break;
            default:
                $responseMessage = $exception->getMessage(); // ($statusCode === 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            // dd($exception);
            //  $response['trace'] = $exception->getTrace();
            // $response['code'] = $exception->getCode();
        }


        return response()->json(
            [
                'error' => true,
                'message' => $responseMessage,
                'line' => $e->getLine(),
                'file' => basename($e->getFile())
            ]
            , $statusCode);


    }
}
