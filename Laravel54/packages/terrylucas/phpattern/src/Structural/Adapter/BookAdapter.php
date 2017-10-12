<?php
/**
 * Created by PhpStorm.
 * User: Terry Lucas
 * Date: 2017/8/17
 * Time: 17:15
 */

namespace TerryLucas2017\Pattern\Structural\Adapter;


/**
 * Class BookAdapter
 * User: Terry Lucas
 * @package TerryLucas2017\Pattern\Structural\Adapter
 */
class BookAdapter implements IBook
{
    /**
     * User: Terry Lucas
     * Date: ${DATE}
     * @var IEbook
     */
    protected $ebook;

    /**
     * @author: Terry Lucas
     * BookAdapter constructor.
     * @param IEbook $iebook
     */
    public function __construct(IEbook $iebook)
    {
        $this->ebook = $iebook;
    }

    /**
     * User: Terry Lucas
     */
    public function open()
    {
        // TODO: Implement open() method.
        $this->ebook->unlock();
    }

    /**
     * User: Terry Lucas
     */
    public function turnPage()
    {
        // TODO: Implement turnPage() method.
        $this->ebook->nextPage();
    }

    /**
     * User: Terry Lucas
     * @return int
     */
    public function getPage(): int
    {
        // TODO: Implement getPage() method.
        $this->ebook->getPage();
    }
}