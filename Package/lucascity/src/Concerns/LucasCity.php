<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    /**
     * Class LucasCity
     * User: Terry Lucas
     * @package App
     */
    class LucasCity extends Model
    {
        //
        /**
         * User: Terry Lucas
         * Date: ${DATE}
         * @var string
         */
        protected $table = 'area_map';

        /**
         * User: Terry Lucas
         * Date: ${DATE}
         * @var array
         */
        protected $fillable = [
            'area_code',
            'area_name',
            'area_type',
            'province_code',
            'area_name',
        ];

        /**
         * User: Terry Lucas
         * Date: ${DATE}
         * @var array
         */
        protected $hidden = [
            'id',
        ];
    }
