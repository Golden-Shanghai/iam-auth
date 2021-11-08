<?php

namespace Ze\IAMAuth\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserThirdPfBind extends Model
{
    const IAM_PLATFORM = 'IAMPassport';

    protected $table = 'admin_users_third_pf_bind';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(config('admin.database.users_model'), 'user_id');
    }

    public static function getBindRelation(string $platform, string $thirdUid)
    {
        $pk = [
            'platform'      => $platform,
            'third_user_id' => $thirdUid,
        ];

        return self::where($pk)->first();
    }

    public static function getBindRelationByUid(string $platform, string $uid)
    {
        $pk = [
            'platform' => $platform,
            'user_id'  => $uid,
        ];

        return self::where($pk)->first();
    }

    public static function getUserByThird(string $platform, string $thirdUid)
    {
        $bindRelation = self::getBindRelation($platform, $thirdUid);

        return $bindRelation ? $bindRelation->user : null;
    }

    public static function getBindUids(string $platform)
    {
        return self::where(['platform' => $platform])->pluck('third_user_id')->toArray();
    }
}
