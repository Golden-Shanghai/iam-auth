<?php


namespace Ze\IAMAuth\Http\Controllers\Api;

use Illuminate\Http\Request;
use Ze\IAMAuth\Business\UserBusiness;

class UserController
{
    // 对象属性字段查询
    public function schema()
    {
        try {
            return iamOk(UserBusiness::getBasicTablesInfo());
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }
    }

    // 账号创建
    public function userCreate(Request $request)
    {
        try {
            $userInfo = $request->validate([
                // 账号
                'loginName' => 'required|string',
                // 组织id
                'orgId'     => 'required|string',
                // 姓名
                'fullName'  => 'required|string',
            ]);

            return iamOk(['uid' => UserBusiness::addNewUser($userInfo)]);
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }
    }

    // 账号修改
    public function userUpdate(Request $request)
    {
        try {
            $userInfo = $request->validate([
                // uid
                'bimUid'    => 'required|string',
                // 登录账号
                'loginName' => 'required|string',
                // 姓名
                'fullName'  => 'required|string',
            ]);

            UserBusiness::editUser($userInfo);

            return iamOk();
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }
    }

    // 账号删除
    public function userDelete(Request $request)
    {
        try {
            $userInfo = $request->validate([
                'bimUid' => 'required|string'
            ]);

            UserBusiness::deleteUser($userInfo['bimUid']);

            return iamOk();
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }
    }

    // 批量查询账号ID
    public function queryAllUserIds()
    {
        try {
            return iamOk(['userIdList' => UserBusiness::getUids()]);
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }
    }

    // 根据账号ID查询账号详细内容
    public function queryUserById(Request $request)
    {
        try {
            $userInfo = $request->validate([
                'bimUid' => 'required|string'
            ]);

            return iamOk(['account' => UserBusiness::getUserInfo($userInfo['bimUid'])]);
        }
        catch (\Throwable $e) {
            return iamErr($e);
        }
    }
}
