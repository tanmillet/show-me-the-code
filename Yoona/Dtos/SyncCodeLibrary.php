<?php

namespace App\Yoona\Dtos;

use Illuminate\Support\Facades\Cache;

class SyncCodeLibrary extends YoBase
{
    //
    protected $table = 'sync_code_library';

    //根据地区代码返回文本信息
    public static function getText($code, $type = "area_code")
    {
        if (strlen($code) != 6) {
            throw new \Exception("the area code's length must be 6");
        }

        switch ($type) {
            //返回地区信息，如代码440303，返回广东省深圳市罗湖区
            case 'area_code':
                $model = self::where("CODENO", "AreaCode")->where("ITEMNO", $code)->first();
                break;
            //返回地区信息（不包括上级信息），如440300返回深圳市；440303返回罗湖区
            case 'detail_area_code':
                $model = self::where("CODENO", "DetailAreaCode")->where("ITEMNO", $code)->first();
                break;
            default:
                return null;
        }

        return empty($model) ? null : $model->ITEMNAME;
    }

    //获取所有省份列表
    public static function getProvinceLists()
    {
        if (Cache::has("province_lists")) {
            $arr = json_decode(Cache::get("province_lists"), true);
        } else {
            $arr = array();
            self::where("CODENO", "AreaCode")
                ->where("ITEMNO", "like", "__0000")
                ->orderBy("ITEMNO")
                ->chunk(500, function ($list) use (&$arr) {
                    foreach ($list as $code) {
                        $arr[] = array(
                            'code' => $code->ITEMNO,
                            'name' => $code->ITEMNAME
                        );
                    }
                });

            //存缓存，一天
            Cache::put("province_lists", json_encode($arr), 24 * 60 * 60);
        }

        return $arr;
    }

    //根据省份代码获取所有城市列表
    public static function getCityLists($code)
    {
        if (strlen($code) != 6) {
            throw new \Exception("the area code's length must be 6");
        }

        if (Cache::has("city_lists")) {
            $arr = json_decode(Cache::get("city_lists"), true);
        } else {
            $arr = array();
            self::where("CODENO", "DetailAreaCode")
                ->where("ITEMNO", "!=", substr($code, 0, 2) . "0000")
                ->where("ITEMNO", 'like', substr($code, 0, 2) . "__" . "00")
                ->orderBy("ITEMNO")
                ->chunk(500, function ($list) use (&$arr) {
                    foreach ($list as $code) {
                        $arr[] = array(
                            'code' => $code->ITEMNO,
                            'name' => $code->ITEMNAME
                        );
                    }
                });

            //存入缓存，一天
            Cache::put("city_lists", json_encode($arr), 24 * 60 * 60);
        }

        return $arr;
    }

    //根据城市代码获取下属县区列表
    public static function getDistrictLists($code)
    {
        if (strlen($code) != 6) {
            throw new \Exception("the area code's length must be 6");
        }

        if (Cache::has("district_lists")) {
            $arr = json_decode(Cache::get("province_lists"), true);
        } else {
            $arr = array();
            self::where("CODENO", "DetailAreaCode")
                //00代表城市自己，01表示为市辖区，先去除
                ->whereNotIn("ITEMNO", array(substr($code, 0, 4) . "00", substr($code, 0, 4) . "01"))
                ->where("ITEMNO", 'like', substr($code, 0, 4) . "__")
                ->orderBy("ITEMNO")
                ->chunk(500, function ($list) use (&$arr) {
                    foreach ($list as $code) {
                        $arr[] = array(
                            'code' => $code->ITEMNO,
                            'name' => $code->ITEMNAME
                        );
                    }
                });

            //存入缓存，一天
            Cache::put("city_lists", json_encode($arr), 24 * 60 * 60);
        }

        return $arr;
    }
}