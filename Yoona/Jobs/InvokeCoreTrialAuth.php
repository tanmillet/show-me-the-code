<?php

namespace App\Yoona\Jobs;

/**
 * Class ConveyCoreTrialAuth
 * @package App\Jobs
 */

/**
 * Class SendReducerate
 * @package App\Jobs
 */
class InvokeCoreTrialAuth extends Job
{

    protected $ctr;

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
        $this->ctr->aduitAuth($this->userKey);
    }

    /**
     * 处理一个失败的任务
     *
     * @return void
     */
    public function failed()
    {
        // Called when the job is failing...
        yolog()->info('传达预审接口信息', 'core');
    }

}
