<?php

/**
 * Class LucasMD5.
 *
 * User: Terry Lucas
 */
class LucasMD5Test extends \PHPUnit\Framework\TestCase
{
    /**
     * User: Terry Lucas.
     *
     * Date: ${DATE}
     *
     * @var
     */
    public $hasher;

    /**
     * User: Terry Lucas.
     */
    public function setUp()
    {
        $this->hasher = new \TerryLucas2017\Hasher\LucasMD5();
    }

    /**
     * User: Terry Lucas.
     */
    public function testHasherMake()
    {
        $one = md5('123456');
        $two = $this->hasher->make('123456');

        $this->assertEquals($one, $two);
    }

    /**
     * User: Terry Lucas.
     */
    public function testHasherMakeWithSalt()
    {
        $two = $this->hasher->make('123456', ['salt' => 'lucas']);
        $one = md5('123456', 'lucas');

        $this->assertEquals($one, $two);
    }

    /**
     * User: Terry Lucas.
     */
    public function testHasherChect()
    {
        $hasherValue = md5('123456');
        $res = $this->hasher->check('123456', $hasherValue);

        $this->assertTrue($res, 'hasher value check is true');
    }
}
