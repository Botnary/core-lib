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
    private $align;

    /**
     * DataTableCell constructor.
     * @param $text
     * @param $width
     * @param $height
     * @param string $align
     * @param string $position
     */
    public function __construct($text, $width = null, $height = null, $align = 'left', $position = 'top')
    {
        $this->width = $width;
        $this->height = $height;
        $this->position = $position;
        $this->text = $text;
        $this->setAlign($align);
        return $this;
    }

    function compile()
    {
        $width = $this->width ? 'width="' . $this->width . '"' : '';
        $height = $this->height ? 'width="' . $this->height . '"' : '';
        return sprintf('<td %s %s valign="%s" align="%s">%s</td>', $width, $height, $this->position, $this->align, $this->text);
    }

    /**
     * @param mixed $align
     */
    public function setAlign($align)
    {
        $this->align = $align;
    }
}