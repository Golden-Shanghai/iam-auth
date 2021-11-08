<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminOauthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users_third_pf_bind', function (Blueprint $table) {

            $table->increments('id');
            $table->string('platform', 50);
            $table->unsignedInteger('user_id');
            $table->string('third_user_id', 191);
            $table->timestamps();

            $table->unique(['platform', 'user_id', 'third_user_id']);

        });

        DB::table('admin_menu')
            ->where('title', 'Dashboard')
            ->update(['title' => '欢迎', 'icon' => 'fa-home']);

        DB::table('admin_menu')
            ->where('title', 'Admin')
            ->update(['title' => '技术运维', 'order' => 99]);

        DB::table('admin_roles')
            ->where('id', 1)
            ->update(['name' => '管理员']);

        $renamed = [
            'All permission'  => '全部权限',
            'Dashboard'       => '欢迎页',
            'Login'           => '登录登出',
            'Auth management' => '技术运维',
            'User setting'    => '个人设置',
        ];

        foreach ($renamed as $old => $new) {
            DB::table('admin_permissions')
                ->where(['name' => $old])
                ->update(['name' => $new]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users_third_pf_bind');
    }
}
