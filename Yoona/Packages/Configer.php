<?php
namespace App\Yoona\Packages;

/**
 *
 * Created by PhpStorm.
 * User: Promise tan
 *
 * @exmple
 *
 *   dump(yoconf('env')); //获取某个文件全部值
 *   dump(yoconf()->get('env.dbconnection')); //获取某个值
 *   dump(yoconf()->all()); //获取全部的文件值
 *   dump(C()->get('env.dbconnection1' , function (){
 *      return 1+1;
 *   })); //设置默认值 可以具体的值 也可以是一个 Closure
 *
 * Date: 2017/1/3
 * Time: 15:05
 * Class Configer
 * Author: promise tan
 * @package App\Yoona\Packages
 *
 */
class Configer implements \ArrayAccess
{

    /**
     * author: promise tan
     * Date: ${DATE}
     * @var
     */
    protected $path;
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var array
     */
    protected $configs = array();

    /**
     * @author: promise tan
     * Configer constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @author: promise tan
     * @param $key
     * @param string $default
     * @return array|mixed|string
     */
    public function get($key, $default = '')
    {
        $array = $this->all();

        if (is_null($key)) {
            return $array;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default instanceof \Closure ? $default() : $default;
            }
        }

        return $array;
    }

    /**
     * @author: promise tan
     * @return array
     */
    public function all()
    {
        foreach (glob(app_path('Yoona/Configs') . '/*.php') as $file) {
            $key = basename($file, '.php');
            $file_path = app_path() . str_replace('\\', '/', '\\Yoona\\Configs\\' . basename($file, '.php')) . '.php';
            if (file_exists($file_path)) {
                $config = require $file_path;
                $this->configs[$key] = $config;
            }
        }

        return $this->configs;
    }

    /**
     * @author: promise tan
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        if (empty($this->configs[$key])) {
            $file_path = $this->path . '/' . $key . '.php';
            if (file_exists($file_path)) {
                $config = require $file_path;
                $this->configs[$key] = $config;
            }
        }
        return $this->configs[$key];
    }

    /**
     * @author: promise tan
     * @param mixed $key
     * @param mixed $value
     * @throws \Exception
     */
    public function offsetSet($key, $value)
    {
        throw new \Exception("cannot write config file.");
    }

    /**
     * @author: promise tan
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->configs[$key]);
    }

    /**
     * @author: promise tan
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        unset($this->configs[$key]);
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param  \ArrayAccess|array $array
     * @param  string|int $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param  mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }
}