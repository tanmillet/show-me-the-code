<?php

namespace App\Yoona\Requests;

/**
 * Class UpApplicantInfoRequest
 * Author: promise tan
 * @package App\Yoona\Requests
 */
class UpCompanyValidator extends FormRequest
{
    /**
     * @return array
     */
    public function getValidateRules()
    {
        return
            [
                'company_name' => 'required',
            ];
    }

    /**
     * @author: promise tan
     * @return array
     */
    public function getValidateReturnMsg()
    {
        return
            [
                'company_name' => '单位名称',
            ];
    }

}