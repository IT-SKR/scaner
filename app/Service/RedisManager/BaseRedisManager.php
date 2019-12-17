<?php


namespace App\Service\RedisManager;


use Illuminate\Support\Facades\Redis;

class BaseRedisManager
{

    public $prefix = 'scaner';

    public $connection = 'main';

    public static function setUserSession($token,$user){

        Redis::set("user:token:$token",$user->toJson());
    }

    public static function getUserSession($token){
        return json_decode(Redis::get("user:token:$token"));
    }



}