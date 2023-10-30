<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

interface IResponseHttpHeader
{
    /**
     * Set header content-type Content-Type: text/html; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function setTextHtml(string $encoding = null): void;

    /**
     * Set header Content-Type: application/javascript; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function setApplicationJs(string $encoding = null): void;

    /**
     * Send content-type Content-Type: text/html; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function sendTextHtml(string $encoding = null): void;

    /**
     * Send content-type Content-Type: application/javascript; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function sendApplicationJs(string $encoding = null): void;

    /**
     * Send content-type Content-Type: application/json; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function sendApplicationJson(string $encoding = null): void;

    /**
     * Send content-type Content-Type: application/json; charset=encoding with status 204
     *
     * @return void
     * @throws \Exception
     */
    public function sendNoContentApplicationJson(): void;

    /**
     *
     * Send the properties header to the browser for PDF files
     *
     * @param string $fileName
     * @param string|null $type attachment
     *
     * @return void
     * @throws \Exception
     */
    public function sendPdfApplication(string $fileName, string $type = null): void;

    /**
     *
     * Send the properties header to the browser for Excel/calc files
     *
     * @param string $fileName
     * @param string|null $type attachment /
     *
     * @return void
     * @throws \Exception
     */
    public function sendExcelApplication(string $fileName, string $type = null): void;

    /**
     *
     * Send the properties header to the browser for word/writer files
     *
     * @param string $fileName
     * @param string|null $type
     *
     * @return void
     * @throws \Exception
     */
    public function sendWordApplication(string $fileName, string $type = null): void;

    /**
     *
     * @param string $fileName
     *
     * @return void
     */
    public function sendCsvFile(string $fileName): void;

    /**
     *
     * @param string $fileName
     *
     * @return void
     */
    public function sendTxtFile(string $fileName): void;

    /**
     *
     * Header for jqGris xml request
     *
     * @param string|null $encoding
     *
     * @return void
     */
    public function sendJqGridXMLHeader(string $encoding = null): void;

    /**
     *
     * Send the properties header to the browser for XML attached files
     *
     * @param string $fileName
     * @param string|null $type attachment
     *
     * @return void
     * @throws \Exception
     */
    public function sendXMLAttached(string $fileName, string $type = null): void;

    /**
     *
     * @return void
     */
    public function sendInternalError(): void;

    /**
     * Send content-type Content-Type: application/json; charset=encoding with status 400
     *
     * @return void
     * @throws \Exception
     */
    public function sendBadRequestApplicationJson(): void;
}
