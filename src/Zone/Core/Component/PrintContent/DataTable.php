<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * E-mail: my.test@live.ca
 * Date: 10/5/2015
 * Time: 10:58 AM
 */

namespace Zone\Core\Component\PrintContent;


class DataTable
{
    private $table;
    private $head;
    private $bordered;
    private $headBgColor;
    private $headColor;
    private $fontSize;
    private $width;
    private $cellPadding = 0;

    public function __construct($bordered = false, $headBgColor = 'rgb(206, 206, 206)')
    {
        $this->table = array();
        $this->head = array();
        $this->bordered = $bordered;
        $this->headBgColor = $headBgColor;
        $this->headColor = '#000000';
        $this->fontSize = 9;
        $this->width = false;
    }

    function addHead($row)
    {
        $this->head[] = $row;
    }

    function addRow($row = array())
    {
        $this->table[] = $row;
    }

    function compile()
    {
        $html = '';
        $width = $this->width ? sprintf('width:%s;', $this->width) : '';
        if($this->bordered && !$this->cellPadding){
            $this->cellPadding = 8;
        }
        $border = $this->bordered ? 'cellpadding="'.$this->cellPadding.'" style="' . $width . 'border-collapse: collapse;font-size:' . $this->fontSize . 'pt"' : 'cellpadding="'.$this->cellPadding.'" style="' . $width . 'font-size:' . $this->fontSize . 'pt;"';
        $html .= sprintf('<table %s cellspacing="0">', $border);
        if (count($this->head) > 0) {
            $html .= '<thead>';
            foreach ($this->head as $row) {
                $html .= sprintf('<tr style="background: %s;">', $this->getHeadBgColor());
                /** @var DataTableCell $cell */
                foreach ($row as $cell) {
                    if ($this->bordered) {
                        $cell->setUseBorders(true);
                        $cell->setIsTh(true);
                        $cell->setTextColor($this->getHeadColor());
                    }
                    $html .= $cell->compile();
                }
                $html .= '</tr>';
            }
            $html .= '</thead>';
        }
        $html .= '<tbody>';
        foreach ($this->table as $row) {
            $html .= '<tr>';
            /** @var DataTableCell $cell */
            foreach ($row as $cell) {
                if ($this->bordered) {
                    $cell->setUseBorders(true);
                }
                $html .= $cell->compile();
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        return $html;
    }

    /**
     * @param mixed $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @param mixed $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getHeadBgColor()
    {
        return $this->headBgColor;
    }

    /**
     * @param string $headBgColor
     */
    public function setHeadBgColor($headBgColor)
    {
        $this->headBgColor = $headBgColor;
    }

    /**
     * @return string
     */
    public function getHeadColor()
    {
        return $this->headColor;
    }

    /**
     * @param string $headColor
     */
    public function setHeadColor($headColor)
    {
        $this->headColor = $headColor;
    }

}