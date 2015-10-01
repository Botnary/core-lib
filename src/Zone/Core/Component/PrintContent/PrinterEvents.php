<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * E-mail: my.test@live.ca
 * Date: 10/1/2015
 * Time: 11:10 AM
 */

namespace Zone\Core\Component\PrintContent;


use Symfony\Component\EventDispatcher\Event;

class PrinterEvents extends Event
{
    const ADD_PAGE = 'PRINTER_ADD_PAGE';

    private $printer;

    function __construct($printer)
    {
        $this->printer = $printer;
    }

    /**
     * @return Printer
     */
    public function getPrinter()
    {
        return $this->printer;
    }
}