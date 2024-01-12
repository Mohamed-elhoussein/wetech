<?php

namespace App\Providers;

use App\Models\Identity;
use App\Observers\IdentityObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Identity::observe(IdentityObserver::class);

        Builder::macro('whereLike', function ($column, $value) {
            return $this->where($column, 'like', $value);
        });
    }
}
