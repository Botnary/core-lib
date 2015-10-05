<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/19/2014
 * Time: 5:09 PM
 */

namespace Zone\Core\Component\PrintContent;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class Printer extends \mPDF
{
    private $javascript = true;
    private $n_js;
    private $useAutoPrint = false;
    private $fileName = 'Document.pdf';
    private $docTitle = '';
    private $return = false;
    private $eventDispatcher;

    /**
     * Printer constructor.
     * @param string $mode
     * @param string $format
     * @param int $default_font_size
     * @param string $default_font
     * @param int $mgl
     * @param int $mgr
     * @param int $mgt
     * @param int $mgb
     * @param int $mgh
     * @param int $mgf
     * @param string $orientation
     */
    public function __construct($mode = '', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 16, $mgh = 9, $mgf = 9, $orientation = 'P')
    {
        parent::mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh, $mgf, $orientation);
        $this->eventDispatcher = new EventDispatcher();
    }

    function _putjavascript()
    {
        $this->_newobj();
        $this->n_js = $this->n;
        $this->_out('<<');
        $this->_out('/Names [(EmbeddedJS) ' . ($this->n + 1) . ' 0 R]');
        $this->_out('>>');
        $this->_out('endobj');
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/S /JavaScript');
        $this->_out('/JS ' . $this->_textstring($this->javascript));
        $this->_out('>>');
        $this->_out('endobj');
    }

    function _putresources()
    {
        $this->_putextgstates();
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog()
    {
        parent::_putcatalog();
        if (!empty($this->javascript)) {
            $this->_out('/Names <</JavaScript ' . ($this->n_js) . ' 0 R>>');
        }
    }

    function getAvailableWidth()
    {
        return $this->w - ($this->lMargin + $this->rMargin);
    }

    function getAvailableHeight()
    {
        return $this->h - ($this->tMargin + $this->bMargin);
    }

    function Output()
    {
        if ($this->return) return parent::Output('', 'S');
        if ($this->isAutoPrint()) {
            parent::Output();
        } else {
            parent::Output($this->getFileName(), 'D');
        }
        return false;
    }

    function setDocumentTitle($title)
    {
        $this->docTitle = $title;
        $this->fileName = $this->slug(str_replace(' ', '_', $this->docTitle)) . '.pdf';
    }

    function getDocumentTitle()
    {
        return $this->docTitle;
    }

    function useAutoPrint()
    {
        $this->javascript = "print('true');";
        $this->useAutoPrint = true;
    }

    function useReturn()
    {
        $this->return = true;
    }

    function isAutoPrint()
    {
        return $this->useAutoPrint;
    }

    function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    function AddPage($orientation = '', $size = '')
    {
        parent::AddPage($orientation, $size);
        $this->getEventDispatcher()->dispatch(PrinterEvents::ADD_PAGE, new PrinterEvents($this));
    }

    static function slug($input)
    {
        $string = html_entity_decode($input, ENT_COMPAT, "UTF-8");
        $string = iconv("UTF-8", "ASCII//TRANSLIT", $string);
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $string));
    }

    function addTable(DataTable $dataTable)
    {
        error_log($dataTable->compile());
        $this->WriteHTML($dataTable->compile());
    }

    function addCaption($text, $fontSize = 16, $style = 'B', $align = 'C')
    {
        switch ($align) {
            case 'C':
                $a = 'center';
                break;
            case 'L':
                $a = 'left';
                break;
            case 'R':
                $a = 'right';
                break;
            default:
                $a = 'center';
                break;
        }
        $this->SetFont('dejavusans', $style, $fontSize);
        $this->WriteHTML(sprintf('<h1 style="text-align: %s">%s</h1>', $a, $text));
        $this->SetFont('dejavusans', '', 9);
    }

    function getFormatter()
    {
        return new NumberFormatter("en", NumberFormatter::CURRENCY);
    }
}