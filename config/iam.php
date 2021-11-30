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
            'secret_key'    => 'test_secret_key',
        ],

    ],

    // 提供给外部的api相关配置
    'apis'                        => [

        'tim_api' => [
            // 路由组前缀
            'prefix'      => 'tim-api',
            // 自定义路由组中间件
            'middleware'  => [],
            // 签名key
            'key'         => 'this is ase key',
            // 加密类型
            'sign_type'   => 'AES-128-CBC',
            // 授权账号
            'remote_user' => 'TIMadmin',
            // 授权密码
            'remote_pwd'  => 'TIMadmin',
        ]

    ]
];
