<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.23
 * Time 11:16
 */

namespace TerryLucas2017\Pattern\Structural\Composite;

/**
 * Class TextElement
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Composite
 */
class TextElement implements IRenderable
{
    /**
     * @author Terry Lucas
     * @var string
     */
    private $_text;

    /**
     * @author Terry Lucas
     * TextElement constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->_text = $text;
    }

    /**
     * @author Terry Lucas
     * @return string
     */
    public function reader(): string
    {
        return $this->_text;
    }

}