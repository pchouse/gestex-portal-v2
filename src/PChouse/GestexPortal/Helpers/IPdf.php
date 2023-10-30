<?php

namespace PChouse\GestexPortal\Helpers;

interface IPdf
{
    /**
     * @throws \Zend_Pdf_Exception
     * @throws \Exception
     */
    public function init(string $pdf): void;

    /**
     * @throws \PChouse\Gestex\Di\DiException
     * @throws \Zend_Pdf_Exception
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     */
    public function writeImpDate(): void;

    /**
     * @return string
     * @throws \Zend_Pdf_Exception
     */
    public function pdfToString(): string;

    /**
     * @throws \Zend_Pdf_Exception
     */
    public function newPage(): void;

    /**
     * @return void
     * @throws \Zend_Pdf_Exception
     */
    public function numberPages(): void;

    /**
     * @param $text
     *
     * @return float|int
     * @throws \Zend_Pdf_Exception
     */
    public function getTextWidth($text): float|int;

    /**
     * @param $text
     * @param $with
     *
     * @return array
     * @throws \Zend_Pdf_Exception
     */
    public function splitTwoLines($text, $with): array;

    /**
     * @param $left
     * @param $right
     * @param $text
     *
     * @return float|int
     * @throws \Zend_Pdf_Exception
     */
    public function getAlignCenter($left, $right, $text): float|int;

    public function initEmpty(): void;
}
