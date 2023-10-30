<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Exame;

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\Helpers\IPdf;
use PChouse\GestexPortal\Helpers\Pdf;
use PChouse\GestexPortal\MVC\Escola\IEscolaModel;
use Rebelo\Date\Date;

class ExameHelper implements IExameHelper
{

    /**
     * @param \PChouse\GestexPortal\Helpers\IPdf $pdf
     * @param array $categoria
     * @param array $exame
     *
     * @return void
     * @throws \Zend_Pdf_Exception
     */
    private function pdfCab(IPdf $pdf, array $categoria, array $exame): void
    {

        $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $pdf->pdfPage->setFont($pdf->font, 9);

        if (strtoupper($categoria["tipo"]) === 'T') {
            $pdf->pdfPage->drawText("Sala: " . $exame["sala"], 0.21 * 72, 10.56 * 72, Pdf::PDFENCODING);
        }

        $pdf->pdfPage->drawText(
            "Prova de exame: " . $categoria["codigo"] . " - " . $categoria["descricao"],
            2.37 * 72,
            10.56 * 72,
            Pdf::PDFENCODING
        );

        $pdf->pdfPage->drawText(
            "Alvará: " . $exame["alvara"],
            (8.07 * 72) - $pdf->getTextWidth("Alvará: " . $exame["alvara"]),
            10.56 * 72,
            Pdf::PDFENCODING
        );
    }

    /**
     * @param \Rebelo\Date\Date $fromDay
     * @param \Rebelo\Date\Date $toDay
     *
     * @return \PChouse\GestexPortal\Helpers\IPdf
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \Zend_Pdf_Exception
     */
    public function examesPDF(Date $fromDay, Date $toDay): IPdf
    {
        if ($toDay->isEarlier($fromDay)) {
            throw new \Exception("Data de fim anterior à de inicio");
        }

        $dateProvider = Container::get(IDateProvider::class);
        $exameModel   = Container::get(IExameModel::class);
        $escolaModel  = Container::get(IEscolaModel::class);
        $pdf          = Container::get(IPdf::class);
        $escola       = $escolaModel->getEscola();

        $hoje        = $dateProvider->date();
        $examesStack = \json_decode($exameModel->getExameBetweenDates($fromDay, $toDay), true);

        $pdf->init('examesGrelha');
        $left    = 0.4;
        $right   = 11.29;
        $rect    = array(0.85, 4.67, 5.93, 7.29, 8.03, 9.27, 9.64, 10.5, 10.86);
        $yA      = 6.61;
        $yB      = $yA - 0.25;
        $y       = $yB + 0.1;
        $padding = 0.05;
        $num     = 0;
        foreach ($examesStack as $exame) :
            $yLinhaB = false;

            $num++;

            if ($yB < 0.55) {
                $pdf->newPage();
                $yA = 6.61;
                $yB = $yA - 0.25;
                $y  = $yB + 0.1;
            }
            $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);

            $pdf->pdfPage->setFont($pdf->font, 8);

            $pdf->pdfPage->drawText(
                $num,
                (($rect[0] - $padding) * 72) - $pdf->getTextWidth($num),
                $y * 72,
                Pdf::PDFENCODING
            );

            $linhaNome = $exame["numAluno"] . '-' . $exame["nome"];

            $rectWidth = (($rect[1]) * 72) - (($rect[0] + 4 * $padding) * 72);
            $nomeWidth = $pdf->getTextWidth($linhaNome);
            if ($nomeWidth > $rectWidth) {
                $nomeParts = explode(' ', $linhaNome);
                $linhaA    = '';
                $linhaB    = '';
                foreach ($nomeParts as $part) {
                    if ($pdf->getTextWidth($linhaA . $part) < $rectWidth) {
                        $linhaA .= $part . ' ';
                    } else {
                        $linhaB .= $part . ' ';
                    }
                }
                $yLinhaB = true;
                $pdf->pdfPage->drawText($linhaA, (($rect[0] + $padding) * 72), $y * 72, pdf::PDFENCODING);
                $pdf->pdfPage->drawText($linhaB, (($rect[0] + $padding) * 72), ($y - .15) * 72, pdf::PDFENCODING);
            } else {
                $pdf->pdfPage->drawText($linhaNome, ($rect[0] + $padding) * 72, $y * 72, pdf::PDFENCODING);
            }

            $pdf->pdfPage->drawText(
                $exame["dataExame"],
                (($rect[2] - (($rect[2] - $rect[1]) / 2)) * 72) - $pdf->getTextWidth($exame["dataExame"]) / 4,
                $y * 72,
                Pdf::PDFENCODING
            );

            $pdf->pdfPage->drawText(
                $exame["numDocId"],
                (($rect[3] - (($rect[3] - $rect[2]) / 2)) * 72) - $pdf->getTextWidth($exame["numDocId"]) / 2,
                $y * 72,
                Pdf::PDFENCODING
            );

            $pdf->pdfPage->drawText(
                $exame["categoria"],
                (($rect[4] - (($rect[4] - $rect[3]) / 2)) * 72) - $pdf->getTextWidth($exame["categoria"]) / 2,
                $y * 72,
                Pdf::PDFENCODING
            );

            $tipo  = $exame["tipoExame"];

            $pdf->pdfPage->drawText(
                $exame["pauta"],
                (($rect[5] - (($rect[5] - $rect[4]) / 2)) * 72) - $pdf->getTextWidth($exame["pauta"]) / 2,
                $y * 72,
                Pdf::PDFENCODING
            );

            $pdf->pdfPage->drawText(
                $tipo,
                (($rect[6] - (($rect[6] - $rect[5]) / 2)) * 72) - $pdf->getTextWidth($tipo) / 2,
                $y * 72,
                Pdf::PDFENCODING
            );

            $pdf->pdfPage->drawText(
                $exame["hora"],
                (($rect[7] - (($rect[7] - $rect[6]) / 2)) * 72) - $pdf->getTextWidth($exame["hora"]) / 2,
                $y * 72,
                Pdf::PDFENCODING
            );

            $pdf->pdfPage->drawText(
                "X",
                (($rect[8] - (($rect[8] - $rect[7]) / 2)) * 72) - $pdf->getTextWidth("X") / 2,
                $y * 72,
                Pdf::PDFENCODING
            );

            $de = $dateProvider->parse(Date::SQL_DATE, $exame["dataExame"]);

            if ($hoje->isLater($de) || ($hoje->isEqual($de) && (int)\date('H') > 18)
            ) {
                $pdf->pdfPage->drawText(
                    $exame["resultado"],
                    (($right - (($right - $rect[8]) / 2)) * 72) - $pdf->getTextWidth($exame["resultado"]) / 2,
                    $y * 72,
                    Pdf::PDFENCODING
                );
            }

            if ($yLinhaB) {
                $yB -= 0.15;
            }

            $pdf->pdfPage->setLineWidth(0.25);

            $pdf->pdfPage->drawRectangle(
                $left * 72,
                $yA * 72,
                $right * 72,
                $yB * 72,
                \Zend_Pdf_Page::SHAPE_DRAW_STROKE
            );

            foreach ($rect as $vX) {
                $pdf->pdfPage->drawLine($vX * 72, $yA * 72, $vX * 72, $yB * 72);
            }

            $yA = $yB;
            $yB = $yA - 0.25;
            $y  = $yB + 0.1;
        endforeach;

        $nPages = count($pdf->pdfFile->pages);
        for ($n = 0; $n < $nPages; $n++) {
            $pdf->pdfPage = $pdf->pdfFile->pages[$n];
            $pdf->font    = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
            $pdf->pdfPage->setFont($pdf->font, 8);
            $text = "Página " . ($n + 1) . " de " . $nPages;
            $pdf->pdfPage->drawText($text, ($right * 72) - $pdf->getTextWidth($text), 0.2 * 72, pdf::PDFENCODING);
            $pdf->pdfPage->drawText(date('d-m-Y'), ($left * 72), 0.2 * 72, pdf::PDFENCODING);
            $text = $escola->getAlvara() . ' - ' . $escola->getNome();
            $pdf->pdfPage->drawText($text, ($left * 72), 7.18 * 72, pdf::PDFENCODING);
            $text = 'Data: ' . $fromDay->format(Date::SQL_DATE);
            if (!$fromDay->isEqual($toDay)) {
                $text .= ' até ' . $toDay->format(Date::SQL_DATE);
            }
            $pdf->pdfPage->drawText($text, ($left * 72), 7.05 * 72, pdf::PDFENCODING);
        }
        return $pdf;
    }

    /**
     * @throws \Zend_Pdf_Exception
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     */
    public function examesPraticosPDF(Date $fromDay, Date $toDay): IPdf
    {

        if ($toDay->isEarlier($fromDay)) {
            throw new \Exception("Data de fim antarior à de inicio");
        }

        $exameModel = Container::get(IExameModel::class);
        $pdf        = Container::get(IPdf::class);

        $categoriasStack = \json_decode(
            $exameModel->getCategoriasPraticoWithExam($fromDay, $toDay),
            true
        );

        if (\count($categoriasStack) === 0) {
            throw new \Exception("Tabela de categorias vazia");
        }

        $pdf->init('examesMarcados');
        $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $yRect     = 10.23;
        $yRectB    = $yRect - 0.20;
        $xB        = array(0.50, 1.38, 2.35, 6.62, 6.90, 7.50);
        $pdf->pdfPage->setLineWidth(0.25);
        $lineN       = 1;
        $leftMargin  = 0.2;
        $rightMargin = 8.07;

        $firstPage = true;

        foreach ($categoriasStack as $categoria) {
            $examesStack = \json_decode(
                $exameModel->getExameOfCategoriaBetweenDates($fromDay, $toDay, $categoria["codigo"]),
                true
            );

            $newPage = true;

            foreach ($examesStack as $key => $exame) {
                if ($firstPage) {
                    $this->pdfCab($pdf, $categoria, $exame);
                } elseif ($newPage && !$firstPage) {
                    $pdf->newPage();
                    $yRect  = 10.23;
                    $yRectB = $yRect - 0.15;
                    $this->pdfCab($pdf, $categoria, $exame);
                    $lineN = 1;
                }

                $newPage   = false;
                $firstPage = false;

                $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
                $pdf->pdfPage->setFont($pdf->font, 8);
                $pdf->pdfPage->setLineWidth(0.25);

                if ($key == 0) {
                    $this->pdfCab($pdf, $categoria, $exame);
                }

                $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
                $pdf->pdfPage->setFont($pdf->font, 8);
                $pdf->pdfPage->drawRectangle(
                    $leftMargin * 72,
                    $yRect * 72,
                    $rightMargin * 72,
                    $yRectB * 72,
                    \Zend_Pdf_Page::SHAPE_DRAW_STROKE
                );
                foreach ($xB as $vX) {
                    $pdf->pdfPage->drawLine(
                        $vX * 72,
                        $yRect * 72,
                        $vX * 72,
                        $yRectB * 72
                    );
                }

                $pdf->pdfPage->drawText(
                    $lineN,
                    0.219 * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exame["alvara"],
                    ($xB[0] + 0.09) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exame["numAluno"],
                    ($xB[1] + 0.09) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exame["nome"],
                    ($xB[2] + 0.05) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exame["resultado"],
                    ($xB[3] + 0.05) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exame["dataExame"],
                    ($xB[4] + 0.01) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exame["hora"],
                    ($xB[5] + 0.19) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                if ($yRectB < 0.65) {
                    $pdf->newPage();
                    $pdf->pdfPage->setFont($pdf->font, 8);
                    $pdf->pdfPage->setLineWidth(0.25);
                    $yRect  = 10.23;
                    $yRectB = $yRect - 0.20;
                    $this->pdfCab($pdf, $categoria, $exame);
                } else {
                    $yRect  = $yRectB - 0.001;
                    $yRectB = $yRect - 0.15;
                }
                $lineN++;
            }
        }

        $nPages = count($pdf->pdfFile->pages);
        for ($n = 0; $n < $nPages; $n++) {
            $pdf->pdfPage = $pdf->pdfFile->pages[$n];
            $pdf->font    = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
            $pdf->pdfPage->setFont($pdf->font, 11);
            $pdf->writeImpDate();

            $pdf->pdfPage->setFont($pdf->font, 8);
            $pdf->pdfPage->drawText(
                "Página " . ($n + 1) . " de " . $nPages,
                7.00 * 72,
                0.35 * 72,
                Pdf::PDFENCODING
            );
        }

        return $pdf;
    }

    /**
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Zend_Pdf_Exception
     * @throws \ReflectionException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function examesTeoricosPDF(Date $fromDay, Date $toDay): IPdf
    {

        if ($toDay->isEarlier($fromDay)) {
            throw new \Exception("Data de fim antarior à de inicio");
        }

        $exameModel = Container::get(IExameModel::class);
        $pdf        = Container::get(IPdf::class);

        $categoriasStack = \json_decode(
            $exameModel->getCategoriasTeoricoWithExam($fromDay, $toDay),
            true
        );

        if (\count($categoriasStack) === 0) {
            throw new \Exception("Tabela de categorias vazia");
        }

        $pdf->init('examesMarcados');
        $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
        $yRect     = 10.23;
        $yRectB    = $yRect - 0.20;
        $xB        = array(0.50, 1.38, 2.35, 6.62, 6.90, 7.50);
        $pdf->pdfPage->setLineWidth(0.25);
        $lineN       = 1;
        $leftMargin  = 0.2;
        $rightMargin = 8.07;

        $firstPage = true;

        foreach ($categoriasStack as $categoria) {
            $examesStack = \json_decode(
                $exameModel->getExameOfCategoriaBetweenDates($fromDay, $toDay, $categoria["codigo"]),
                true
            );

            $newPage = true;
            $oldSala = null;

            foreach ($examesStack as $key => $exames) {
                if ($firstPage) {
                    $this->pdfCab($pdf, $categoria, $exames);
                } elseif ($newPage && !$firstPage) {
                    $pdf->newPage();
                    $yRect  = 10.23;
                    $yRectB = $yRect - 0.15;
                    $this->pdfCab($pdf, $categoria, $exames);
                    $lineN   = 1;
                    $oldSala = false;
                }
                $newPage   = false;
                $firstPage = false;

                $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
                $pdf->pdfPage->setFont($pdf->font, 8);
                $pdf->pdfPage->setLineWidth(0.25);

                if ($oldSala !== $exames["sala"] && $oldSala) {
                    $pdf->newPage();
                    $pdf->pdfPage->setFont($pdf->font, 8);
                    $pdf->pdfPage->setLineWidth(0.25);
                    $yRect  = 10.23;
                    $yRectB = $yRect - 0.15;
                    $this->pdfCab($pdf, $categoria, $exames);
                    $lineN = 1;
                }

                $oldSala = $exames["sala"];

                if ($key === 0) {
                    $this->pdfCab($pdf, $categoria, $exames);
                }

                $pdf->font = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);

                $pdf->pdfPage->setFont($pdf->font, 8);

                $pdf->pdfPage->drawRectangle(
                    $leftMargin * 72,
                    $yRect * 72,
                    $rightMargin * 72,
                    $yRectB * 72,
                    \Zend_Pdf_Page::SHAPE_DRAW_STROKE
                );
                foreach ($xB as $vX) {
                    $pdf->pdfPage->drawLine(
                        $vX * 72,
                        $yRect * 72,
                        $vX * 72,
                        $yRectB * 72
                    );
                }

                $pdf->pdfPage->drawText($lineN, 0.219 * 72, ($yRectB + 0.03) * 72, Pdf::PDFENCODING);

                $pdf->pdfPage->drawText(
                    $exames["alvara"],
                    ($xB[0] + 0.09) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exames["numAluno"],
                    ($xB[1] + 0.09) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exames["nome"],
                    ($xB[2] + 0.05) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exames["resultado"],
                    ($xB[3] + 0.05) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exames["dataExame"],
                    ($xB[4] + 0.01) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                $pdf->pdfPage->drawText(
                    $exames["hora"],
                    ($xB[5] + 0.19) * 72,
                    ($yRectB + 0.03) * 72,
                    Pdf::PDFENCODING
                );

                if ($yRectB < 0.65) {
                    $pdf->newPage();
                    $pdf->pdfPage->setFont($pdf->font, 8);
                    $pdf->pdfPage->setLineWidth(0.25);
                    $yRect  = 10.23;
                    $yRectB = $yRect - 0.20;
                    $this->pdfCab($pdf, $categoria, $exames);
                } else {
                    $yRect  = $yRectB - 0.001;
                    $yRectB = $yRect - 0.15;
                }
                $lineN++;
            }
        }

        $nPages = count($pdf->pdfFile->pages);

        for ($n = 0; $n < $nPages; $n++) {
            $pdf->pdfPage = $pdf->pdfFile->pages[$n];
            $pdf->font    = \Zend_Pdf_Font::fontWithName(\Zend_Pdf_Font::FONT_HELVETICA);
            $pdf->pdfPage->setFont($pdf->font, 11);
            $pdf->writeImpDate();

            $pdf->pdfPage->setFont($pdf->font, 8);
            $pdf->pdfPage->drawText("Página " . ($n + 1) . " de " . $nPages, 7.00 * 72, 0.35 * 72, pdf::PDFENCODING);
        }

        return $pdf;
    }
}
