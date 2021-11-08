<?php

use Illuminate\Routing\Router;
use Ze\IAMAuth\Http\Controllers\Api\UserController;
use Ze\IAMAuth\Controllers\Api\OrganizationController;

// 提供给IAM-TIM的对外接口，接口path严格按照文档命名
Route::group([
    'prefix'     => config('iam.apis.tim_api.prefix'),
    'middleware' => array_merge(['tim'], config('iam.apis.tim_api.middleware', []))
], function (Router $router) {
    // 账户相关
    $router->group(['prefix' => 'user'], function (Router $router) {
        // 对象属性字段查询
        $router->post('SchemaService', [UserController::class, 'schema']);
        // 账号创建
        $router->post('UserCreateService', [UserController::class, 'userCreate']);
        // 账号修改
        $router->post('UserUpdateService', [UserController::class, 'userUpdate']);
        // 账号删除
        $router->post('UserDeleteService', [UserController::class, 'userDelete']);
        // 批量查询账号ID
        $router->post('QueryAllUserIdsService', [UserController::class, 'queryAllUserIds']);
        // 根据账号ID查询账号详细内容
        $router->post('QueryUserByIdService', [UserController::class, 'queryUserById']);
    });
});
