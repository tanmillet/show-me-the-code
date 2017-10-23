<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.10.23
 * Time 11:18
 */

namespace TerryLucas2017\Pattern\Structural\Composite;


/**
 * Class FormElement
 * Author Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Composite
 */
class FormElement implements IRenderable
{
    /**
     * @author Terry Lucas
     * @var
     */
    private $_elements;

    /**
     * @author Terry Lucas
     * @return string
     */
    public function reader(): string
    {
        if (!isset($this->_elements)) {
            return '';
        }

        $elementStrings = '<form>';

        foreach ($this->_elements as $element) {
            $elementStrings .= $element->reader();
        }

        $elementStrings .= '</form>';

        return $elementStrings;
    }

    /**
     * @author Terry Lucas
     * @param IRenderable $irenderable
     */
    public function addElements(IRenderable $irenderable)
    {
        $this->_elements[] = $irenderable;
    }

}