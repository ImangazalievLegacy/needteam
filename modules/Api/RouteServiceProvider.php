<?php

namespace Modules\Api;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $prefix;

    protected $alias;

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        $config = $this->app->make('config');
        
        $this->prefix    = $config->get('api.prefix');
        $this->namespace = $config->get('api.namespace');
        $this->alias     = $config->get('api.alias');

        if ($this->isApiRequest()) {
            $this->registerMiddlewares();
        }

        parent::boot($router);
    }

    /**
     * Set the root controller namespace for the application.
     *
     * @return void
     */
    protected function setRootControllerNamespace()
    {

    }

    /**
     * @return void
     */
    public function register()
    {
        $aliases = [
            'api.formatter'  => 'Modules\Api\Formatter',
            'api.serializer' => 'Modules\Api\Serializer',
        ];

        foreach ($aliases as $key => $class) {
            $this->app->bind($key, $class);
        }
    }

    public function registerMiddlewares()
    {
        $kernel = $this->app->make('Illuminate\Contracts\Http\Kernel');
        $kernel->prependMiddleware('Modules\Api\Dispatcher');

        $router = $this->app->make('Illuminate\Contracts\Routing\Registrar');
        $router->middleware('api.auth', 'Modules\Api\Http\Middleware\Authenticate');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        if (!$this->app->routesAreCached()) {
            $router->group(['namespace' => $this->namespace, 'prefix' => $this->prefix, 'as' => $this->alias], function ($router) {
                require base_path(dirname($this->namespace).'/routes.php');
            });
        }
    }

    /**
     * @return bool
     */
    public function isApiRequest()
    {
        $request = $this->app->make('request');

        return starts_with(ltrim($request->path(), '/'), $this->prefix);
    }
}
