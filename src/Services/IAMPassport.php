<?php

namespace Ze\IAMAuth\Services;

use Ze\IAMAuth\Exceptions\IAMOauthException;
use Ze\IAMAuth\Models\AdminUserThirdPfBind;

class IAMPassport
{
    const SOURCE = '高灯应用集中安全访问导航平台';

    const GET_USERS_URL = '/bim-server/api/rest/customization/ExtApiCustTargetUserQueryService/queryUser',
        GET_ORGANIZATIONS_URL = '/bim-server/api/rest/customization/ExtApiCustTargetOrganizationQueryService/queryOrganization';

    protected $domain;
    protected $systemCode;
    protected $secretKey;

    public function __construct(array $config)
    {
        $this->domain = $config['domain'];
        $this->systemCode = $config['system_code'];
        $this->secretKey = $config['secret_key'];
    }

    // 根据第三方用户信息获取我方用户信息,并自动创建关联
    public function getUserByThird(array $userInfo, bool $autoCreate = false)
    {
        if (! isset($userInfo['uid']) || ! $userInfo['uid']) {
            throw new IAMOauthException('Invalid ThirdId', -1);
        }

        // 数据库关联的用户数据
        $user = AdminUserThirdPfBind::getUserByThird(AdminUserThirdPfBind::IAM_PLATFORM, $userInfo['uid']);

        if ($user || (! config('iam.allowed_auto_create_account') && ! $autoCreate)) {
            return $user;
        }

        // 用户不存在且开启了自动创建账号，则新增一个
        $userModel = config('admin.database.users_model');

        $user = $userModel::firstOrCreate([
            'username' => $userInfo['loginName'],
        ], [
            'name'     => $userInfo['displayName'],
            'avatar'   => $userInfo['avatar_url'] ?? '',
            'password' => '',
        ]);

        $this->bindUserByThird($user, $userInfo);

        return $user;
    }

    // 将指定第三方账号和指定官方账号绑定
    public function bindUserByThird($user, array $userInfo)
    {
        if (! isset($userInfo['uid']) || ! $userInfo['uid']) {
            throw new IAMOauthException('Invalid ThirdId', -1);
        }

        // 三方平台uid
        $uid = $userInfo['uid'];
        $platform = AdminUserThirdPfBind::IAM_PLATFORM;

        // 检查该官方账号是否已绑定其他社区账号
        $bindRelation = AdminUserThirdPfBind::where(['user_id' => $user->id, 'platform' => $platform,])->first();


        if ($bindRelation && $bindRelation['third_user_id'] == $uid) {
            return true;
        }

        if ($bindRelation && $bindRelation['third_user_id'] != $uid) {
            $bindRelation->delete();
        }

        // 检查该社区账号是否已绑定其他官方账号
        $bindRelation = AdminUserThirdPfBind::where(['platform' => $platform, 'third_user_id' => $uid])->first();

        if ($bindRelation && $bindRelation['third_user_id'] == $uid) {
            return true;
        }

        if ($bindRelation && $bindRelation['third_user_id'] != $uid) {
            $bindRelation->delete();
        }

        // 创建账号绑定关系
        AdminUserThirdPfBind::create(['user_id' => $user->id, 'platform' => $platform, 'third_user_id' => $uid]);

        // 更新本地user
        $user->update([
            'name'   => $userInfo['displayName'],
            'avatar' => $userInfo['avatar_url'] ?? '',
        ]);

        return true;
    }

    public function getSource()
    {
        return self::SOURCE;
    }

    // 全量三方用户接口
    public function allUsers($username = '')
    {
        $postParams = [
            'systemCode' => config('iam.services.iam_oauth.system_code'),
            'secretKey'  => config('iam.services.iam_oauth.secret_key'),
            'username'   => $username
        ];

        return $this->post(self::GET_USERS_URL, $postParams);
    }

    // post请求,并处理可能异常
    private function post(string $path, array $query)
    {
        $response = guzHttpRequest($this->domain . $path, $query, 'POST');

        if (! ($response['success'] ?? false)) {
            throw new IAMOauthException($response['errorMessage'], $response['errorCode']);
        }

        return ! empty($response['data']) ? $response['data'] : $response;
    }
}
