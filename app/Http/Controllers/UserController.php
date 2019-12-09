<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Itskr\SkrLaravel\Skr;

class UserController extends Controller
{

    //
    public function scx_login(Request $request){

        Log::info('sxc_login_code:',$request->all());
        Skr::check($request->all(),['code'=>['required|max:30']]);
        Log::info('sxc_login_code_checked');

        $http = new Client();

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


        if ($data['errcode'] !== 0){
            return Skr::response('DEFAULT',$data);
        }


        //如果成功拿到用户信息，则给用户进行注册，注册的密码统一用sxc_123456

        $user = User::where('openid',$data['openid'])->first();

        //用户为空，进行注册
        if (empty($user)){
            User::create([
                'openid' => $data['openid'],
                'password' => Hash::make('sxc_123456'),
            ]);
        }

        return Skr::response('SUCCESS',[
            'access_token'=>'success',
            'openid'=>$data['openid']
        ]);
    }


    public function register(){

    }

}
