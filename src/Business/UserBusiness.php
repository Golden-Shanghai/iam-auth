<?php


namespace Ze\IAMAuth\Business;


use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Ze\IAMAuth\Exceptions\IAMOauthException;
use Ze\IAMAuth\Models\AdminUserThirdPfBind;

class UserBusiness
{
    // 用户相关的各表必填字段
    const REQUIRED_FIELDS = [
        'admin_users' => [
            'name',
            'username'
        ],
        'admin_roles' => [
            'slug',
            'name'
        ],
    ];

    // 额外补充的字段
    const EXTRA_FIELDS = [
        'admin_user'  => [
            [
                'name'         => 'roles',
                'mulitivalued' => true,
                'required'     => false,
                'type'         => 'integer',
            ],
            [
                'name'         => 'permissions',
                'mulitivalued' => true,
                'required'     => false,
                'type'         => 'integer',
            ]
        ],
        'admin_roles' => [
            [
                'name'         => 'permissions',
                'mulitivalued' => true,
                'required'     => false,
                'type'         => 'integer',
            ],
        ]
    ];

    // 获取用户相关的基础表的字段信息
    public static function getBasicTablesInfo()
    {
        $userTable = (new Administrator())->getTable();
        $roleTable = (new Role())->getTable();

        $tableInfo = [];
        foreach ([
                     'account' => $userTable,
                     'role'    => $roleTable
                 ] as $name => $table) {

            $columns = \Schema::getColumnListing($table);

            $tableInfo[$name] = [];

            foreach ($columns as $column) {

                $tableInfo[$name][] = [
                    // 字段名
                    'name'         => $column,
                    // 是否多选
                    'mulitivalued' => false,
                    // 是否必填
                    'required'     => in_array($column, self::REQUIRED_FIELDS[$table]),
                    // 类型
                    'type'         => \Schema::getColumnType($table, $column),
                ];

            }

            $tableInfo[$name] = array_merge($tableInfo[$name], self::EXTRA_FIELDS[$table] ?? []);
        }

        return $tableInfo;
    }

    // 账号创建
    public static function addNewUser(array $userInfo)
    {
        // 先查用户是否已存在
        $userModel = config('admin.database.users_model');

        $userId = $userModel::where('username', $userInfo['username'])->value('id');

        if ($userId && AdminUserThirdPfBind::getBindRelationByUid(AdminUserThirdPfBind::IAM_PLATFORM, $userId)) {
            iamThrow(IAMOauthException::USER_CREATE_ERROR, '用户已存在');
        }


        $userInfo = [
            'uid'         => $userInfo['id'],
            'loginName'   => $userInfo['username'],
            'displayName' => $userInfo['name']
        ];

        // 关联账户
        \IAMPassport::getUserByThird($userInfo, true);

        return $userInfo['uid'];
    }

    // 账号编辑
    public static function editUser(array $userInfo)
    {
        $uid = $userInfo['bimUid'];

        // 验证账号是否已关联
        $relationUser = AdminUserThirdPfBind::getBindRelation(AdminUserThirdPfBind::IAM_PLATFORM, $uid);

        if (! $relationUser) {
            iamThrow(IAMOauthException::USER_UPDATE_ERROR, '要修改的用户在此系统不存在');
        }

        if ($userInfo['username']) {
            // 判断下登录账号是否存在重复
            $userModel = config('admin.database.users_model');

            if ($userModel::where([['username', '=', $userInfo['username']], ['id', '<>', $relationUser->user->id]])->first()) {
                iamThrow(IAMOauthException::USER_UPDATE_ERROR, '修改的账号名已存在，请更换');
            }
        }

        unset($userInfo['bimUid']);

        return $relationUser->user->update(array_filter($userInfo));
    }

    // 删除用户
    public static function deleteUser(string $uid)
    {
        $relationUser = AdminUserThirdPfBind::getBindRelation(AdminUserThirdPfBind::IAM_PLATFORM, $uid);

        if (! $relationUser) {
            iamThrow(IAMOauthException::USER_DELETE_ERROR, '要删除的用户在此系统不存在');
        }

        $userModel = config('admin.database.users_model');

        $userId = $relationUser->user_id;

        // 按照laravel-admin的逻辑，表中至少要存在1个用户
        if ($userModel::count() < 2) {
            iamThrow(IAMOauthException::USER_DELETE_ERROR, '系统只要需要存在一个账户');
        }

        \DB::beginTransaction();

        $userRes = $userModel::destroy($userId);
        if (! $userRes) {
            \DB::rollBack();
            iamThrow(IAMOauthException::USER_DELETE_ERROR);
        }

        $relationRes = AdminUserThirdPfBind::where('user_id', $userId)->delete();

        if (! $relationRes) {
            \DB::rollBack();
            iamThrow(IAMOauthException::USER_DELETE_ERROR);
        }

        \DB::commit();

        return true;
    }

    // 返回系统中的所有外部uid
    public static function getUids()
    {
        $uids = AdminUserThirdPfBind::where('platform', AdminUserThirdPfBind::IAM_PLATFORM)->pluck('third_user_id');

        return $uids ?: [];
    }

    // 获取用户详情
    public static function getUserInfo($uid)
    {
        $relationUser = AdminUserThirdPfBind::getBindRelation(AdminUserThirdPfBind::IAM_PLATFORM, $uid);

        if (! $relationUser) {
            iamThrow(IAMOauthException::GET_USER_ERROR, '该用户在此系统中不存在');
        }

        return $relationUser->user;
    }
}
