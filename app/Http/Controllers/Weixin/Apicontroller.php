<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Apicontroller extends Controller
{
    //
    public function test()
    {
        $goods_info = [
            'goods_id' =>32,
            'goods_name' =>"iphone",
            'pirce' => 12.12
        ];
        echo json_encode($goods_info);
    }
    /**
     * 商品列表
     */
    public function goodslist()
    {
        $goodsinfo = DB::table('p_goods')->select()->limit(6)->get()->toArray();
        $response = [
            'errno' =>0,
            'msg' =>'ok',
            'data' => [
                'list' => $goodsinfo
            ]
            ];
            return $response;
    }
}
