# 🧭 Columbus 

![Columbus OG Image](columbus_og.png)
Columbus is a lightweight composer package that allows you to quickly and easily generate sitemaps for your Laravel application. 

## Installation

You can install the package via composer:
    
```
composer require dgtlss/columbus
```

Once installed you can publish the config file and generate the middleware required for Columbus to work. The middleware tells Columbus which routes should be added to the sitemap, and which should be ignored.

```
php artisan columbus:init
```

Once you have initialised Columbus you will need to add the middleware to your `app/Http/Kernel.php` file. You can do this by adding the following line to the `$routeMiddleware` array:

```
'Mappable' => \App\Http\Middleware\Mappable::class,
```

Now that the middleware has been added to your laravel application you can generate your sitemap by running the following command:

```
php artisan columbus:map
```

This will generate a `sitemap.xml` file in your public directory. This will now be available by going to `yourdomain.test/sitemap`

## Usage

Now that we have successfully installed and configured Columbus you can start adding routes to your sitemap. You can do this by adding the `Mappable` middleware to the routes that you want to be included in the `sitemap.xml` file. When Columbus initialised it added a premade route group & middleware to your `routes/web.php` file. You can add routes to this group like so:

```
Route::middleware(['Mappable'])->group(function(){
    /* routes in this group will be added to the sitemap */
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});

```

### Notes

- By default Columbus will only add `GET` routes to your sitemap. If you want to change this you can do so inside of `config/columbus.php` 

- *Please note: Routes with variables currently do not work with Columbus. This is something that will be added in the future.*
