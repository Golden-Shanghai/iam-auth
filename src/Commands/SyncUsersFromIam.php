<?php


namespace Ze\IAMAuth\Commands;

use Illuminate\Console\Command;
use Ze\IAMAuth\Models\AdminUserThirdPfBind;

class SyncUsersFromIam extends Command
{
    protected $signature = 'iam:sync-user';

    protected $description = '同步本地账号与iam授权中心的绑定关系';

    public function handle()
    {
        // IAM全量用户
        $allGoldenUsers = collect(\IAMPassport::allUsers());

        $goldenUsers = [];

        foreach ($allGoldenUsers as $goldenUser) {
            $goldenUsers[strtolower($goldenUser['username'])] = $goldenUser;
        }

        $userModel = config('admin.database.users_model');

        // 分批次绑定账号操作
        $userModel::chunk(100, function ($users) use ($goldenUsers) {

            $users->each(function ($user) use ($goldenUsers) {
                // 中台账号已绑定
                if (AdminUserThirdPfBind::getBindRelationByUid(AdminUserThirdPfBind::IAM_PLATFORM, $user->id)) {
                    return true;
                }

                $goldenUser = $goldenUsers[strtolower($user->username)] ?? [];

                if (! $goldenUser) {
                    $this->error('本地账号「' . $user->username . '」未找到匹配的高灯账号');
                }

                // iam账号已绑定
                if (AdminUserThirdPfBind::getBindRelation(AdminUserThirdPfBind::IAM_PLATFORM, $goldenUser['userId'])) {
                    $this->line('高灯账号「' . $goldenUser['username'] . '」已有绑定关系');
                    return true;
                }

                // 未绑定过则增加绑定关系
                AdminUserThirdPfBind::create([
                    'user_id'       => $user->id,
                    'platform'      => AdminUserThirdPfBind::IAM_PLATFORM,
                    'third_user_id' => $goldenUser['userId'],
                ]);

                // 同步更新用户信息
                $user->update(['name' => $goldenUser['fullname']]);

                $this->info('本地账号「' . $user->username . '」绑定关系创建成功');
            });

        });

    }


}
