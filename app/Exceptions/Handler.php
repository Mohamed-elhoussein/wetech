<?php

namespace App\Exceptions;

use App;
use App\Bots\ErrorBots;
use Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Log;
use Request;
use Stripe\Error\Authentication as StripeAuthentication;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function known()
    {
        return [
            'Symfony\Component\HttpKernel\Exception\NotFoundHttpException',
            'Illuminate\Database\Eloquent\ModelNotFoundException',
            'Illuminate\Database\QueryException',
            'Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException',
            'Illuminate\Auth\AuthenticationException',
            'Illuminate\Routing\Route',
            'ErrorException',
            'Illuminate\Validation\ValidationException',
            'Symfony\Component\ErrorHandler\Error\FatalError',
            // stripe errors
            'Stripe\Error\InvalidRequest',
            'Stripe\Error\Card',
            'Stripe\Error\Authentication',
            'Illuminate\Http\Exceptions\HttpResponseException'
        ];
    }

    public function render($request, Throwable $exception)
    {
        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }

        // dd(get_class($exception));

        $known = $this->known();
        $new = get_class($exception);
        $save = !in_array($new, $known);

        if ($save) {
            new ErrorBots('exeption :' . $new);
        }

        $url = Request::url();

        $error = [
            'Link: ' => $url,
            'state' => false,
        ];

        if ($exception instanceof InvalidRequest) {
            $error['code'] = '306';
            $error['message'] = 'stripe payment error : ' . $exception->getMessage();
        } elseif ($exception instanceof Card) {
            $error['code'] = '306';
            $error['message'] = 'stripe payment error : ' . $exception->getMessage();
        } elseif ($exception instanceof StripeAuthentication) {
            $error['code'] = '306';
            $error['message'] = ' Api keys error : ' . $exception->getMessage();
        } elseif ($exception instanceof ModelNotFoundException) {
            $model = explode('\\', $exception->getModel());
            $modelPhrase = ucwords(implode(' ', preg_split('/(?=[A-Z])/', end($model))));
            $error['code'] = '900';
            $error['message'] = App::make($exception->getModel())->modelNotFoundMessage ?? $modelPhrase . ' not found';
        } elseif ($exception instanceof ValidationException) {
            $error['code'] = '403';
            $error['message'] = $exception->validator->errors()->first();
        } elseif ($exception instanceof AuthenticationException) {
            $error['code'] = '401';
            $error['message'] = trans('api.failed.unauthenticated');
        } elseif (($exception instanceof NotFoundHttpException) or ($exception instanceof RouteNotFoundException)) {
            $error['code'] = '404';
            $error['message'] = trans('api.failed.route_not_found');
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            $error['code'] = '405';
            $error['message'] = $request->method() . ' method not allowed ';
        } elseif ($exception instanceof QueryException) {
            $error['code'] = '406';
            $error['message'] = trans('api.database.error');
        } else if (!$exception instanceof HttpResponseException) {
            $error['code'] = '500';
            $error['message'] = 'server error : ' . $exception->getMessage();
            $error['file'] = $exception->getFile();
            $error['line'] = $exception->getLine();
            $error['is_auth'] = Auth::check();
            $error['user_id'] = optional(Auth::user())->id;
            $error['mobile'] = optional(Auth::user())->mobile;
            $error['device'] = optional(Auth::user())->device_type;
            $error['catched_at'] = now()->todatetimestring();
        }

        if ('true' == env('FULL_SYSTEM_DEBUG')) {
            Log::info('-----------FULL ERROR LOG START ------------- ');
            $debug = debug_request();
            foreach ($error as $key => $val) {
                Log::info($key . ' : ' . $val);
            }
            Log::info('ip:' . $debug['ip']);
            Log::info('route:' . $debug['route']);
            Log::info('parametres : ------------');
            foreach ($debug['parametres'] as $key => $val) {
                Log::info('param ' . $key . ' : ' . $val);
            }
            Log::info('headers : ------------');
            foreach ($debug['headers'] as $key => $val) {
                if (in_array($key, ['authorization', 'cookie'])) {
                    Log::info('header ' . $key . ' : ' . substr($val[0], 0, 100) . '[...]');
                } else {
                    Log::info('header ' . $key . ' : ' . $val[0]);
                }
            }
            Log::info('-----------FULL ERROR LOG END ------------- ');
            $error['debug'] = $debug;
        }

        if ('500' == @$error['code']) {
            new ErrorBots(json_encode($error));
        }

        $executionEndTime = microtime(true);
        $seconds = $executionEndTime - LARAVEL_START;
        $seconds = number_format($seconds, 3) . ' seconds';
        $error['execution'] = $seconds;

        // End code

        if ($this->isHttpException($exception) && $request->ajax() && 404 == $exception->getStatusCode()) {
            return response()->error($error);
        }

        if ($this->isHttpException($exception)) {
            if (404 == $exception->getStatusCode()) {
                return response()->view('errors.404', [], 404);
            }

            if (403 == $exception->getStatusCode()) {
                return response()->view('errors.404', [], 404);
            }

            if (500 == $exception->getStatusCode()) {
                return response()->view('errors.500', [], 500);
            }
        }

        return parent::render($request, $exception);
    }



    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }
}
