<?php

namespace Ze\IAMAuth;

use Illuminate\Support\ServiceProvider;
use Ze\IAMAuth\Services\IAMPassport;
use Ze\IAMAuth\Services\OAuthAuthentication;

class IAMAuthServiceProvider extends ServiceProvider
{
    public function boot(IAMAuth $extension)
    {
        if (! IAMAuth::boot()) {
            return;
        }

        // 数据库注册
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // 视图注册
        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'iam-auth');
        }

        // 配置文件注册
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'iam-auth');
        }

        // 路由注册
        $this->app->booted(function () {
            IAMAuth::routes(__DIR__ . '/../routes/iam.php');
        });

    }

    public function register()
    {
        // 服务注册
        $this->app->singleton('iam-oauth', function ($app) {
            return new OAuthAuthentication($app['config']['iam']['services']['iam_oauth']);
        });

        // 服务注册
        $this->app->singleton('iam-passport', function ($app) {
            return new IAMPassport();
        });


        $this->expandConfig();
    }

    // 扩展config配置
    private function expandConfig()
    {
        config(['admin.auth.excepts' => array_merge(config('admin.auth.excepts'), [
            'oauth/authorize',
            'oauth/callback',
            'oauth/bind-account'
        ])]);
        config(['admin.skin' => 'skin-green']);
        config(['admin.layout' => ['sidebar-mini']]);
    }
}
