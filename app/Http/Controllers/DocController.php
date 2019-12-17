<?php

namespace App\Http\Controllers;

use App\Doc;
use App\DocImage;
use App\Service\OcrManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Itskr\SkrLaravel\Skr;

class DocController extends Controller
{
    //

    public function index(Request $request){

        $user = Auth::guard('api')->user();

        if (empty($user)){
            Skr::response('BUSY');
        }

        //端上需要传递当前获取到的最大 doc_id ，防止重复获取
        $doc_id = $request->has('doc_id')?$request->has('doc_id'):0;
        //没次只获取15条
        $data = Doc::where('user_id',$user->id)->where('id','>',$doc_id)->take(15)->get();

        return Skr::response('SUCCESS',$data);

    }

    public function create(Request $request){

        $user = Auth::guard('api')->user();

        Log::info('user.'.json_encode($user));

        //先去识别信息，如果信息识别有误就不再继续
        //第一张图片识别的结果用来作为文档

        $docInfo = [];
        $doc_id = null;
        $doc_images = [];
        Log::info('doc_create_all_request',$request->all());

        foreach ($request->images as $key=>$image){

            $imageUrl  = 'https://scaner.yuzhidushu.com/storage/'.$image;

            $res = OcrManager::toOcr($imageUrl);

            //第一张的时候给docInfo赋值
            if (empty($docInfo)){

                $docInfo = OcrManager::resolveRes($res);

                $doc = Doc::create([
                    array_merge($docInfo,['user_id'=>$user->id])
                ]);

                $doc_id = $doc->id;
            }

            if (empty($doc_id)){
                Skr::exception('BUSY');
            }

            $doc_images[] = [
                'index'=>$key,
                'doc_id'=>$doc_id,
                'doc_image_url'=>$imageUrl,
                'content'=>$res,
                'status'=>9,
                'created_at'=>time(),
                'updated_at'=>time()
            ];
        }

        if (empty($doc_images)){
            return Skr::response('PARAM_MIS');
        }

        DocImage::insert($doc_images);

        return Skr::response('SUCCESS');

    }

    //存储单张图片的接口
    public function saveImage(Request $request){
        $image_name = $request->file('pic')->store('','public');
        return Skr::response('SUCCESS',[
            'image_name'=>$image_name
        ]);
    }

    public function update(Request $request){

    }

    public function detail(Request $request){

    }

}
