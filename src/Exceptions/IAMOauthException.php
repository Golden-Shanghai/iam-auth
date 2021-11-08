<?php


namespace Ze\IAMAuth\Exceptions;


class IAMOauthException extends \Exception
{
    /**
     * 0 正确
     * 1xxx 自定义错误
     * xx0x 中间件级别错误
     * xx1x 程序级别错误
     */
    const
        SUCCESS = 0,
        LOGIN_ERROR = 1000,
        SCHEMA_ERROR = 1011,
        USER_CREATE_ERROR = 1012,
        USER_UPDATE_ERROR = 1013,
        USER_DELETE_ERROR = 1014,
        GET_ALL_USER_ERROR = 1015,
        GET_USER_ERROR = 1016;

    const MSG = [
        self::SUCCESS            => '成功',
        self::LOGIN_ERROR        => '登录验证失败',
        self::SCHEMA_ERROR       => '查询字段属性失败',
        self::USER_CREATE_ERROR  => '账号创建失败',
        self::USER_UPDATE_ERROR  => '账号修改失败',
        self::USER_DELETE_ERROR  => '账号删除失败',
        self::GET_ALL_USER_ERROR => '查询账号id失败',
        self::GET_USER_ERROR     => '查询账号详情失败'
    ];
}
