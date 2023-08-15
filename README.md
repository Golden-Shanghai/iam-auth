# 快捷登录SDK

> PHP >= 7.00    
> laravel-admin >= 1.6

---
主要包含三部分

- 自动处理OAuth授权登录及账号绑定
- 提供基于laravel-admin的账号管理界面接入
- 包含账号信息同步接口

---

### 若存在，先移除旧版本oauth包

```shell
composer remove cann/laravel-admin-oauth-golden
```

### 引入本扩展

```shell
composer require ze/iam-auth
```

### 发布配置

```shell
php artisan vendor:publish --provider=\Ze\IAMAuth\IAMAuthServiceProvider
```

### 若admin_users_third_pf_bind表不存在，则生成数据表

```shell
php artisan migrate
```

### 修改iam相关配置项config/iam.php

> 更多配置参考vendor/ze/iam-auth/config/iam.php

```php
<?php

return [

    // 当第三方登录未匹配到本地账号时，是否允许自动创建本地账号
    'allowed_auto_create_account' => true,

    // OAuth 秘钥
    'services'                    => [

        'iam_oauth' => [
            // OAuth-client_id
            'client_id'     => 'test_id',
            // OAuth-secret
            'client_secret' => 'test_secret',
            // oauth目标域名
            'domain'        => 'http://iam-test.com',
            // 目标系统代码
            'system_code'  => 'test_code',
            // 目标系统安全密钥
            'secret_key'    => 'test_secret_key'
        ],

    ],

    // 提供给外部的api相关配置
    'apis'                        => [

        'tim_api' => [
            // 路由组前缀
            'prefix'     => 'tim-api',
            // 自定义路由组中间件
            'middleware' => [],
            // 签名key
            'key'        => 'this is ase key',
            // 加密类型
            'sign_type'  => 'AES-128-CBC',
            // 授权账号
            'remote_user'   =>  'TIMadmin',
            // 授权密码
            'remote_pwd'    =>  'TIMadmin',
        ]

    ]
];
```

### 修改laravel-admin配置文件admin.php, 修改重定向路由，及路由过滤项

```php
// ...
'auth' = [
    // ... 
    'redirect_to' => 'auth/login',
    'excepts' => [
            'auth/login',
            'auth/logout',
            // ...
        ],
];
```

### 同步本地与远端账号关联关系

```shell
php artisan iam:sync-user
```






