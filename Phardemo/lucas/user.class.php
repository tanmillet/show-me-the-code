<?php
/**
 * Created by PhpStorm.
 * Author Terry Lucas
 * Date 17.11.30
 * Time 10:45
 */
class user
{
    /**
     * @author Terry Lucas
     * @var string
     */
    private $name = 'terry lucas';

    /**
     * @author Terry Lucas
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @author Terry Lucas
     */
    public function introduce()
    {
        echo "My name is $this->name !";
    }

}