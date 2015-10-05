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
    private $bordered;

    public function __construct($bordered = false)
    {
        $this->table = array();
        $this->bordered = $bordered;
    }

    function addRow($row = array())
    {
        $this->table[] = $row;
    }

    function compile()
    {
        $border = $this->bordered ? 'border=1' : '';
        $html = sprintf('<table %s cellpadding="0" cellspacing="2">', $border);
        foreach ($this->table as $row) {
            $html .= '<tr>';
            /** @var DataTableCell $cell */
            foreach ($row as $cell) {
                $html .= $cell->compile();
            }
            $html .= '</tr>';
        }
        $html = '</table>';
        return $html;
    }

}