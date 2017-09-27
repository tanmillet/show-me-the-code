<?php
namespace App\Yoona\Http;

use App\Http\Controllers\Controller;
use App\Yoona\Traits\YoBasicTrait;

/**
 * Class BaseContr
 * Author: promise tan
 * @package App\Yoona\Http
 */
class BaseContr extends Controller
{
    use YoBasicTrait;
    /**
     * @author: promise tan
     * BaseContr constructor.
     */
    public function __construct()
    {
    	checkLogined();
    }
}