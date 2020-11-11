<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;


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
}
