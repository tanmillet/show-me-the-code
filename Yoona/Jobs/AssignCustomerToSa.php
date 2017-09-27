<?php

namespace App\Yoona\Jobs;
/**
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/16
 * Time: 15:32
 */
/**
 * Class AssignCustomerToSa
 * Author: promise tan
 * @package App\Yoona\Jobs
 */
class AssignCustomerToSa extends Job
{
    /**
     * author: promise tan
     * Date: ${DATE}
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $ctr;

    /**
     * author: promise tan
     * Date: ${DATE}
     * @var
     */
    protected $userKey;

    /**
     * ConveyCoreTrialAuth constructor.
     * @param $params
     */
    public function __construct($userKey)
    {
        $this->userKey = $userKey;
        $this->ctr = app('App\Yoona\Http\OpenApi\UpCoreApiContr');
    }

    /**
     * 运行任务。
     *
     * @return void
     */
    public function handle()
    {
        $this->ctr->assignCtoS($this->userKey);
    }

    /**
     * 处理一个失败的任务
     *
     * @return void
     */
    public function failed()
    {
        // Called when the job is failing...
        yolog()->info('默认分配客户信息', 'core');
    }
}