<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class XcxController extends Controller
{
    /**
     * 小程序登录
     */
    public function login(Request $request)
    {
        // 接收code
        $code = $request->get('code');
        // 使用 code
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.env('WX_XCX_APPID').'&secret='.env('WX_XCX_SECRET').'&js_code='.$code.'&grant_type=authorization_code';
        $data = json_decode(file_get_contents($url),true);
        // print_r($data);

        // 自定义登录状态
        if(isset($data['errcode']))   //有错误
        {
            $response = [
                'errno' =>50001,
                'msg' =>'登录失败',
            ];
        }else{  //成功
            $token = sha1($data['openid'].$data['session_key'].mt_rand(0,999999));
            // 服务器端保存
            $redis_key = 'xcx_token:'.$token; 
            Redis::set($redis_key,time());
            REdis::expire($redis_key,7200);
            $response = [
                'errno' =>0,
                'msg' =>'ok',
                'data' =>[
                    'token' =>$token
                ]
            ];
        }
        return $response;
    }
}
