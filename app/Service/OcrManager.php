<?php


namespace App\Service;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use TencentCloud\Common\Credential;
use TencentCloud\Ocr\V20181119\Models\GeneralAccurateOCRRequest;
use TencentCloud\Ocr\V20181119\OcrClient;

class OcrManager
{
    public static function toOcr($imageUrl){
        //证书
        $cred = new Credential(config('weichat.cos_secret_id'),config('weichat.cos_secret_key'));
        //创建实例
        $client = new OcrClient($cred,config('weichat.cos_region'));

        $req = new GeneralAccurateOCRRequest();

        $req->ImageUrl = $imageUrl;


        $resp = $client->GeneralAccurateOCR($req);

        Log::info($resp->toJsonString());

        return $resp->toJsonString();
    }

    public static function resolveRes($res):array {

        $items = json_decode($res,true);
        $doc_name = '';
        $doc_no = '';
        $doc_at = '';


        foreach ($items['TextDetections'] as $item){

            if ($doc_name == ''){
                $doc_name = self::getDocName($item['DetectedText']);
            }

            if ($doc_no == ''){
                $doc_no = self::getDocNo($item['DetectedText']);
            }

            if ($doc_at == ''){
                $doc_at = self::getDocAt($item['DetectedText']);
            }
        }

        return [
            'doc_name'=>$doc_name,
            'doc_no'=>$doc_no,
            'doc_at'=>$doc_at,
        ];

    }


    public static function getDocName($content){

        if (Str::contains($content,'姓名'))
        {
            return Str::after($content,':');
        }

        return '';
    }

    public static function getDocNo($content){
        if (Str::contains($content,'编号'))
        {
            return Str::after($content,':');
        }
        return '';
    }

    public static function getDocAt($content){
        if (Str::contains($content,'报告时间'))
        {
            return Str::after($content,':');
        }
        return '';
    }
}