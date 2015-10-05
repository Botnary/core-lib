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
    private $isTh;

    /**
     * DataTableCell constructor.
     */
    public function __construct()
    {
        $this->setUseBorders(false);
        $this->setAlign('left');
        $this->setPosition('top');
    }


    /**
     * @param $text
     * @return DataTableCell
     */
    public static function create($text)
    {
        $instance = new self();
        $instance->setText($text);
        return $instance;
    }

    function compile()
    {
        $width = $this->width ? 'width="' . $this->width . '"' : '';
        $height = $this->height ? 'width="' . $this->height . '"' : '';
        $style = '';
        if($this->getIsTh()) {
            return sprintf('<th %s %s valign="%s" align="%s" %s>%s</th>', $width, $height, $this->position, $this->align, $style, $this->text);
        }else{
            return sprintf('<td %s %s valign="%s" align="%s" %s>%s</td>', $width, $height, $this->position, $this->align, $style, $this->text);
        }
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
     * @return DataTableCell
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
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
     * @return DataTableCell
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
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
     * @return DataTableCell
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
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
     * @return DataTableCell
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
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
     * @return DataTableCell
     */
    public function setAlign($align)
    {
        $this->align = $align;
        return $this;
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
     * @return DataTableCell
     */
    public function setUseBorders($useBorders)
    {
        $this->useBorders = $useBorders;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsTh()
    {
        return $this->isTh;
    }

    /**
     * @param mixed $isTh
     * @return DataTableCell
     */
    public function setIsTh($isTh)
    {
        $this->isTh = $isTh;
        return $this;
    }
}