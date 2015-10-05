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
        $this->SetFont('dejavusans', 'B', 9);
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
}