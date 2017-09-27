<?php
namespace App\YoSDK\Packages;

use Illuminate\Log\Writer;
use Illuminate\Support\Facades\Log;

/**
 * Class Logger
 * Author: promise tan
 * @package App\Yoona\Packages
 */
class Logger
{
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var
     */
    private $log;

    /**
     * @author: promise tan
     * @param $method
     * @return bool
     */
    public function useDailyFiles($method)
    {
        $dirpath = storage_path() . '/logs/' . date('Y-m-d', time());
        if (!file_exists($dirpath)) {
            $old_mask = umask(0);
            if (!mkdir($dirpath, 02770, true)) {
                Log::info("创建" . date('Y-m-d', time()) . "日志目录失败");
                return FALSE;
            }
            umask($old_mask);
        }
        $this->log = new Writer(new \Monolog\Logger($method));
        $this->log->useDailyFiles($dirpath . '/laravel-' . php_sapi_name() . '-' . $method . '.log', 30);
    }

    /**
     * @author: promise tan
     * @param $message
     * @param string $type
     * @param array $context
     */
    public function error($message, $type = '', array $context = [])
    {
        if (!empty($type)) {
            $this->useDailyFiles($type . '-' . __FUNCTION__);
        } else {
            $this->useDailyFiles(__FUNCTION__);
        }
        $this->log->write(__FUNCTION__, $message, $context);
    }

    /**
     * @author: promise tan
     * @param $message
     * @param string $type
     * @param array $context
     */
    public function info_record($message, $type = '', array $context = [])
    {
        $this->useDailyFiles($type . '-info-record');
        $this->log->write('info', $message, $context);
    }

    /**
     * @author: promise tan
     * @param $message
     * @param string $type
     * @param array $context
     */
    public function error_record($message, $type = '', array $context = [])
    {
        $this->useDailyFiles($type . '-error-record');
        $this->log->write('error', $message, $context);
    }

    /**
     * @author: promise tan
     * @param $message
     * @param string $type
     * @param array $context
     */
    public function info($message, $type = '', array $context = [])
    {
        if (!empty($type)) {
            $this->useDailyFiles($type . '-' . __FUNCTION__);
        } else {
            $this->useDailyFiles(__FUNCTION__);
        }
        $this->log->write(__FUNCTION__, $message, $context);

    }

    /**
     * @author: promise tan
     * @param $message
     * @param string $type
     * @param array $context
     */
    public function debug($message, $type = '', array $context = [])
    {
        if (!empty($type)) {
            $this->useDailyFiles($type . '-' . __FUNCTION__);
        } else {
            $this->useDailyFiles(__FUNCTION__);
        }
        $this->log->write(__FUNCTION__, $message, $context);
    }

}