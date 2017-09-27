<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/11
 * Time: 17:53
 */

namespace App\YoSDK\Http;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

/**
 * Class UserWSDL
 * Author: promise tan
 * @package Http
 */
class UserWSDL extends Controller
{

    /**
     * @author: promise tan
     * @return string
     */
    public function logined()
    {

        $rqs = json_encode(Request::all());
        yolog()->info($rqs, 'yosdk-login');
        return [
            'status' => 'succeed',
            'status_code' => 200,
            'message' => 'logined'
        ];
    }

    /**
     * @author: promise tan
     * @return string
     */
    public function registered()
    {
        $rqs = json_encode(Request::all());
        yolog()->info($rqs, 'yosdk-register');
        return [
            'status' => 'succeed',
            'status_code' => 200,
            'message' => 'registered'
        ];
    }

    /**
     * @author: promise tan
     * @return string
     */
    public function authCode()
    {
        $rqs = json_encode(Request::all());
        yolog()->info($rqs, 'yosdk-code');
        return [
            'status' => 'succeed',
            'status_code' => 200,
            'auth_code' => '12314',
            'message' => 'ok'
        ];
    }
}