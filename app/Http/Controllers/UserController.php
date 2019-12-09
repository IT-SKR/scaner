<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Itskr\SkrLaravel\Skr;

class UserController extends Controller
{

    //
    public function scx_login(Request $request){


        return Skr::response('SUCCESS');
    }


    public function register(){

    }

}
