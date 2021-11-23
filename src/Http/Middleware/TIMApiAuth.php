<?php


namespace Ze\IAMAuth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ze\IAMAuth\Exceptions\IAMOauthException;

class TIMApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // 必传公共参数验证
            $params = $request->validate([
                'bimRequestId'  => 'required|string',
                'bimRemoteUser' => 'required|string',
                'bimRemotePwd'  => 'required|string',
            ]);

            if ($params['bimRemoteUser'] != config('iam.apis.tim_api.remote_user') ||
                $params['bimRemotePwd'] != config('iam.apis.tim_api.remote_pwd')) {

                iamThrow(IAMOauthException::LOGIN_ERROR, '账号密码不正确');
            }

            // todo 这里验签

            return $next($request);
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }

    }
}
