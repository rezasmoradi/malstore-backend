<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Business;
use App\Models\Category;
use App\Models\Comment;
use App\Models\CustomerGroup;
use App\Models\Discount;
use App\Models\Message;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Shipper;
use App\Models\Shopper;
use App\Models\Slide;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->registerModelBindings();
    }

    public function mapApiRoutes()
    {
        $files = File::files(base_path('routes/api'));
        foreach ($files as $file) {
            $path = $file->getPath() . DIRECTORY_SEPARATOR . $file->getFilename();
            $name = $file->getFilenameWithoutExtension();

            Route::prefix("api/{$name}")
                ->middleware('api')
                ->namespace($this->namespace)
                ->group($path);
        }
    }

    public function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    public function registerModelBindings()
    {
        Route::model('user', User::class);
        Route::model('address', Address::class);
        Route::model('category', Category::class);
        Route::model('product', Product::class);
        Route::model('order_detail', OrderDetail::class);
        Route::model('discount', Discount::class);
        Route::model('comment', Comment::class);
        Route::model('message', Message::class);
        Route::model('supplier', Supplier::class);
        Route::model('shopper', Shopper::class);
        Route::model('shipper', Shipper::class);
        Route::model('banner', Banner::class);
        Route::model('slide', Slide::class);
        Route::model('business', Business::class);
        Route::model('customer_group', CustomerGroup::class);
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user() ?->id ?: $request->ip());
        });
    }
}
