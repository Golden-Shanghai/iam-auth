<?php


namespace Ze\IAMAuth\Services;

use Ze\IAMAuth\Exceptions\IAMOauthException;

class OAuthAuthentication
{
    const
        //请求用户授权接口
        AUTHORIZE_URL = '/idp/oauth2/authorize',
        //获取授权Token接口
        GET_TOKEN_URL = '/idp/oauth2/getToken',
        //刷新授权接口
        REFRESH_TOKEN_URL = '/idp/oauth2/refreshToken',
        //查询授权接口
        GET_TOKEN_INFO_URL = '/idp/oauth2/getTokenInfo',
        //回收授权接口
        REVOKE_TOKEN_URL = '/idp/oauth2/revokeToken',
        //检查授权是否有效接口
        CHECK_TOKEN_VALID_URL = '/idp/oauth2/checkTokenValid',
        //获取用户信息接口
        GET_USERINFO_URL = '/idp/oauth2/getUserInfo',
        //登出接口
        LOGOUT_URL = '/idp/profile/OAUTH2/Redirect/GLO';

    // 域名
    protected $domain;
    // 客户端应用注册ID
    protected $clientId;
    // 客户端应用注册密钥
    protected $clientSecret;

    public function __construct(array $config)
    {
        $this->domain = $config['domain'];
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
    }

    /**
     * 应用登出
     * @param string $entityId 应用ID
     * @param string $redirctToUrl 回调url
     * @param bool $redirectToLogin 是否直接跳转至应用回调url还是停留在idp退出页
     */
    public function getLogOut(string $redirctToUrl, string $redirectToLogin = 'true')
    {
        $query = [
            'redirctToUrl'    => $redirctToUrl,
            'redirectToLogin' => $redirectToLogin,
            'entityId'        => $this->clientId
        ];

        return $this->jump(self::LOGOUT_URL, $query);
    }

    /**
     * 请求用户授权接口
     * @param string|array $redirectUri 跳转地址(uri编码)
     * @param string $state 用于保持请求和回调的状态，在回调时，会在Query Parameter中回传该参数。
     * @param string $responseType code
     */
    public function authorize($redirectUri, string $state = '', string $responseType = 'code')
    {
        $query = [
            'redirect_uri'  => $redirectUri,
            'state'         => $state,
            'client_id'     => $this->clientId,
            'response_type' => $responseType,
        ];

        return $this->jump(self::AUTHORIZE_URL, $query);
    }

    /**
     * 获取授权Token接口(authorization_code模式)
     * @param string $code 调用authorize接口获得的授权码code
     * @param string $grantType 请求类型，默认authorization_code
     */
    public function getToken(string $code = 'code', string $grantType = 'authorization_code')
    {
        $query = [
            'client_id'     => $this->clientId,
            'grant_type'    => $grantType,
            'code'          => $code,
            'client_secret' => $this->clientSecret,
        ];

        return $this->send(self::GET_TOKEN_URL, $query, 'POST');
    }

    /**
     * 刷新授权接口
     * @param string $refreshToken 刷新token授权码
     * @param string $grantType 请求类型，默认refresh_token
     */
    public function refreshToken(string $refreshToken, string $grantType = 'refresh_token')
    {
        $query = [
            'client_id'     => $this->clientId,
            'grant_type'    => $grantType,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
        ];

        return $this->send(self::REFRESH_TOKEN_URL, $query, 'POST');
    }

    /**
     * 查询授权接口
     * @param string $accessToken token授权码
     */
    public function getTokenInfo(string $accessToken)
    {
        $query = [
            'access_token' => $accessToken
        ];

        return $this->send(self::GET_TOKEN_INFO_URL, $query, 'GET');
    }

    /**
     * 回收授权接口
     * @param string $accessToken token授权码
     */
    public function revokeToken(string $accessToken)
    {
        $query = [
            'access_token' => $accessToken
        ];

        return $this->send(self::REVOKE_TOKEN_URL, $query, 'GET');
    }

    /**
     * 检查授权是否有效接口
     * @param string $accessToken token授权码
     */
    public function checkTokenValid(string $accessToken)
    {
        $query = [
            'access_token' => $accessToken
        ];

        return $this->send(self::CHECK_TOKEN_VALID_URL, $query, 'GET');
    }

    /**
     * 获取用户信息接口
     * @param string $accessToken token授权码
     */
    public function getUserInfo(string $accessToken)
    {
        $query = [
            'access_token' => $accessToken,
            'client_id'    => $this->clientId
        ];

        return $this->send(self::GET_USERINFO_URL, $query, 'GET');
    }

    // 返回拼接完整的url地址
    private function url(string $path, array $query): string
    {
        return $this->domain . $path . '?' . http_build_query($query);
    }

    // curl发请求
    private function send(string $path, array $query, string $method = 'GET')
    {
        // 这块有个bug，get请求的参数必须传params参数，否则响应端接不到值
        $response = guzHttpRequest($this->url($path, $query), $method == 'GET' ? $query : [], $method);

        $errorCode = $response['errcode'] ?? 0;

        if ($errorCode) {
            throw new IAMOauthException($response['msg'], $errorCode);
        }

        return $response;
    }

    // 302路由跳转
    private function jump(string $path, array $query)
    {
        $url = $this->url($path, $query);

        return redirect($url);
    }
}
