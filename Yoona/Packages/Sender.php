<?php
namespace App\Yoona\Packages;

use Illuminate\Support\Facades\Mail;

/**
 *
 * 功能描述：对于发送者的功能是 发送邮件 发送短信等发送功能
 *
 * Created by PhpStorm.
 * User: Promise tan
 * Date: 2017/1/4
 * Time: 9:31
 */
class Sender
{
    /**
     *
     * @author 储奇
     * @example yosend()->sendEmail(['prm1'=>'xxx'],'mail');
     * @param array $viewData 需要传递给view的数据
     * @param string $cfg 邮件基础配置模板名称
     * @return $this
     *
     */
    public function sendEmail(array $viewData, $cfg)
    {
        $mailPrm = yoconf('env')[$cfg];
        $flag = Mail::send($mailPrm['view'], $viewData, function ($message) use ($mailPrm) {
            $message->from($mailPrm['from'])->sender($mailPrm['sender'])->to($mailPrm['to'])->subject($mailPrm['subject']);
            if (is_array($mailPrm['cc']) && !empty($mailPrm['cc'])) {
                // 添加多个抄送
                foreach ($mailPrm['cc'] as $ccAddr) {
                    $message->addTo($ccAddr);
                }
            }
            if (isset($mailPrm['attachFilePath'], $mailPrm['attachFileName'], $mailPrm['attachFileMime'])) {
                // 添加附件文件
                $message->attach($mailPrm['attachFilePath'], ['as' => $mailPrm['attachFileName'], 'mime' => $mailPrm['attachFileMime']]);
            }
        });
        ($flag) ? yolog()->info('邮件发送成功', 'email') : yolog()->error('发送失败', 'email');

        return $this;
    }

    /**
     * @author 储奇
     */
    public function sendPhoneMessage()
    {

    }
}