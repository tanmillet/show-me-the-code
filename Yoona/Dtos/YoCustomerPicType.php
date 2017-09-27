<?php

namespace App\Yoona\Dtos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YoCustomerPicType extends Model
{
    use SoftDeletes;
    //
    protected $table = "yo_customer_pic_type";
    protected $dates = ['deleted_at'];
    protected $guarded = [
        'id',
        'updated_at',
        'created_at'
    ];
}
