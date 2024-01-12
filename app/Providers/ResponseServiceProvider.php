<?php

/*
*   Developer : takidddine soulaimane
*   Email     : takiddine.job@gmail.com
*   whatsapp  :  +212658829307
*/

// Place this file on the Providers folder of your project
namespace App\Providers;

use HttpCodes;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;


class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('data', function ($data = false, $message = '', $message_code = -1) use ($factory) {

            $executionEndTime = microtime(true);
            $seconds = $executionEndTime - LARAVEL_START;
            $seconds = number_format($seconds, 3) . ' seconds';

            $format = [
                'state'         => true,
                'code'          => HttpCodes::OK,
                'message'       => $message,
                'message_code'  => $message_code,
                'execution'     => $seconds
            ];

            $format['data'] = $data ?? [];



            if (env('FULL_SYSTEM_DEBUG') == 'true') {
                $debug = debug_request();
                $format['debug'] = $debug;
            }

            return $factory->make($format);
        });

        $factory->macro('message', function ($message, $message_code = -1) use ($factory) {

            $executionEndTime   = microtime(true);
            $seconds            = $executionEndTime - LARAVEL_START;
            $seconds            = number_format($seconds, 3) . ' seconds';

            $format = [
                'state'         => true,
                'code'          => HttpCodes::OK,
                'message_code'  => $message_code,
                'message'       => $message,
                'execution'     => $seconds
            ];


            $format['data'] = $data ?? [];

            if (env('FULL_SYSTEM_DEBUG') == 'true') {
                $debug = debug_request();
                $format['debug'] = $debug;
            }


            return $factory->make($format);
        });

        $factory->macro('notFound', function ($message, $message_code = -1) use ($factory) {

            $executionEndTime = microtime(true);
            $seconds = $executionEndTime - LARAVEL_START;
            $seconds = number_format($seconds, 3) . ' seconds';

            $format = [
                'state'        => true,
                'code'         => '900',
                'message'      => $message . ' not found',
                'message_code' => $message_code,
                'execution'    => $seconds
            ];



            if (env('FULL_SYSTEM_DEBUG') == 'true') {
                $debug = debug_request();
                $format['debug'] = $debug;
            }


            return $factory->make($format);
        });

        $factory->macro('success', function ($message = '', $data = false, $message_code = -1) use ($factory) {

            $executionEndTime = microtime(true);
            $seconds = $executionEndTime - LARAVEL_START;
            $seconds = number_format($seconds, 3) . ' seconds';

            $format = [
                'state'         => true,
                'code'          => HttpCodes::OK,
                'message'       => $message,
                'message_code'  => $message_code,
                'execution'     => $seconds
            ];
            $format['data'] = $data ?? [];

            if (env('FULL_SYSTEM_DEBUG') == 'true') {
                $debug = debug_request();
                $format['debug'] = $debug;
            }

            return $factory->make($format);
        });

        $factory->macro('error', function ($code, $message = '', $data = [], $message_code = -1) use ($factory) {

            $executionEndTime = microtime(true);
            $seconds = $executionEndTime - LARAVEL_START;
            $seconds = number_format($seconds, 3) . ' seconds';

            $false = [
                'state'        => false,
                'code'         => $code,
                'message'      => $message,
                'message_code' => $message_code,
                'execution'    => $seconds,
            ];

            if ($data) {
                $false['data'] = $data;
            }

            if (env('FULL_SYSTEM_DEBUG') == 'true') {
                $debug = debug_request();
                $false['debug'] = $debug;
            }

            return $factory->make($false);
        });
    }






    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
