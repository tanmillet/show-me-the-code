<?php

namespace TerryLucas2017\Pattern\Structural\Bridge;

/**
 * Class HtmlFormat
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Bridge
 */
class TextFormat implements IFormatter
{
    /**
     * User: Terry Lucas
     * @param string $text
     * @return string
     */
    public function format(string $text)
    {
        return $text;
    }
}