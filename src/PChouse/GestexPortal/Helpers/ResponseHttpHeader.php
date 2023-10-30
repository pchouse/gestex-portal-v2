<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

class ResponseHttpHeader implements IResponseHttpHeader
{
    /**
     * Stack of headers
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Verify if encoding exist
     *
     * @param string $encoding
     *
     * @return bool
     */
    private function checkEncoding(string $encoding): bool
    {
        $enc = \array_change_key_case(\array_flip(\mb_list_encodings()));
        return \array_key_exists(\strtolower($encoding), $enc);
    }

    /**
     * Set header content-type Content-Type: text/html; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function setTextHtml(string $encoding = null): void
    {
        if ($encoding === null) {
            $encoding = "utf-8";
        }

        if (!$this->checkEncoding($encoding)) {
            throw new \Exception('wrong encoding in ' . __METHOD__);
        }
        $this->headers["Content-Type"] = "text/html; charset=" . $encoding;
    }

    /**
     * Set header Content-Type: application/javascript; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function setApplicationJs(string $encoding = null): void
    {
        if ($encoding === null) {
            $encoding = "utf-8";
        }

        if (!$this->checkEncoding($encoding)) {
            throw new \Exception('wrong encoding in ' . __METHOD__);
        }

        $this->headers["Content-Type"] = "application/javascript; charset=" . $encoding;
    }

    /**
     * Send content-type Content-Type: text/html; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function sendTextHtml(string $encoding = null): void
    {
        if ($encoding === null) {
            $encoding = "utf-8";
        }

        if (!$this->checkEncoding($encoding)) {
            throw new \Exception('wrong encoding in ' . __METHOD__);
        }

        \header("Content-Type: text/html; charset=" . $encoding);
    }

    /**
     * Send content-type Content-Type: application/javascript; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function sendApplicationJs(string $encoding = null): void
    {
        if ($encoding === null) {
            $encoding = "utf-8";
        }

        if (!$this->checkEncoding($encoding)) {
            throw new \Exception('wrong encoding in ' . __METHOD__);
        }
        \header("Content-Type: application/javascript; charset=" . $encoding);
    }

    /**
     * Send content-type Content-Type: application/json; charset=encoding
     *
     * @param string|null $encoding
     *
     * @return void
     * @throws \Exception
     */
    public function sendApplicationJson(string $encoding = null): void
    {
        if ($encoding === null) {
            $encoding = "utf-8";
        }

        if (!$this->checkEncoding($encoding)) {
            throw new \Exception('wrong encoding in ' . __METHOD__);
        }
        \header("Content-Type: application/json; charset=" . $encoding);
    }

    /**
     * Send content-type Content-Type: application/json; charset=encoding with status 204
     *
     * @return void
     * @throws \Exception
     */
    public function sendNoContentApplicationJson(): void
    {
        \header("Content-Type: application/json; charset=UTF-8", true, 204);
    }

    /**
     * Send content-type Content-Type: application/json; charset=encoding with status 400
     *
     * @return void
     * @throws \Exception
     */
    public function sendBadRequestApplicationJson(): void
    {
        \header("Content-Type: application/json; charset=UTF-8", true, 400);
    }

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
    public function sendPdfApplication(string $fileName, string $type = null): void
    {
        $enum = array('attachment', 'inline');

        if ($type === null) {
            $type = "attachment";
        }

        if (\in_array($type, $enum) === false) {
            throw new \Exception('wrong type in ' . __METHOD__);
        }

        \header('Content-type: application/pdf');
        \header("Content-Transfer-Encoding: binary");
        \header("Content-Disposition: " . $type . "; filename=" . $fileName);
    }

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
    public function sendExcelApplication(string $fileName, string $type = null): void
    {
        $enum = array('attachment', 'inline');
        if ($type === null) {
            $type = "attachment";
        }

        if (\in_array($type, $enum) === false) {
            throw new \Exception('wrong type in ' . __METHOD__);
        }

        \header('Content-type: application/vnd.ms-excel');
        \header("Content-Transfer-Encoding: binary");
        \header("Content-Disposition: " . $type . "; filename=" . $fileName);
    }

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
    public function sendWordApplication(string $fileName, string $type = null): void
    {
        $enum = array('attachment', 'inline');

        if ($type === null) {
            $type = "attachment";
        }

        if (\in_array($type, $enum) === false) {
            throw new \Exception('wrong type in ' . __METHOD__);
        }
        \header('Content-type: application/vnd.ms-word');
        \header("Content-Transfer-Encoding: binary");
        \header("Content-Disposition: " . $type . "; filename=" . $fileName);
    }

    /**
     *
     * @param string $fileName
     *
     * @return void
     */
    public function sendCsvFile(string $fileName): void
    {
        \header('Content-type: text/csv');
        \header("Content-disposition: attachment; filename=" . $fileName);
    }

    /**
     *
     * @param string $fileName
     *
     * @return void
     */
    public function sendTxtFile(string $fileName): void
    {
        \header('Content-type: plain/text');
        \header("Content-disposition: attachment; filename=" . $fileName);
    }

    /**
     *
     * Header for jqGris xml request
     *
     * @param string|null $encoding
     *
     * @return void
     */
    public function sendJqGridXMLHeader(string $encoding = null): void
    {
        if ($encoding === null) {
            $encoding = "utf-8";
        }

        if (\stristr($_SERVER["HTTP_ACCEPT"], "application/xhtml+xml")) {
            \header("Content-type: application/xhtml+xml;charset=" . \strtolower($encoding));
        } else {
            \header("Content-type: text/xml;charset=" . \strtolower($encoding));
        }
    }

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
    public function sendXMLAttached(string $fileName, string $type = null): void
    {
        $enum = array('attachment', 'inline');

        if ($type === null) {
            $type = "attachment";
        }

        if (\in_array($type, $enum) === false) {
            throw new \Exception('wrong type in ' . __METHOD__);
        }
        \header('Content-type: text/xml');
        \header("Content-Transfer-Encoding: text");
        \header("Content-Disposition: " . $type . "; filename=" . $fileName);
    }

    /**
     *
     * @return void
     */
    public function sendInternalError(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    }
}
