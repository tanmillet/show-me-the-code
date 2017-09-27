<?php

    namespace App;

    use Illuminate\Support\Facades\Cache;

    trait LucasCityTrait
    {

        /**
         * User: Terry Lucas
         * @return array
         */
        public function getProvinces()
        {
            $cacheKey = '__LUCAS_CITY_CACHE_KEY_PROVINCES__';
            $provinces = (env('LUCAS_CITY_CACHE')) ? Cache::get($cacheKey) : NULL;

            if (is_null($provinces)) {
                $provinces = LucasCity::where('province_code', '=', 0)
                    ->orderBy('area_code')
                    ->where('area_type', '=', 0)
                    ->select('area_name', 'area_code')
                    ->get();

                Cache::put($cacheKey, $provinces, 24 * 60);
            }

            return $provinces->toArray();
        }

        /**
         * User: Terry Lucas
         * @param $province
         * @return array
         */
        public function getDowntowns($province)
        {
            $cacheKey = '__LUCAS_CITY_CACHE_KEY_Downtowns__';
            $downtowns = (env('LUCAS_CITY_CACHE')) ? Cache::get($cacheKey) : NULL;

            if (is_null($downtowns)) {
                $downtowns = LucasCity::where('province_code', '=', $province)
                    ->orderBy('area_code')
                    ->where('area_type', '=', 1)
                    ->select('area_name', 'area_code')
                    ->get();

                Cache::put($cacheKey, $downtowns, 24 * 60);
            }

            return $downtowns->toArray();
        }

        /**
         * User: Terry Lucas
         * @param $downtown
         * @return array
         */
        public function getCounty($downtown)
        {
            $cacheKey = '__LUCAS_CITY_CACHE_KEY_Countys__';
            $countys = (env('LUCAS_CITY_CACHE')) ? Cache::get($cacheKey) : NULL;

            if (is_null($countys)) {
                $countys = LucasCity::where('province_code', '=', $downtown)
                    ->orderBy('area_code')
                    ->where('area_type', '=', 2)
                    ->select('area_name', 'area_code')
                    ->get();

                Cache::put($cacheKey, $countys, 24 * 60);
            }

            return $countys->toArray();
        }
    }