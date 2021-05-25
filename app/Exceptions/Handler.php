<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;
use Exception;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            return $this->handleApiException($request, $exception);
        } else {
            $retval = parent::render($request, $exception);
        }

        return $retval;
    }

    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        return $request->expectsJson()
                    ? response()->json([
                        'success' => false,
                        'errors' => [
                            'type' => '',
                            'title' => 'Unauthenticated',
                            'status' => 401,
                            'detail' => 'Unauthenticated',
                            'instance' => ''
                        ]
                    ], 401)
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['errors']['title'] = 'Unauthenticated';
                $response['errors']['detail'] = 'Unauthenticated';
                break;
            case 403:
                $response['errors']['title'] = 'Forbidden';
                $response['errors']['detail'] = 'Forbidden';
                break;
            case 404:
                $response['errors']['title'] = 'Not Found';
                $response['errors']['detail'] = 'Not Found';
                break;
            case 405:
                $response['errors']['title'] = 'Method Not Allowed';
                $response['errors']['detail'] = 'Method Not Allowed';
                break;
            case 422:
                $response['errors']['title'] = $exception->original['message'];
                $response['errors']['detail'] = $exception->original['errors'];
                break;
            default:
                $response['errors']['title'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                $response['errors']['detail'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            $response['debug']['message'] = $exception->getMessage();
            $response['debug']['exception'] = get_class($exception);
            $response['debug']['file'] = $exception->getFile();
            $response['debug']['line'] = $exception->getLine();
            $response['debug']['code'] = $exception->getCode();
            $response['debug']['trace'] = collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all();
        }

        $response['errors']['status'] = $statusCode;
        $response['errors']['type'] = '';
        $response['errors']['instance'] = '';
        $response['success'] = false;

        return response()->json($response, $statusCode);
    }
}
