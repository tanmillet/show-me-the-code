<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2016/12/29
 * Time: 18:07
 */

/**
 * @author: promise tan
 * @return \Illuminate\Foundation\Application|mixed
 */
if (!function_exists('C')) {

    function C($key = '')
    {
        return (yoempty($key)) ? app('yoconfiger') : app('yoconfiger')->get($key) ;
    }

}

/**
 * @author: promise tan
 * @return \Illuminate\Foundation\Application|mixed
 */
if (!function_exists('yoconf')) {

    function yoconf($key = '')
    {
        return (yoempty($key)) ? app('yoconfiger') : app('yoconfiger')->get($key) ;
    }

}

/**
 * @author: promise tan
 * @param 进行日志记录
 * @param string $logFileName
 */
if (!function_exists('yolog')) {

    function yolog()
    {
        return app('yologger');
    }

}

/**
 * @author: promise tan
 * @param 进行Curl
 * @param string $logFileName
 */
if (!function_exists('yohttp')) {

    function yohttp()
    {
        return app('yohttper');
    }

}

/**
 * @author: promise tan
 * @param 进行Curl
 * @param string $logFileName
 */
if (!function_exists('yohttp')) {

    function yohttp()
    {
        return app('yohttper');
    }

}

/**
 * @author: promise tan
 * @param 进行转化input output
 * @param string $logFileName
 */
if (!function_exists('tranfer')) {

    function tranfer($origin = '')
    {
        return new \App\Yoona\Packages\Tranfer($origin);
    }

}

/**
 * @author: promise tan
 * @param int $lenght
 * @param string $prefix
 * @return string
 * @throws Exception
 */
if (!function_exists('yoid')) {

    function yoid($lenght = 13, $prefix = 'X')
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($lenght / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
        } else {
            throw new \Exception("no cryptographically secure random function available");
        }
        return $prefix . substr(bin2hex($bytes), 0, $lenght);
    }
}

/**
 * @author: promise tan
 * @funtion 获取面签者的工号
 * @return string
 * @throws Exception
 */
if (!function_exists('workNo')) {

    function workNo()
    {
        if (\App\Util\AdminAuth::check()) {
            return \App\Util\AdminAuth::user()->work_no;
        }
    }

}

/**
 * @author: promise tan
 * @funtion 获取面签者的工号
 * @return string
 * @throws Exception
 */
if (!function_exists('interviewer')) {

    function interviewer()
    {
        if (\App\Util\AdminAuth::check()) {
            return \App\Util\AdminAuth::user();
        }
    }
}

/**
 * @author: promise tan
 * @funtion 获取面签者的工号
 * @return string
 * @throws Exception
 */
if (!function_exists('renderIsCreateCustomerInfo')) {

    function renderIsCreateCustomerInfo($tokenNum, $offset)
    {
        $str = strrev(str_pad(decbin($tokenNum), 3, 0, STR_PAD_LEFT));

        return ((int)substr($str, $offset - 1, 1) === 1) ? '<i class="Pd_ok">已填</i> ' : '<i class="Pd_no">未填</i>';
    }

}

/**
 * @author: promise tan
 * @param $dbv
 * @param $nv
 * @return number
 */
if (!function_exists('tranTokenNum')) {

    function tranTokenNum($dbv, $nv)
    {
        $dbstr = str_pad(decbin($dbv), 3, 0, STR_PAD_LEFT);
        $nvstr = str_pad(decbin($nv), 3, 0, STR_PAD_LEFT);

        return bindec($dbstr | $nvstr);
    }
}

/**
 * @author: promise tan
 * @param $dbv
 * @param $nv
 * @return number
 */
if (!function_exists('tranCertNo')) {

    function tranCertNo($certNo)
    {
        return strlen($certNo) == 15
            ? substr_replace($certNo, "************", 4, 8)
            : (strlen($certNo) == 18
                ? substr_replace($certNo, "**************", 4, 10)
                : "未设置");
    }

}

/**
 * @author: promise tan
 * @param $value
 * @return bool
 */
if (!function_exists('yoempty')) {

    function yoempty($value)
    {
        if (is_null($value)) {
            return true;
        } elseif (is_string($value) && trim($value) === '') {
            return true;
        } elseif ((is_array($value) || $value instanceof Countable) && count($value) < 1) {
            return true;
        } elseif ($value instanceof SplFileInfo) {
            return (string)$value->getPath() == '';
        }

        return false;
    }
}