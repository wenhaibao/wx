<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class TestController extends Controller
{
    /**
     * test1
     */
    public function test1()
    {
        // echo __METHOD__;
        // $user_info = DB::table('p_users')->limit(4)->get()->toArray();
        // print_r($user_info);
        $key = 'wx2004';
        Redis::set($key,time());
        echo Redis::get($key);
    }
    public function test2()
    {
        print_r($_GET);
    }
    public function test3()
    {
        // print_r($_POST);
        $xml_str = file_get_contents("php://input");
        //将xml 转换为 对象或数组
        $xml_obj = simplexml_load_string($xml_str);
        // echo '<pre>';print_r($xml_obj);echo '</pre>';
        echo $xml_obj->ToUserName;
    }
    public function guzzle1()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APPID')."&secret=".env('WX_APPSEC');
        // echo $url;exit;
        // 使用guzzle发起get请求
        $client = new Client();
        $response = $client->request('GET',$url,['verify' => false]);  //发起请求并接受响应
        $json_str = $response->getBody();  //服务器相应数据
        echo $json_str;
    }
    public function aaa()
    {
        $xml = '<xml><ToUserName><![CDATA[gh_d73211005fc1]]></ToUserName>
        <FromUserName><![CDATA[ov8Pk550CtW7sxoSuwrWonqh6ffk]]></FromUserName>
        <CreateTime>1606456596</CreateTime>
        <MsgType><![CDATA[event]]></MsgType>
        <Event><![CDATA[subscribe]]></Event>
        <EventKey><![CDATA[]]></EventKey>
        </xml>';
        $obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        dd($obj);
    }
}
