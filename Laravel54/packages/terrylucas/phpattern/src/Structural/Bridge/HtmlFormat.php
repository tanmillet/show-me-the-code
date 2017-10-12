<?php

namespace TerryLucas2017\Pattern\Structural\Bridge;

/**
 * Class HtmlFormat
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Bridge
 */
class HtmlFormat implements IFormatter
{
    /**
     * User: Terry Lucas
     * @param string $text
     * @return string
     */
    public function format(string $text)
    {
        return '<p>'.$text.'</p>';
    }
}