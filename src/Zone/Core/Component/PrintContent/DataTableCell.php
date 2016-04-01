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
    private $colSpan;
    private $rowSpan;
    private $isHidden;
    private $textColor;

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
        $style = 'style="';
        $height = $this->height ? 'width="' . $this->height . '"' : '';
        $style .= $this->getUseBorders() ? 'border: 0.1mm solid #000000;' : '';
        $style .= $this->width ? 'width:' . $this->width . ';' : '';
        $style .= $this->height ? 'height:' . $this->height . ';' : '';
        $style .= 'color:' . $this->getTextColor() . ';';
        $colSpan = $this->colSpan > 0 ? sprintf('colspan="%d"', $this->colSpan) : '';
        $rowSpan = $this->rowSpan > 0 ? sprintf('rowspan="%d"', $this->rowSpan) : '';
        $style .= '"';
        if ($this->getIsTh()) {
            $cell = sprintf('<th %s %s %s valign="%s" align="%s" %s>%s</th>', $rowSpan, $colSpan, $height, $this->position, $this->align, $style, $this->text);
        } else {
            $cell = sprintf('<td %s %s %s valign="%s" align="%s" %s>%s</td>', $rowSpan, $colSpan, $height, $this->position, $this->align, $style, $this->text);
        }
        if ($this->isHidden) {
            $cell = '';
        }
        return $cell;
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

    /**
     * @return mixed
     */
    public function getColSpan()
    {
        return $this->colSpan;
    }

    /**
     * @param mixed $colSpan
     * @return DataTableCell
     */
    public function setColSpan($colSpan)
    {
        $this->colSpan = $colSpan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRowSpan()
    {
        return $this->rowSpan;
    }

    /**
     * @param mixed $rowSpan
     * @return DataTableCell
     */
    public function setRowSpan($rowSpan)
    {
        $this->rowSpan = $rowSpan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * @param mixed $isHidden
     * @return DataTableCell
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextColor()
    {
        return $this->textColor ? $this->textColor : '#000000';
    }

    /**
     * @param mixed $textColor
     * @return DataTableCell
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
        return $this;
    }

}