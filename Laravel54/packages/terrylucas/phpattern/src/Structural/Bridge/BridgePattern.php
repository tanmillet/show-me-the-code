<?php

namespace TerryLucas2017\Pattern\Structural\Bridge;

/**
 * Class BridgePattern
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Bridge
 */
class BridgePattern
{
    /**
     * User: Terry Lucas
     */
    public function exc()
    {
        $helloService = new HelloWorldService(new HtmlFormat());
        $str = $helloService->get();

        echo $str.PHP_EOL;

        $helloService = new HelloWorldService(new TextFormat());
        $str = $helloService->get();

        echo $str.PHP_EOL;
    }
}