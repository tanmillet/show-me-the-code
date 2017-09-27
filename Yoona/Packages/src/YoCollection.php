<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/22
 * Time: 10:58
 */

namespace App\Yoona\Packages\src;

use App\Yoona\Packages\HOCProxyer;
use \Illuminate\Support\Collection;

/**
 * Class YoCollection
 * Author: promise tan
 * @package App\Yoona\Packages\src
 */
class YoCollection extends Collection
{

    /**
     * The methods that can be proxied.
     *
     * @var array
     */
    protected static $proxies = [
        'contains', 'each', 'every', 'filter', 'first', 'map',
        'partition', 'reject', 'sortBy', 'sortByDesc', 'sum',
    ];

    /**
     * @author: promise tan
     * YoCollection constructor.
     * @param array $items
     */
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * 添加一个自定义的代理的方法
     *
     * @param  string $method
     * @return void
     */
    public static function proxy($method)
    {
        static::$proxies[] = $method;
    }

    /**
     * 可代理的方法
     *
     * @param  string $key
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key)
    {
        if (!in_array($key, static::$proxies)) {
            throw new \Exception("Property [{$key}] does not exist on this collection instance.");
        }

        return new HOCProxyer($this, $key);
    }
}