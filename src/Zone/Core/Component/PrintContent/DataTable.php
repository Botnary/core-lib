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

    public function __construct($bordered = false, $headBgColor = 'rgb(206, 206, 206)')
    {
        $this->table = array();
        $this->head = array();
        $this->bordered = $bordered;
        $this->headBgColor = $headBgColor;
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
        $border = $this->bordered ? 'style="border-collapse: collapse;"' : '';
        if($this->bordered) {
            $html = '<style>
                        /*td {border: 0.1mm solid #000000; }*/
                        /*th {border: 0.1mm solid #000000; }*/
                    </style>';
        }
        $html .= sprintf('<table %s cellpadding="8" cellspacing="0">', $border);
        if (count($this->head) > 0) {
            $html .= '<thead>';
            foreach ($this->head as $row) {
                $html .= sprintf('<tr style="background: %s">', $this->headBgColor);
                /** @var DataTableCell $cell */
                foreach ($row as $cell) {
                    if ($this->bordered) {
                        $cell->setUseBorders(true);
                        $cell->setIsTh(true);
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

}