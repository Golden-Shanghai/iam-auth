<?php


namespace Ze\IAMAuth\Facades;


use Illuminate\Support\Facades\Facade;

class OAuthAuthentication extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'iam-oauth';
    }
}
