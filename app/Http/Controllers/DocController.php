<?php

namespace App\Http\Controllers;

use App\Doc;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Itskr\SkrLaravel\Skr;

class DocController extends Controller
{
    //

    public function index(Request $request){
        $user = User::where('openid',$request->openid)->find();
        if (empty($user)){
            Skr::response('BUSY');
        }

    }

    public function create(Request $request){
        $user = Auth::guard('api')->user();

        $request->file('pic1')->store('','public');

        Doc::create([
            'user_id'=>$user->id,
            'doc_name'=>'name_doc'
        ]);

        Skr::response('SUCCESS');

    }

}
