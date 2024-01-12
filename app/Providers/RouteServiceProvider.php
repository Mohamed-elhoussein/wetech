<?php

namespace App\Providers;

use App\Http\Middleware\Store;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();


        $this->routes(function () {
            $this->mapUsersApiRoute();

            $this->mapApiRoute();

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth'])
                ->namespace($this->namespace)
                ->as('main.')
                ->prefix('maintenance-store')
                ->group(base_path('routes/maintenance.php'));

            // Route::namespace($this->namespace)
            //     ->as('no-main.')
            //     ->prefix('no-maintenance-store')
            //     ->group(base_path('routes/main-noauth.php'));

            Route::middleware(['web', Store::class])
                ->namespace($this->namespace)
                ->as('store.')
                ->group(base_path('routes/store.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    public function mapUsersApiRoute( $routes = [])
    {
                Route::prefix('api/users')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api/user.php'));
    }
    public function mapApiRoute( $routes = [])
    {
                Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
    }
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
