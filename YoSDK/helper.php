<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/11
 * Time: 16:51
 */
if (!function_exists('yolog')) {
    /**
     * @author: promise tan
     * @param 进行日志记录
     * @param string $logFileName
     */
    function yolog()
    {
        return app('yologger');
    }
}