<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * Date: 9/19/2014
 * Time: 5:09 PM
 */

namespace Zone\Core\Component\PrintContent;


use Symfony\Component\Intl\NumberFormatter\NumberFormatter;

class Printer extends BasePrinter
{
    private $widths;
    private $aligns;

    function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
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
        $this->SetFont('Arial', $style, $fontSize);
        $this->Write($h, $this->toUtf($text));
        $this->SetFont('Arial', '', 9);
        $this->SetX($x);
        $this->Ln();
    }

    function addCaption($h, $text, $fontSize = 9, $style = '', $align = 'L')
    {
        $this->Ln();
        $this->SetFont('Arial', $style, $fontSize);
        $this->MultiCell($this->getAvailableWidth(), $h, $this->toUtf($text), 0, $align);
        $this->SetFont('Arial', '', 9);
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

} 