<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    public string $api_root_directory = 'routes/Api/';
    public string $api_controllers_namespace = 'App\Http\Controllers\Api';
    public array $route_list = [
        User::class => 'user.php',
        Category::class => 'category.php',
        Article::class => 'article.php',
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(360)->by($request->user()?->id ?: $request->ip());
        });

        Route::bind('article', function ($value) {
            return Article::query()
                ->where('id', $value)
                ->orWhere('slug', $value)
                ->firstOrFail();
        });

        $this->routes(function () {
            foreach ($this->route_list as $route_file) {
                Route::middleware('api')
                    ->prefix('api')
                    ->namespace($this->api_controllers_namespace)
                    ->group(base_path($this->api_root_directory . $route_file));
            }

            Route::middleware('api')
                ->prefix('api/auth')
                ->namespace('App\Http\Controllers')
                ->group(base_path('routes/auth.php'));
        });
    }
}
