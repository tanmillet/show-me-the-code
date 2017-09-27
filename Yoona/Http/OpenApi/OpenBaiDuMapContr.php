<?php

namespace App\Yoona\Http\OpenApi;

use App\Yoona\Http\ApiContr;

/**
 *
 * @service 定位移动web
 *
 * Class OpenBaiDuMapContr
 * Author: promise tan
 * @package App\Yoona\Http\OpenApi
 */
class OpenBaiDuMapContr extends ApiContr
{

    public function vmap()
    {
        return view('Yoona.Map.index');
    }

}