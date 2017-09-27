<?php
class Config_Web{

    public static  $base_url = "http://xuqiu.3595.com/";

	public static $platform_name = "需求排期系统";

    //3595 部门名称信息
    public static $dept_infos = array(
        1 => "3595技术部",
        2 => "3595运维部",
        3 => "3595美术"
    );

    //3595 需求分析系统需求状态
    public static  $demand_status = array(
        1 => "未开始",
        2 => "已激活",
        3 => "进行中...",
        4 => "已完成,待验收...",
        5 => "已验收",
    );

    //计算时间间隔
    public static function gettime($timediff){
        $days = intval( $timediff / 86400 );
        $time = '';
        $time .= !empty($days) ? $days.'天': '';
        $remain = $timediff % 86400;
        $hours = intval( $remain / 3600 );
        $time .= !empty($hours) ? $hours.'时': '';
        $remain = $remain % 3600;
        $mins = intval( $remain / 60 );
        $time .= !empty($mins) ? $mins.'分': '';
        $secs = $remain % 60;
        $time .= !empty($secs) ? $secs.'秒': '';
        $time .=  empty($time) ? '最新评论' : '前评论';

        return $time;
    }

    //3595 需求分析优先级
    public static $demand_priority = array(
        1 => "1",
        2 => "2",
        3 => "3",
        4 => "4",
        5 => "5",
    );
}