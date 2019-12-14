<?php


namespace App\Service\RedisManager;


use Illuminate\Support\Facades\Redis;

class BaseRedisManager
{

    public $prefix = 'scaner';

    public $connection = 'main';

    public static function setUserSession($token,$user){
        Redis::set("user:token:$token",$user);
    }

    public static function getUserSession($token){
        return Redis::get("user:token:$token");
    }



}