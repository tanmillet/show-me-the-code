<?php

namespace App\Yoona\Http\Admin\Contrs;

/**
 * Class ApiContr
 * @package App\AppSwx\Http\ApiControllers
 */
use App\Util\AdminAuth;
use App\Yoona\Http\BaseContr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class ProjectAdminContr
 * @package App\AppOpenApi\Http\Controllers
 */
class ProjectAdminContr extends BaseContr
{
    /**
     * @author: promise tan
     * @return mixed
     */
    public function login()
    {
        if (Request::isMethod('POST')) {

            Session::forget('loginUserName');
            Session::forget('loginUserPosition');
            Session::forget('loginedTag');

            $userLockCode = Request::get('lockCode');
            if (empty($userLockCode)) {
                return Redirect::to('/yo/unlock');
            }
            $lockCodes = collect($this->getUserLockCode());
            $res = $lockCodes->map(function ($lockCode, $index) use ($userLockCode) {
                if ($userLockCode == $index) {
                    return $lockCode;
                }
            })->reject(function ($lockCode) {
                return empty($lockCode);
            })->flatten()->toArray();

            if (empty($res) || !isset($res[0]) || !isset($res[1])) {
                return Redirect::to('/yo/unlock');
            }

            Session::put('loginedTag', base64_encode(json_encode($res)));
            Session::put('loginUserName', $res[0]);
            Session::put('loginUserPosition', $res[1]);

            return Redirect::to('/yo/projects');
        }

        return view('Yoona.unlock');
    }

    /**
     * @author: promise tan
     * @return mixed
     */
    public function logout()
    {
        Session::forget('loginUserName');
        Session::forget('loginUserPosition');
        Session::forget('loginedTag');

        return Redirect::to('/yo/unlock');
    }

    /**
     * @author: promise tan
     * @return mixed
     */
    public function projects()
    {
        if (empty(AdminAuth::user())) {
            if (empty(Session::get('loginUserName'))) {
                return Redirect::to('/yo/unlock');
            }
        }
        return view('Yoona.Admin.main');
    }

    /**
     * @author: promise tan
     * @return array
     */
    protected function getUserLockCode()
    {
        return
            [
                'mNMXYT4aWLv16W6q' => [
                    'userName' => '许雄',
                    'position' => '测试负责',
                ],
                'ABWdk33sXY0xlGZ8' => [
                    'userName' => '欧树权',
                    'position' => '开发负责',
                ],
                'gjd123456' => [
                    'userName' => '郭建东',
                    'position' => '开发负责',
                ],
                'VHI42q633aqA6P4m' => [
                    'userName' => '谭重涛',
                    'position' => '项目负责',
                ],
                'CGrxoYfIxKE8KcKn' => [
                    'userName' => '李莹',
                    'position' => '产品负责',
                ],
                'rU5aWlxyLoAz8xbt' => [
                    'userName' => '李烁',
                    'position' => '产品负责',
                ],
                'WVufM0ezEeJmeBxa' => [
                    'userName' => '温林青',
                    'position' => '前端负责',
                ],
                'WcqfM0ezEetzxBxa' => [
                    'userName' => '储奇',
                    'position' => '开发负责',
                ],
            ];
    }
}