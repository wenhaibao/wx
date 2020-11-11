<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class WxController extends Controller
{
    /**
     * 微信接入
     */
    public function wx()
    {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];
	
    $token = env('WX_TOKEN');
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    
    if( $tmpStr == $signature )
        {
            echo $_GET['echostr'];
        }else{
            echo '';
        }
    }
    /**
     * 获取accessToken
     *
     */
    public function getAccessToken()
    {
        $key = 'wx:access_token';
        $token = Redis::get($key);
        if($token)
        {
            echo '有缓存';
            echo $token;
        }else{
            echo '无缓存';
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APPID')."&secret=".env('WX_APPSEC');
            $response = file_get_contents($url);
            // echo $response;
            $data = json_decode($response,true);
            $token = $data['access_token'];
    
            // 保存到redis 中 时间为3600
            
            Redis::set($key,$token);
            Redis::expire($key,3600);
        }
        
        echo 'access_token'.$token;
    }
}
