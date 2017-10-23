<?php

namespace TerryLucas2017\Interview;

/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.23
 * Time 14:49
 */
/**
 * Class Demo01
 * Author Terry Lucas
 * @package TerryLucas2017\Interview
 */
/**
 * Class Demo01
 * Author Terry Lucas
 * @package TerryLucas2017\Interview
 */
class Demo01
{
    /**
     * @author Terry Lucas
     * Demo01 constructor.
     */
    public function __construct()
    {
    }

    /**
     * @author Terry Lucas
     */
    public function index()
    {
        //return 'OK';
        // num1
        // $current = time();

        // return date('Y-m-d H:i:s' , strtotime('-1 days' , $current));

        //num2
        // $str = '123456';

        // return strrev($str);

        //num3

        //return $_SERVER;

        // dump($_SERVER);
        // $clientIp = $_SERVER['REMOTE_ADDR'];
        // $serverIp = $_SERVER['SERVER_NAME'];

        // return $clientIp.$serverIp;

        // $a = '456';
        // $b = &$a;
        // unset($b);
        // $b = '678';

        // return $a . '---------' . $b;

        // return empty([]) ? 't' : 'f';

        // $str = '中华人民共和国';

        // return mb_substr($str, 0, 3);

        // $email = '12321@sina.com';
        // if (!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$email)) {
        //     return  "无效的 email 格式！";
        // }

        // return 'ok';
        // $dir = app_path();
        // $files = [];
        // dump($this->myScanDir($dir));
        $menus = [
            ['t1', 'tan1', 't0'],
            ['t2', 'tan2', 't1'],
            ['t3', 'tan3', 't2'],
            ['t4', 'tan4', 't1'],
            ['t5', 'tan5', 't2'],
            ['t6', 'tan6', 't1'],
            ['t7', 'tan7', 't3'],
        ];
        dd($this->rbM($menus , 't2'));
    }

    /**
     * @author Terry Lucas
     * @param $dir
     */
    private function myScanDir($dir)
    {
        //传入需要遍历的目录
        $files = [];
        if (!is_dir($dir)) {

            return $files;
        }
        //打开目录
        if (!$fileHandle = opendir($dir)) {
            closedir($fileHandle);

            return $files;
        }

        //进行读取目录获取字符串 如果是不为空则进行下一步操作
        while (($file = readdir($fileHandle)) !== FALSE) {
            //判断是否是点和点点的情况 如果是则表示该层次还是目录进一步进行循环遍历
            if ($file !== "." && $file !== "..") {
                $files[$file] = $this->myScanDir($dir."/".$file);
            } else {
                $files[] = $dir.'/'.$file;
            }
        }
        //关闭文件流
        closedir($fileHandle);

        return $files;
    }

    /**
     * @author Terry Lucas
     * @param $menus
     * @param $m
     * @return array
     */
    private function rbM($menus , $m)
    {
        $temp = [];
        foreach ($menus as $menu){
            if($menu[2] === $m){
                $menu['sons'] = $this->rbM($menus , $menu[0]);
                $temp[] = $menu;
            }
        }

        return $temp;
    }
}