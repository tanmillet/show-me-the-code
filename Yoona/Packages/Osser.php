<?php
namespace App\Yoona\Packages;

use OSS\OssClient;
use OSS\Core\OssException;

/**
 * Created by PhpStorm.
 * User: promise - tan
 * Date: 2017/1/17
 * Time: 17:44
 */
final class Osser
{

    // 静态成员变量，用来保存类的唯一实例
    private static $_instance;

    // 用private修饰构造函数，防止外部程序来使用new关键字实例化这个类
    private function __construct()
    {
    }

    // 覆盖php魔术方法__clone()，防止克隆
    private function __clone()
    {
        trigger_error('Clone is not allow', E_USER_ERROR);
    }

    // 单例方法，返回类唯一实例的一个引用
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 外网访问地址
     * @return mixed
     */
    static public function getDomainPath()
    {
        return config('ossconfig.OSS_ENDPOINT_OUTER');
    }

    /**
     * 图片访问路径
     * @param $path
     */
    static public function visitPath($path)
    {
        return config('ossconfig.OSS_VISIT_JIEQIANME') . '/' . trim($path, '/');
    }


    static public function serverPath($path)
    {
        return config('ossconfig.OSS_VISIT_SERVER') . '/' . trim($path, '/');
    }

    /**
     * 获取OSS配置项，如果传入了$key则只返回键为$key的项，否则返回全部配置项。
     * @param null $key
     * @return array|string
     */
    private static function getConfig($key = null)
    {
        $cfg = [
            'id' => config('ossconfig.OSS_ACCESS_ID'),
            'secret' => config('ossconfig.OSS_ACCESS_SECRET'),
            'endpoint' => config('ossconfig.OSS_ENDPOINT_OUTER'),
            'bucket' => config('ossconfig.OSS_BUCKET'),
        ];
        return (null === $key) ? $cfg : (in_array($key, array_keys($cfg), true) ? $cfg[$key] : '');
    }

    /**
     * 根据Config配置，得到一个OssClient实例
     * @return null|OssClient 返回OssClient实例或者null
     */
    public static function getOssClient()
    {
        try {
            $cfg = self::getConfig();
            $ossClient = new OssClient($cfg['id'], $cfg['secret'], $cfg['endpoint'], false);
            return $ossClient;
        } catch (OssException $e) {
            $record = [
                'msg' => '获取OssClient实例时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            yolog()->info($record['msg'], $record['fileName']);
            return null;
        }
    }

    /**
     * 上传文件时，生成文件名（包含路径和扩展名）
     * @param array $mycfg 举例：$mycfg = ['folder'=>'upload', 'fileName' => 'unixtime_userid.jpg']
     * @return string
     */
    private static function createObjName(array $mycfg = [])
    {
        // 图片默认配置
        $imgConfig = [
            'folder' => 'common',
            'fileName' => md5(str_random(16) . time()) . '.jpg',
        ];
        $imgConfig = array_merge($imgConfig, $mycfg);
        $res = trim($imgConfig['folder'], '/') . '/' . $imgConfig['fileName'];
        return $res;
    }

    /**
     * 上传文件（通过文件内容字符串方式）
     * @param   string $content 要上传的文件的内容字符串，例如 $content = file_get_contents(storage_path().'/118.jpg')
     * @param   array $mycfg 自定义配置，例如 $mycfg = ['folder'=>'upload', 'fileName' => 'unixtime_userid.jpg']
     * @return  array            传入上述参数，返回值类似于 ['success' => true, 'data' => 'upload/20160825/unixtime_userid.jpg', 'msg' => '上传成功！']
     */
    public static function uploadFileByContent($content, array $mycfg = [])
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return ['success' => false, 'data' => '', 'msg' => '配置有误，获取OssClient实例失败！'];
        }
        $file = self::createObjName($mycfg);
        yolog()->info('上传文件' . $file, 'oss_upload');
        try {
            $res = $ossClient->putObject($bucket, $file, $content);
            return $res ? ['success' => true, 'data' => '/' . $file, 'msg' => '上传成功！', 'visit_path' => self::visitPath($file)] : ['success' => false, 'data' => '', 'msg' => '上传失败！'];
        } catch (OssException $e) {
            $record = [
                'msg' => '上传文件时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            yolog()->info($record['msg'], $record['fileName']);
            return ['success' => false, 'data' => '', 'msg' => '上传文件时出现异常，上传失败！'];
        }
    }

    /**
     * 判断文件是否存在
     * @param $filename
     * @return array|bool
     */
    static public function doesFileExist($filename)
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
        try {
            $exist = $ossClient->doesObjectExist($bucket, trim($filename, '/'));
            return $exist;
        } catch (OssException $e) {
            $record = [
                'msg' => '判断文件是否存在时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
            yolog()->info($record['msg'], $record['fileName']);
            return false;
        }
    }

    /**
     * 删除单个文件
     * @param $filename
     * @return array
     */
    static public function deleteFile($filename)
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
         yolog()->info('删除文件：' . $filename, 'oss_delete');
        $result = self::doesFileExist($filename);
        if ($result) {
            try {
                $result = $ossClient->deleteObject($bucket, trim($filename, '/'));
                 yolog()->info(trim($filename, '/') . '-文件删除成功！', 'oss_delete');
                return $result ? array('success' => true, 'data' => '', 'msg' => '删除成功！') : array('success' => false, 'data' => '', 'msg' => '删除失败！');
            } catch (OssException $e) {
                $record = [
                    'msg' => '删除文件时出现异常！异常信息为：' . $e->getMessage(),
                    'fileName' => 'oss-exception'
                ];
                 yolog()->info($record['msg'], $record['fileName']);
                return ['success' => false, 'data' => '', 'msg' => '删除文件时出现异常，删除失败！'];
            }
        } else {
            return array('success' => false, 'data' => '', 'msg' => '文件不存在，删除失败！');
        }
    }


    /**
     * 以追加的方式上传文件
     * @link https://help.aliyun.com/document_detail/31851.html?spm=5176.87240.400427.15.nRSBOJ
     * @param string $content
     * @param array $mycfg
     * @return bool|int
     */
    public static function appendToFile($content, array $mycfg = [])
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return false;
        }
        $file = self::createObjName($mycfg);
        $judge = self::isAppendable($file);
        $position = $judge['available'] ? $judge['position'] : null;
        if (null === $position) {
            return false;
        }
        try {
            return $ossClient->appendObject($bucket, $file, $content, $position);
        } catch (OssException $e) {
            $record = [
                'msg' => '追加内容到文件时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
             yolog()->info($record['msg'], $record['fileName']);
            return false;
        }
    }


    /**
     * 判断文件是否可以被追加内容。若能追加，返回下次追加的位置。
     * @link https://help.aliyun.com/document_detail/31851.html?spm=5176.87240.400427.15.Lovo0M
     * @param string $file 文件名（包含路径和文件名），类似于 $file = 'append/test.json'
     * @return array
     */
    public static function isAppendable($file)
    {
        if (!self::doesFileExist($file)) {
            return ['available' => true, 'position' => 0];
        }
        $info = self::getOssFileInfo($file);
        if (isset($info['data']['x-oss-object-type']) && ($info['data']['x-oss-object-type'] === 'Appendable')) {
            return ['available' => true, 'position' => $info['data']['x-oss-next-append-position']];
        }
        return ['available' => false];
    }


    /**
     * 获取文件信息
     * @link https://github.com/aliyun/aliyun-oss-php-sdk/blob/master/samples/Object.php?spm=5176.doc32104.2.1.BlyzKk
     * @param string $file 文件名（包含路径和文件名），类似于 $file = 'append/test.json'
     * @return array
     */
    public static function getOssFileInfo($file)
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return ['success' => false, 'data' => [], 'msg' => '配置有误，获取OssClient实例失败！'];
        }
        try {
            $data = $ossClient->getObjectMeta($bucket, trim($file, '/'));
            return ['success' => true, 'data' => $data, 'msg' => '成功获取文件信息！'];
        } catch (OssException $e) {
            $record = [
                'msg' => '获取文件信息时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
             yolog()->info($record['msg'], $record['fileName']);
            return ['success' => false, 'data' => [], 'msg' => '获取文件信息时出现异常！'];
        }
    }

    /**
     * 上传本地文件
     * @param $filename
     * @param array $myConfig
     * @return array
     */
    static public function uploadLocalFile($filename, $myConfig = array())
    {
        $bucket = self::getConfig('bucket');
        $ossClient = self::getOssClient();
        if (!$ossClient) {
            return ['success' => false, 'data' => '', 'msg' => '配置有误，获取OssClient实例失败！'];
        }
        $file = self::createObjName($myConfig);
         yolog()->info('上传文件' . $file, 'oss_upload');
        try {
            $res = $ossClient->uploadFile($bucket, $file, $filename);
            return $res ? ['success' => true, 'data' => '/' . $file, 'msg' => '上传成功！', 'visit_path' => self::visitPath($file)] : ['success' => false, 'data' => '', 'msg' => '上传失败！'];
        } catch (OssException $e) {
            $record = [
                'msg' => '上传文件时出现异常！异常信息为：' . $e->getMessage(),
                'fileName' => 'oss-exception'
            ];
             yolog()->info($record['msg'], $record['fileName']);
            return ['success' => false, 'data' => '', 'msg' => '上传文件时出现异常，上传失败！'];
        }
    }
}