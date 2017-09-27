<?php
namespace App\Yoona\Packages;


/**
 *
 * 功能描述：对于客户配送实行
 *
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/4
 * Time: 9:31
 */
class Pointer
{
    /**
     * 根据经纬度和半径计算出范围
     * @param string $lat 经度
     * @param String $lng 纬度
     * @param float $radius 半径
     * @return Array 范围数组
     */
    private function calcScope($lat, $lng, $radius)
    {
        $degree = (24901 * 1609) / 360.0;
        $dpmLat = 1 / $degree;

        $radiusLat = $dpmLat * $radius;
        $minLat = $lat - $radiusLat;       // 最小经度
        $maxLat = $lat + $radiusLat;       // 最大经度

        $mpdLng = $degree * cos($lat * (PI / 180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng * $radius;
        $minLng = $lng - $radiusLng;      // 最小纬度
        $maxLng = $lng + $radiusLng;      // 最大纬度

        /** 返回范围数组 */
        $scope = array(
            'minLat' => $minLat,
            'maxLat' => $maxLat,
            'minLng' => $minLng,
            'maxLng' => $maxLng
        );
        return $scope;
    }

    /**
     * 根据经纬度和半径查询在此范围内的所有的客户
     * @param  String $lat 经度
     * @param  String $lng 纬度
     * @param  float $radius 半径
     * @return Array         计算出来的结果
     */
    public function searchByLatAndLng($lat, $lng, $radius)
    {
        $scope = $this->calcScope($lat, $lng, $radius);        // 调用范围计算函数，获取最大最小经纬度
        /** 查询经纬度在 $radius 范围内的电站的详细地址 */
        //$sql = 'SELECT `字段` FROM `表名` WHERE `Latitude` < '.$scope['maxLat'].' and `Latitude` > '.$scope['minLat'].' and `Longitude` < '.$scope['maxLng'].' and `Longitude` > '.$scope['minLng'];
        $sql = '';
        $res = [];
        // $stmt = self::$db->query($sql);
        // $res = $stmt->fetchAll(PDO::FETCH_ASSOC);       // 获取查询结果并返回
        return $res;
    }

    /**
     * 获取两个经纬度之间的距离
     * @param  string $lat1 经一
     * @param  String $lng1 纬一
     * @param  String $lat2 经二
     * @param  String $lng2 纬二
     * @return float  返回两点之间的距离
     */
    public function calcDistance($lat1, $lng1, $lat2, $lng2)
    {
        /** 转换数据类型为 double */
        $lat1 = doubleval($lat1);
        $lng1 = doubleval($lng1);
        $lat2 = doubleval($lat2);
        $lng2 = doubleval($lng2);
        /** 以下算法是 Google 出来的，与大多数经纬度计算工具结果一致 */
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344);
    }
}