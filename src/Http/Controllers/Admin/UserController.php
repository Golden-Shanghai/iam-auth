<?php


namespace Ze\IAMAuth\Http\Controllers\Admin;

use Encore\Admin\Controllers\UserController as BaseUserController;
use Encore\Admin\Form;
use Illuminate\Support\MessageBag;
use Ze\IAMAuth\Models\AdminUserThirdPfBind;

class UserController extends BaseUserController
{
    public function form()
    {
        $userModel = config('admin.database.users_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $userModel());

        $form->display('id', 'ID');

        $goldenUsers = self::fetchGoldenPassportUids();

        if ($form->isCreating()) {
            $form->select('golden_uid', '高灯账号')
                ->options(\Arr::pluck($goldenUsers, 'fullname', 'userId'))
                ->rules('required');
            $form->hidden('name');
            $form->hidden('username');

            $form->text('name', '账号')->rules('required');
            $form->text('username', '姓名')->rules('required');
            $form->hidden('avatar');
            $form->hidden('password');
            $form->ignore(['golden_uid']);
        }
        else {
            $form->display('name', trans('admin.name'));
        }

        $form->multipleSelect('roles', trans('admin.roles'))
            ->options($roleModel::all()->pluck('name', 'id'));

        $form->multipleSelect('permissions', trans('admin.permissions'))
            ->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('admin.created_at'));

        $form->saving(function (Form $form) use ($goldenUsers) {

            if ($goldenUid = request('golden_uid')) {

                // 检测重复绑定
                if (AdminUserThirdPfBind::getBindRelation(AdminUserThirdPfBind::IAM_PLATFORM, $goldenUid)) {
                    return back()->withInput()->withErrors(new MessageBag(['golden_uid' => '该高灯账号已被其他账号绑定']));
                }

                // 同步用户资料
                $form->name = $goldenUsers[$goldenUid]['fullname'];
                $form->username = $goldenUsers[$goldenUid]['username'];
                $form->avatar = '';
                $form->password = '';
            }

        });

        $form->saved(function (Form $form) {

            // 创建绑定关系
            if ($goldenUid = request('golden_uid')) {
                AdminUserThirdPfBind::create([
                    'user_id'       => $form->model()->id,
                    'platform'      => AdminUserThirdPfBind::IAM_PLATFORM,
                    'third_user_id' => $goldenUid,
                ]);
            }

        });

        return $form;
    }

    // 获取所有高灯员工列表
    protected static function fetchGoldenPassportUids()
    {
        $users = \IAMPassport::allUsers();

        // 替换key为uid
        return array_combine(array_column($users, 'userId'), $users);
    }
}
