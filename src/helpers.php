<?php

use Ze\IAMAuth\Exceptions\IAMOauthException;

if (! function_exists('iamOk')) {
    // 用于给IAM返回自带id参数的json
    function iamOk($data = [], $message = IAMOauthException::MSG[IAMOauthException::SUCCESS], $code = IAMOauthException::SUCCESS)
    {
        // 随机ID，第三方应用系统每次响应返回此ID
        $bimRequestId = request()->input('bimRequestId', '');

        // todo 返回值要经过ASE加密

        return response()->json(array_merge([
            'message'      => $message,
            'resultCode'   => $code,
            'bimRequestId' => $bimRequestId,
        ], $data));
    }
}

if (! function_exists('iamThrow')) {
    // 自定义抛异常
    function iamThrow($code = -1, $message = '')
    {
        throw new IAMOauthException($message, $code);
    }
}

if (! function_exists('iamErr')) {
    // 自定义异常处理
    function iamErr(\Throwable $e)
    {
        return iamOk(
            [],
            $e->getMessage() ?: (IAMOauthException::MSG[$e->getMessage()] ?? 'Fail'),
            $e->getCode() ?: -1
        );
    }
}
