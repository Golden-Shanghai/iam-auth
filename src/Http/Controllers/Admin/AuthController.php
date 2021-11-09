<?php

namespace Ze\IAMAuth\Http\Controllers\Admin;

use \App\Admin\Controllers\AuthController as Controller;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;
use Ze\IAMAuth\Exceptions\IAMOauthException;

class AuthController extends Controller
{
    // session中state的key
    private $stateKey;

    private $platform = 'IAMPassport';

    public function __construct()
    {
        $this->stateKey = config('app.name') . '_oauth_state';
    }

    // 登入
    public function login()
    {
        // 已登录的用户
        if ($this->guard()->check()) {
            if (request('reset')) {
                // 强行退出
                parent::getLogout(request());
            }
            else {
                // 无需访问登录页
                return redirect($this->redirectPath());
            }
        }

        // 强行用密码登录
        if (request('pwd_login')) {
            return parent::getLogin();
        }

        // 跳转登录授权流程
        return redirect(admin_url('/oauth/authorize'));
    }

    // 登出
    public function logout(Request $request)
    {
        parent::getLogout($request);

        return \IAMOauth::getLogOut(admin_url('/auth/login'), 'true');
    }

    // 授权
    public function authorize(Request $request)
    {
        // 登录成功的回调地址
        $redirectUrl = admin_url('/oauth/callback');

        // 随机生成回调参数
        $this->state = md5(rand(1, 10000) . uniqid(rand(1, 10000)));

        // 存state
        $request->session()->put($this->stateKey, $this->state);

        // 跳转登录页
        return \IAMOauth::authorize($redirectUrl, $this->state);
    }

    // 授权回调
    public function callback(Request $request)
    {
        $data = $request->validate([
            'code'  => 'required|string',
            'state' => 'nullable|string',
        ]);

        $code = $data['code'];
        $state = $data['state'];

        $cacheState = $request->session()->pull($this->stateKey, '');

        if ($state != $cacheState) {
            throw new IAMOauthException('登录验证state失败', -1);
        }

        // 获取accessToken
        $response = \IAMOauth::getToken($code);

        $accessToken = $response['access_token'];

//        $refreshToken = $response['refresh_token'];
//        $uid = $response['uid'];
//        $expiresIn = $response['expires_in'];

        // 获取userinfo
        $userInfo = \IAMOauth::getUserInfo($accessToken);

        // 根据第三方用户信息获取我方用户信息
        $user = \IAMPassport::getUserByThird($userInfo);

        if (! $user) {
            // 未关联，且需要手动关联
            // 临时存储第三方用户信息
            $request->session()->put('IAMAdminOAuthThirdUser', ['user_info' => $userInfo]);

            // 跳转绑定页
            return redirect()->guest(admin_url('oauth/bind-account'));
        }

        if ($user) {
            // 同步更新用户信息
            $user->update([
                'name'   => $userInfo['displayName'],
                'avatar' => $userInfo['avatar_url'] ?? '',
            ]);
        }

        // laravel-admin登录
        Admin::guard()->login($user);

        admin_toastr(trans('admin.login_successful'));

        return redirect(admin_url('/'));
    }

    // 账号绑定
    public function bindAccount(Request $request)
    {
        if (! $cache = $request->session()->get('IAMAdminOAuthThirdUser')) {
            throw new IAMOauthException('Not Found Third User Info', -1);
        }

        // GET请求直接跳账号绑定页
        if ($request->isMethod('GET')) {
            return view('iam-auth::bind-account', [
                'sourceName' => \IAMPassport::getSource(),
            ]);
        }

        // POST请求：
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only(['username', 'password']);

        if (! Admin::guard()->validate($credentials)) {
            return back()->withInput()->withErrors(['username' => '账号或密码不正确']);
        }

        $user = Admin::guard()->getLastAttempted();

        \IAMPassport::bindUserByThird($user, $cache['user_info']);

        Admin::guard()->login($user);

        admin_toastr('绑定成功');

        return redirect(admin_url('/'));
    }
}
