<?php

    namespace App\Http\Controllers;

    use App\LucasCityTrait;
    use Illuminate\Http\Request;

    /**
     * Class LucasCityController
     * User: Terry Lucas
     * @package App\Http\Controllers
     */
    class LucasCityController extends Controller
    {
        use LucasCityTrait;

        /**
         * @author: Terry Lucas
         * LucasCityController constructor.
         */
        public function __construct()
        {
        }

        /**
         * User: Terry Lucas
         * @return mixed
         */
        public function provinces()
        {
            return $this->getProvinces();
        }

        /**
         * User: Terry Lucas
         * @param $province
         * @return array
         */
        public function downtowns($province)
        {
            return $this->getDowntowns($province);
        }

        /**
         * User: Terry Lucas
         * @param $downtown
         * @return array
         */
        public function countys($downtown)
        {
            return $this->getCounty($downtown);
        }
    }
