<?php

namespace App\Http\Controllers;

use App\Service\RedisManager\BaseRedisManager;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Itskr\SkrLaravel\Skr;

class UserController extends Controller
{

    //
    public function scx_login(Request $request){

        Log::info('sxc_login_code:',$request->all());
        Skr::check($request->all(),['code'=>['required|max:100']]);
        Log::info('sxc_login_code_checked');

        $http = new Client();

        $query = [
            'appid'=>config('weichat.appid'),
            'secret'=>config('weichat.secret'),
            'js_code'=>$request->code,
            'grant_type'=>'authorization_code'];
        Log::info('query',$query);

        $response = $http->get('https://api.weixin.qq.com/sns/jscode2session',[
            'query'=>[
                'appid'=>config('weichat.appid'),
                'secret'=>config('weichat.secret'),
                'js_code'=>$request->code,
                'grant_type'=>'authorization_code']
        ]);

        if ($response->getStatusCode() != 200){
            return Skr::response('DEFAULT');
        }

        $data = json_decode((string) $response->getBody(), true);
        Log::info('sxc_login_code_success:',$data);


        if (!empty($data['errcode'])&&empty($data['errcode']) !== 0){
            return Skr::response('DEFAULT',$data);
        }


        //如果成功拿到用户信息，则给用户进行注册，注册的密码统一用sxc_123456

        $user = User::select("scx_openid","name","id","unionid")->where('scx_openid',$data['openid'])->first();

        $user_arr = [
            'scx_openid' => $data['openid'],
            'name' => $data['openid'],
            'password' => Hash::make('sxc_123456'),
        ];

        if (!empty($data['unionid'])){
            $user_arr['unionid'] = $data['unionid'];
        }

        //用户为空，进行注册
        if (empty($user)){
            $user = User::create($user_arr);
        }

        $token = Str::random(40);

        BaseRedisManager::setUserSession($token,$user);

        return Skr::response('SUCCESS',[
            'access_token'=>$token,
            'openid'=>$data['openid']
        ]);
    }


    public function register(){

    }

}
