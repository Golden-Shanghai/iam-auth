<?php


namespace Ze\IAMAuth\Commands;

use Illuminate\Console\Command;

class SyncUsersFromIam extends Command
{
    protected $signature = 'iam:sync-user';

    protected $description = '同步本地账号与iam授权中心的绑定关系';

    public function handle()
    {
        // todo 先获取所有三方平台的用户列表
    }


}
