<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;

#[Inject]
#[Autowired]
class Pdf implements IPdf
{

    const PDFENCODING = "UTF-8";

    const CHARSAPACE = 0.09;

    public \Zend_Pdf $pdfFile;

    public string $pdfFileName;

    public \Zend_Pdf_Page $pdfPage;

    public \Zend_Pdf_Resource_Font $font;

    public string $pdf;

    public string $name;

    public function __construct()
    {
    }

    public function initEmpty(): void
    {
        $this->pdfFile = new \Zend_Pdf();
    }

    /**
     * @throws \Zend_Pdf_Exception
     * @throws \Exception
     */
    public function init(string $pdf): void
    {
        $this->pdfFileName = match ($pdf) {
            'examesGrelha'         => GESTEX_PDF . DIRECTORY_SEPARATOR . "examesGrelha.pdf",
            'examesMarcados'=>GESTEX_PDF . DIRECTORY_SEPARATOR . "examesMarcados.pdf",
            default                => throw new \Exception("Tipo de PDF $pdf desconhecido"),
        };
        $this->pdf         = $pdf;
        $this->pdfFile     = \Zend_Pdf::load($this->pdfFileName);
        $this->pdfPage     = $this->pdfFile->pages[0];
        $this->font        = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $this->pdfPage->setFont($this->font, 10);
    }

    /**
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \Zend_Pdf_Exception
     */
    public function writeImpDate(): void //@phpstan-ignore-line
    {
        $date       = Container::get(IDateProvider::class)->date();
        $this->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $this->pdfPage->setFont($this->font, 8);
        $this->pdfPage->drawText(
            \sprintf("Data: %s (Impressão)", $date->format('d-m-Y H:i')),
            0.63 * 72,
            0.35 * 72,
            self::PDFENCODING
        );
    }

    /**
     * @return string
     * @throws \Zend_Pdf_Exception
     */
    public function pdfToString(): string
    {
        return $this->pdfFile->render();
    }

    /**
     * @throws \Zend_Pdf_Exception
     */
    public function newPage(): void //@phpstan-ignore-line
    {
        $template                     = $this->pdfFile->pages[0];
        $newPage                      = new \Zend_Pdf_Page($template);
        $index                        = count($this->pdfFile->pages);
        $this->pdfFile->pages[$index] = $newPage;
        $this->pdfPage                = $this->pdfFile->pages[$index];
    }

    /**
     * @return void
     * @throws \Zend_Pdf_Exception
     */
    public function numberPages(): void
    {
        $nPages = \count($this->pdfFile->pages);
        for ($n = 0; $n < $nPages; $n++) {
            $this->pdfPage = $this->pdfFile->pages[$n];
            $this->font    = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
            $this->pdfPage->setFont($this->font, 8);
            $this->pdfPage->drawText(
                "Página " . ($n + 1) . " de " . $nPages,
                7.00 * 72,
                0.35 * 72,
                self::PDFENCODING
            );
        }
    }

    /**
     * @param $text
     *
     * @return float|int
     * @throws \Zend_Pdf_Exception
     */
    public function getTextWidth($text): float|int
    {

        $text = (string)$text;

        $font     = $this->pdfPage->getFont();
        $fontSize = $this->pdfPage->getFontSize();

        $characters = array();
        for ($i = 0; $i < \strlen($text); $i++) {
            $characters [] = ord($text [$i]);
        }
        $glyphs    = $font->glyphNumbersForCharacters($characters);
        $widths    = $font->widthsForGlyphs($glyphs);
        $textWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
        return $textWidth;
    }

    /**
     * @param $text
     * @param $with
     *
     * @return array
     * @throws \Zend_Pdf_Exception
     */
    public function splitTwoLines($text, $with): array
    {
        $textParts = explode(' ', $text);
        $line1     = "";
        $n = 0;
        foreach ($textParts as $n => $t) {
            if ($this->getTextWidth($t . $line1) <= $with) {
                $line1 .= $t . " ";
            } else {
                break;
            }
        }
        for ($x = 0; $x < $n; $x++) {
            unset($textParts[$x]);
        }
        $lines[0] = trim($line1);
        $lines[1] = trim(join(" ", $textParts));
        return $lines;
    }

    /**
     * @param $left
     * @param $right
     * @param $text
     *
     * @return float|int
     * @throws \Zend_Pdf_Exception
     */
    public function getAlignCenter($left, $right, $text): float|int
    {
        return ($left * 72) + (($right * 72 - $left * 72) / 2 - ($this->getTextWidth($text) / 2));
    }
}
