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
    private $useBorders;

    /**
     * @return DataTableCell
     */
    public static function create()
    {
        $instance = new self();
        return $instance;
    }

    function compile()
    {
        $width = $this->width ? 'width="' . $this->width . '"' : '';
        $height = $this->height ? 'width="' . $this->height . '"' : '';
        $style = $this->getUseBorders() ? 'style="border: solid 1px;"' : '';
        return sprintf('<td %s %s valign="%s" align="%s" %s>%s</td>', $width, $height, $this->position, $this->align, $style, $this->text);
    }

    /**
     * @return null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param null $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return null
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param null $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * @param mixed $align
     */
    public function setAlign($align)
    {
        $this->align = $align;
    }

    /**
     * @return mixed
     */
    public function getUseBorders()
    {
        return $this->useBorders;
    }

    /**
     * @param mixed $useBorders
     */
    public function setUseBorders($useBorders)
    {
        $this->useBorders = $useBorders;
    }

}