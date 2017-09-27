<?php

/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/22
 * Time: 11:02
 */

namespace App\Yoona\Packages;

use App\Yoona\Packages\src\YoCollection;

/**
 * Class HOCProxyer
 * Author: promise tan
 * @package App\Yoona\Packages
 */
class HOCProxyer
{
    /**
     * The collection being operated on.
     *
     * @var YoCollection $collection
     */
    protected $collection;

    /**
     * The method being proxied.
     *
     * @var string
     */
    protected $method;

    /**
     *
     * @funtion 进行代理实例化
     * @author: promise tan
     * HOCProxyer constructor.
     * @param YoCollection $collection 自定义的Collection类型
     * @param $method 代理方法
     */
    public function __construct(YoCollection $collection, $method)
    {
        $this->method = $method;
        $this->collection = $collection;
    }

    /**
     * 代理访问一个属性在集合内。
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->collection->{$this->method}(function ($value) use ($key) {
            return is_array($value) ? $value[$key] : $value->{$key};
        });
    }

    /**
     *代理一个方法调用在集合内。
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->collection->{$this->method}(function ($value) use ($method, $parameters) {
            return $value->{$method}(...$parameters);
        });
    }
}