<?php


namespace Ze\IAMAuth\Facades;

use Illuminate\Support\Facades\Facade;

class IAMPassport extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'iam-passport';
    }
}
