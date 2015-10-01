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

class Printer extends UnicodeFPDF
{
    private $widths;
    private $aligns;
    private $javascript = "";
    private $n_js;
    private $useAutoPrint = false;
    private $fileName = 'Document.pdf';
    private $docTitle = '';
    private $return = false;
    private $eventDispatcher;

    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        $this->eventDispatcher = new EventDispatcher();
        parent::__construct($orientation = 'P', $unit = 'mm', $size = 'A4');
    }

    function setTableWidths($w)
    {
        //Set the array of column widths
        $this->widths = $w;
    }

    function getAvailableWidth()
    {
        return $this->w - ($this->lMargin + $this->rMargin);
    }

    function getAvailableHeight()
    {
        return $this->h - ($this->tMargin + $this->bMargin);
    }

    function setTableCellAligns($a)
    {
        //Set the array of column alignments
        $this->aligns = $a;
    }

    function addTableRow($data, $fill = false, $border = true, $style = null)
    {
        //Calculate the height of the row
        $h = $this->getTotalHeight($data);
        //Issue a page break first if needed
        $this->checkPageBreak($h);
        //Draw the cells of the row
        for ($i = 0; $i < count($data); $i++) {
            $w = $this->widths[$i];
            $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
            //Save the current position
            $x = $this->GetX();
            $y = $this->GetY();
            if ($border) {
                //Draw the border
                $this->Rect($x, $y, $w, $h, $fill?'DF':'D');
            }
            //Set the style
            if($style){
                $this->SetFont('',$style);
            }
            //Print the text
            $this->MultiCell($w, 5, $this->toUtf($data[$i]), 0, $a);
            //Reset back the style
            $this->SetFont('','');
            //Put the position to the right of the cell
            $this->SetXY($x + $w, $y);
        }
        //Go to the next line
        $this->Ln($h);
    }

    function getTotalHeight($data = array())
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++)
            $nb = max($nb, $this->nbLines($this->widths[$i], $data[$i]));
        $h = 5 * $nb;
        return $h;
    }

    function checkPageBreak($h)
    {
        $x = $this->GetX();
        //If the height h would cause an overflow, add a new page immediately
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            $this->SetX($x);
        }
    }

    function nbLines($w, $txt)
    {
        //Computes the number of lines a MultiCell of width w will take
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }

    function addText($h, $text, $fontSize = 9, $style = '', $align = 'L')
    {
        $x = $this->GetX();
        if ($align == 'C') {
            $center = $this->w / 2 - $this->GetStringWidth($text) / 2;
            $this->SetX($center);
        }
        $this->SetFont('DejaVuSans', $style, $fontSize);
        $this->Write($h, $this->toUtf($text));
        $this->SetFont('DejaVuSans', '', 9);
        $this->SetX($x);
        $this->Ln();
    }

    function addCaption($h, $text, $fontSize = 9, $style = '', $align = 'L')
    {
        $this->Ln();
        $this->SetFont('DejaVuSans', $style, $fontSize);
        $this->MultiCell($this->getAvailableWidth(), $h, $this->toUtf($text), 0, $align);
        $this->SetFont('DejaVuSans', '', 9);
        $this->Ln();
    }

    function getFormatter()
    {
        return new NumberFormatter("en", NumberFormatter::CURRENCY);
    }

    public function drawLine($red, $blue, $green, $size = 0.1)
    {
        $this->Ln();
        $this->SetDrawColor($red, $blue, $green);
        $this->SetLineWidth($size);
        $this->Line($this->lMargin, $this->GetY(), $this->getAvailableWidth() + 10, $this->GetY());
        $this->Ln(5);
        $this->SetDrawColor(0, 0, 0);
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
    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
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

    function Output($file = null)
    {
        if ($this->return) return parent::Output('', 'S');
        if ($this->isAutoPrint()) {
            parent::Output();
        } else if(trim($file)){
            parent::Output($file, 'F');
        }else{
            parent::Output($this->getFileName(), 'D');
        }
        return false;
    }

    function escape($text)
    {
        return html_entity_decode($text, ENT_QUOTES, "utf-8");
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

    static function slug($input)
    {
        $string = html_entity_decode($input, ENT_COMPAT, "UTF-8");
        $string = iconv("UTF-8", "ASCII//TRANSLIT", $string);
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $string));
    }

    //Cell with horizontal scaling if text is too wide
    function CellFit($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '', $scale = 1, $force = 0)
    {
        //Get string width
        $str_width = $this->GetStringWidth($txt);
        $str_width = $str_width == 0 ?: $str_width;
        //Calculate ratio to fit cell
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $ratio = ($w - $this->cMargin * 2) / $str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force == 1));
        if ($fit) {
            switch ($scale) {

                //Character spacing
                case 0:
                    //Calculate character spacing in points
                    $char_space = ($w - $this->cMargin * 2 - $str_width) / max($this->MBGetStringLength($txt) - 1, 1) * $this->k;
                    //Set character spacing
                    $this->_out(sprintf('BT %.2f Tc ET', $char_space));
                    break;

                //Horizontal scaling
                case 1:
                    //Calculate horizontal scaling
                    $horiz_scale = $ratio * 100.0;
                    //Set horizontal scaling
                    $this->_out(sprintf('BT %.2f Tz ET', $horiz_scale));
                    break;

            }
            //Override user alignment (since text will fill up cell)
            $align = '';
        }

        //Pass on to Cell method
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT ' . ($scale == 0 ? '0 Tc' : '100 Tz') . ' ET');
    }

    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, 1, 0);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, 1, 1);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, 0, 0);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '')
    {
        //Same as calling CellFit directly
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, 0, 1);
    }

    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if ($this->CurrentFont['type'] == 'Type0') {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++) {
                if (ord($s[$i]) < 128)
                    $len++;
                else {
                    $len++;
                    $i++;
                }
            }
            return $len;
        } else
            return strlen($s);
    }

    function toUtf($txt)
    {
        return $txt;
        //return iconv('UTF-8', 'windows-1252', stripslashes(trim($this->escape($txt))));
    }

    var $extgstates = array();

    // alpha: real value from 0 (transparent) to 1 (opaque)
    // bm:    blend mode, one of the following:
    //          Normal, Multiply, Screen, Overlay, Darken, Lighten, ColorDodge, ColorBurn,
    //          HardLight, SoftLight, Difference, Exclusion, Hue, Saturation, Color, Luminosity
    function SetAlpha($alpha, $bm = 'Normal')
    {
        // set alpha for stroking (CA) and non-stroking (ca) operations
        $gs = $this->AddExtGState(array('ca' => $alpha, 'CA' => $alpha, 'BM' => '/' . $bm));
        $this->SetExtGState($gs);
    }

    function AddExtGState($parms)
    {
        $n = count($this->extgstates) + 1;
        $this->extgstates[$n]['parms'] = $parms;
        return $n;
    }

    function SetExtGState($gs)
    {
        $this->_out(sprintf('/GS%d gs', $gs));
    }

    function _enddoc()
    {
        if (!empty($this->extgstates) && $this->PDFVersion < '1.4')
            $this->PDFVersion = '1.4';
        parent::_enddoc();
    }

    function _putextgstates()
    {
        for ($i = 1; $i <= count($this->extgstates); $i++) {
            $this->_newobj();
            $this->extgstates[$i]['n'] = $this->n;
            $this->_out('<</Type /ExtGState');
            $parms = $this->extgstates[$i]['parms'];
            $this->_out(sprintf('/ca %.3F', $parms['ca']));
            $this->_out(sprintf('/CA %.3F', $parms['CA']));
            $this->_out('/BM ' . $parms['BM']);
            $this->_out('>>');
            $this->_out('endobj');
        }
    }

    function _putresourcedict()
    {
        parent::_putresourcedict();
        $this->_out('/ExtGState <<');
        foreach ($this->extgstates as $k => $extgstate)
            $this->_out('/GS' . $k . ' ' . $extgstate['n'] . ' 0 R');
        $this->_out('>>');
    }

    function AddPage($orientation = '', $size = '')
    {
        parent::AddPage($orientation, $size);
        $this->getEventDispatcher()->dispatch(PrinterEvents::ADD_PAGE, new PrinterEvents($this));
    }
} 