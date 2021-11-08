<?php

use Ze\IAMAuth\Http\Controllers\Admin\AuthController;
use Ze\IAMAuth\Http\Controllers\Admin\UserController;
use Ze\IAMAuth\Http\Controllers\Admin\RoleController;
use Ze\IAMAuth\Http\Controllers\Admin\PermissionController;

// 登入
Route::get('auth/login', [AuthController::class, 'login'])->name('admin.login');
// 登出
Route::get('auth/logout', [AuthController::class, 'logout'])->name('admin.logout');
// 授权
Route::get('oauth/authorize', [AuthController::class, 'authorize']);
// 授权回调
Route::get('oauth/callback', [AuthController::class, 'callback']);
// 账号绑定
Route::get('oauth/bind-account', [AuthController::class, 'bindAccount']);
Route::post('oauth/bind-account', [AuthController::class, 'bindAccount']);

// todo 管理员管理
Route::resource('auth/users', UserController::class)->names('admin.auth.users');
// 角色管理
Route::resource('auth/roles', RoleController::class)->names('admin.auth.roles');
// 权限管理
Route::resource('auth/permissions', PermissionController::class)->names('admin.auth.permissions');
