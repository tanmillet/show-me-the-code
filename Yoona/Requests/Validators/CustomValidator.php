<?php

namespace App\Yoona\Requests\Validators;

use Illuminate\Validation\Validator;

class CustomValidator extends Validator
{

    /**
     * 校验联系电话是否正确 测试使用
     */
    // public function validatePhone2343($attribute, $value, $parameters)
    // {
    //     dump($attribute);
    //     dump($parameters);
    //     return ($value == 123) ? true : false;
    // }

    /**
     * 校验联系电话是否正确
     */
    public function validatePhone($attribute, $value, $parameters)
    {
        $pattern1 = "/^1[3|4|5|7|8]\d{9}$/"; //手机号
        $pattern2 = "/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/";//固话
        return preg_match($pattern1, $value) || preg_match($pattern2, $value) ? true : false;
    }

    /**
     * 校验身份证号格式是否正确
     */
    public function validateIdnumber($attribute, $value, $parameters)
    {
        $pattern = "/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/";
        if (!preg_match($pattern, $value)) {
            return false;
        }

        $len = strlen($value);
        if ($len == 18) {
            $a = str_split(strtoupper($value), 1); //转换成大写，以避免身份证最后一位x是小写时校验出错
            $w = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

            $c = array(1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2);

            $sum = 0;

            for ($i = 0; $i < 17; $i++) {
                $sum += $a[$i] * $w[$i];
            }

            $r = $sum % 11;
            $res = $c[$r];

            if ($res == $a[17]) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * 使用正则表达式校验手机号码
     */
    public function validateMobilePhone($attribute, $value, $parameters)
    {
        $pattern = "/^1[3|4|5|7|8]\d{9}$/";
        return preg_match($pattern, $value);
    }

}