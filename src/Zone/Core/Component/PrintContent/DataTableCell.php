<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * E-mail: my.test@live.ca
 * Date: 10/5/2015
 * Time: 11:00 AM
 */

namespace Zone\Core\Component\PrintContent;


class DataTableCell
{
    private $width;
    private $height;
    private $position;
    private $text;

    /**
     * DataTableCell constructor.
     * @param $width
     * @param $height
     * @param $position
     * @param $text
     */
    public function __construct($text, $width = null, $height = null, $position = 'top')
    {
        $this->width = $width;
        $this->height = $height;
        $this->position = $position;
        $this->text = $text;
    }

    function compile()
    {
        $width = $this->width ? 'width="' . $this->width . '"' : '';
        $height = $this->height ? 'width="' . $this->height . '"' : '';
        return sprintf('<td %s %s valign="%s">%s</td>', $width, $height, $this->position, $this->text);
    }
}