<?php

namespace TerryLucas2017\Hasher;

/**
 * Class LucasMD5.
 *
 * User: Terry Lucas
 */
class LucasMD5
{
    /**
     * User: Terry Lucas.
     *
     * @param $value
     * @param array $options
     *
     * @return string
     */
    public function make($value, array $options = [])
    {
        $salt = isset($options['salt']) ? $options['salt'] : '';

        return hash('md5', $value, $salt);
    }

    /**
     * User: Terry Lucas.
     *
     * @param $value
     * @param $hashValue
     * @param array $options
     *
     * @return bool
     */
    public function check($value, $hashValue, array $options = [])
    {
        $salt = isset($options['salt']) ? $options['salt'] : '';

        return hash('md5', $value, $salt) === $hashValue;
    }
}
