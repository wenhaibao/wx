<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class WxController extends Controller
{
    /**
     * 微信接入
     */
    // public function wx()
    // {
    // $signature = $_GET["signature"];
    // $timestamp = $_GET["timestamp"];
    // $nonce = $_GET["nonce"];
	
    // $token = env('WX_TOKEN');
    // $tmpArr = array($token, $timestamp, $nonce);
    // sort($tmpArr, SORT_STRING);
    // $tmpStr = implode( $tmpArr );
    // $tmpStr = sha1( $tmpStr );
    
    // if( $tmpStr == $signature )
    //     {
    //         echo $_GET['echostr'];
    //     }else{
    //         echo '';
    //     }
    // }
    /**
     * 处理推送文件
     */
    public function wxEvent()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        
        $token = env('WX_TOKEN');
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
    
        if($tmpStr == $signature)  //验证通过
        {
            // 1接收数据
            $xml_str = file_get_contents("php://input");
            // 记录日志
            file_put_contents('wx_event.log',$xml_str,FILE_APPEND);
            // 2把xml文本转换为php的对象或者数组
            $obj = simplexml_load_string($xml_str);
            if($obj->MsgType=="event")
            {
                if($obj->Event=="subscribe")
                {
                    $content = "欢迎关注";
                    echo $this->sub($obj,$content);
                    exit;
                }
            }
            echo "";
            die;
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
    /**
     * 关注回复消息
     */
    public function sub($obj,$content)
    {
        $FromUserName = $obj->ToUserName;
        $ToUserName = $obj->FromUserName;
        $xml = "<xml><ToUserName><![CDATA[".$ToUserName."]]></ToUserName>
                    <FromUserName><![CDATA[".$FromUserName."]]></FromUserName>
                    <CreateTime>1605066750</CreateTime>
                    <MsgType><![CDATA[event]]></MsgType>
                    <Event><![CDATA[subscribe]]></Event>
                    <EventKey><![CDATA[".$content."]]></EventKey>
                </xml>";
                $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                return $xml;
    }
}
