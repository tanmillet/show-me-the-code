<?php
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/3
 * Time: 17:44
 */

namespace App\Yoona\Http\Front\Contrs;


use App\Util\AdminAuth;
use App\Yoona\Http\BaseContr;

/**
 *
 * @功能 我的工作区域/面签首页
 *
 * Class LenderContr
 * Author: promise tan
 * @package App\Yoona\Http\Front\Contrs
 */
class InterviewerContr extends BaseContr
{
    /**
     * @author: promise tan
     */
    public function index()
    {
        //模拟登陆  //登录未实行进行模拟登陆
        AdminAuth::attempt(array('email' => 'yoonadmin@bqjieqianme.com', 'password' => 'welcome!bqjr88'));
        $notes = $this->model('YoNotice')->where(['status' => 1])->get();

        return view('Yoona.Front.S.index', compact('notes'));
    }

    /**
     * @author: promise tan
     * @param $noteId
     */
    public function myNote($noteId)
    {
        $note = $this->model('YoNotice')->find($noteId);

        //TODO
    }

}