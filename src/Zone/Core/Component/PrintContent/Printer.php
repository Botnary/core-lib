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
use Zone\Core\Component\UTF8\StringComponent;

class Printer extends \Mpdf\Mpdf
{
    private $javascript = true;
    public $n_js;
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
        parent::__construct($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh, $mgf, $orientation);
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

    function Output($file = null, $dest = '')
    {
        if ($this->return) return parent::Output('', 'S');
        if ($this->isAutoPrint()) {
            parent::Output();
        } else if (trim($file)) {
            parent::Output($file, 'F');
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
        unset($this->javascript);
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

    function AddPage($orientation = '', $condition = '', $resetpagenum = '', $pagenumstyle = '', $suppress = '', $mgl = '', $mgr = '', $mgt = '', $mgb = '', $mgh = '', $mgf = '', $ohname = '', $ehname = '', $ofname = '', $efname = '', $ohvalue = 0, $ehvalue = 0, $ofvalue = 0, $efvalue = 0, $pagesel = '', $newformat = '')
    {
        parent::AddPage($orientation, $condition, $resetpagenum, $pagenumstyle, $suppress, $mgl, $mgr, $mgt, $mgb, $mgh, $mgf, $ohname, $ehname, $ofname, $efname, $ohvalue, $ehvalue, $ofvalue, $efvalue, $pagesel, $newformat);
        $this->getEventDispatcher()->dispatch(PrinterEvents::ADD_PAGE, new PrinterEvents($this));
    }

    static function slug($input)
    {
        return StringComponent::getInstance()->slugify($input);
    }

    function addTable(DataTable $dataTable)
    {
        $this->WriteHTML($dataTable->compile());
    }

    function addCaption($text, $headline = '3', $align = 'C')
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
        $this->WriteHTML(sprintf('<h%s style="text-align: %s;">%s</h%s>', $headline, $a, $text, $headline));
    }

    function getFormatter()
    {
        return new NumberFormatter("en", NumberFormatter::CURRENCY);
    }

    function setWatermark($file, $x = null, $y = null, $w = 0, $h = 0, $type = '', $link = '')
    {
        // Put an image on the page
        if (!isset($this->images[$file])) {
            // First use of this image, get info
            if ($type == '') {
                $pos = strrpos($file, '.');
                if (!$pos)
                    $this->Error('Image file has no extension and no type was specified: ' . $file);
                $type = substr($file, $pos + 1);
            }
            $type = strtolower($type);
            if ($type == 'jpeg')
                $type = 'jpg';
            $mtd = '_parse' . $type;
            if (!method_exists($this, $mtd))
                $this->Error('Unsupported image type: ' . $type);
            $info = $this->$mtd($file);
            $info['i'] = count($this->images) + 1;
            $this->images[$file] = $info;
        } else
            $info = $this->images[$file];

        // Automatic width and height calculation if needed
        if ($w == 0 && $h == 0) {
            // Put image at 96 dpi
            $w = -96;
            $h = -96;
        }
        if ($w < 0)
            $w = -$info['w'] * 72 / $w / $this->k;
        if ($h < 0)
            $h = -$info['h'] * 72 / $h / $this->k;
        if ($w == 0)
            $w = $h * $info['w'] / $info['h'];
        if ($h == 0)
            $h = $w * $info['h'] / $info['w'];

        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q', $w * $this->k, $h * $this->k, $x * $this->k, ($this->h - ($y + $h)) * $this->k, $info['i']));
    }
}