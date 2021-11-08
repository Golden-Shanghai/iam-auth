<?php


namespace Ze\IAMAuth;

use Illuminate\Support\ServiceProvider;

class TIMApiProvider extends ServiceProvider
{
    // 路由中间件
    protected $routeMiddleware = [
        'tim.auth' => Http\Middleware\TIMApiAuth::class
    ];

    // 路由分组
    protected $middlewareGroups = [
        'tim' => [
            'tim.auth',
        ]
    ];

    public function register()
    {
        // 中间件注册
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // 路由组注册
        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    public function boot()
    {
        // 加载路由
        $this->loadRoutesFrom(__DIR__ . '/../routes/tim.php');
    }
}
