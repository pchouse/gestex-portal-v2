<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use PChouse\GestexPortal\Di\Inject;
use Rebelo\Date\Date;
use Rebelo\Decimal\Decimal;

#[Inject]
class Response implements IResponse
{

    protected \Logger $logger;

    private array $values = [];

    const HTML_CODE = "html_code";

    const JS_CODE = "js_code";

    const ERROR = "error";

    const ERROR_NUMBER = "error_number";

    const MSG = "msg";

    const WARNING = "warning";

    const ERROR_FIELDS = "error_fields";

    const TYPE_LOAD = "type_load";

    const DATA = "data";

    const EXTRA_DATA = "extraData";

    public function __construct(protected IResponseHttpHeader $responseHttpHeader)
    {

        $this->logger = \Logger::getLogger(Response::class);

        $this->values[self::HTML_CODE]    = null;
        $this->values[self::ERROR]        = false;
        $this->values[self::ERROR_NUMBER] = null;
        $this->values[self::WARNING]      = null;
        $this->values[self::ERROR_FIELDS] = null;
        $this->values[self::TYPE_LOAD]    = null;
        $this->values[self::DATA]         = null;
        $this->values[self::EXTRA_DATA]   = null;
    }

    public function setHtmlCode(?string $html): void
    {
        $this->values[self::HTML_CODE] = $html;
    }

    public function getHtmlCode(): ?string
    {
        return $this->values[self::HTML_CODE];
    }

    public function setJsCode(?string $jsCode): void
    {
        $this->values[self::JS_CODE] = $jsCode;
    }

    public function getJsCode(): ?string
    {
        return $this->values[self::JS_CODE];
    }

    public function setError(bool $bool): void
    {
        $this->values[self::ERROR] = $bool;
    }

    public function getError(): bool
    {
        return $this->values[self::ERROR];
    }

    public function setErrorNumber(?int $errorNumber): void
    {
        $this->values[self::ERROR_NUMBER] = $errorNumber;
    }

    public function getErrorNumber(): ?int
    {
        return $this->values[self::ERROR_NUMBER];
    }

    public function setMsg(?string $msg): void
    {
        $this->values[self::MSG] = $msg;
    }

    public function getMsg(): ?string
    {
        return $this->values[self::MSG];
    }

    public function setWarning(?string $warning): void
    {
        $this->values[self::WARNING] = $warning;
    }

    public function getWarning(): ?string
    {
        return $this->values[self::WARNING];
    }

    public function setErrorFields(?array $fields): void
    {
        $this->values[self::ERROR_FIELDS] = $fields;
    }

    public function getErrorFields(): ?array
    {
        return $this->values[self::ERROR_FIELDS];
    }

    public function setTypeLoad(?string $typeLoad): void
    {
        $this->values[self::TYPE_LOAD] = $typeLoad;
    }

    public function getTypeLoad(): ?string
    {
        return $this->values[self::TYPE_LOAD];
    }

    public function setData(?string $data): void
    {
        $this->values[self::DATA] = $data;
    }

    public function getData(): ?string
    {
        return $this->values[self::DATA];
    }

    public function setExtraData(?string $extraData): void
    {
        $this->values[self::EXTRA_DATA] = $extraData;
    }

    public function getExtraData(): ?string
    {
        return $this->values[self::EXTRA_DATA];
    }

    /**
     * @throws \Exception
     */
    public function toJson(): string
    {
        if (\count($this->values[self::ERROR_FIELDS] ?? []) > 0) {
            $this->values[self::ERROR_FIELDS] = \array_unique($this->values[self::ERROR_FIELDS]);
        }

        //JSON_FORCE_OBJECT
        return \json_encode($this->values) ?:
            throw new \Exception("Error ao criar JSON na resposta");
    }

    /**
     * Send response as json
     *
     * @throws \Exception
     */
    public function send(): void
    {
        $this->responseHttpHeader->sendApplicationJson();
        echo $this->toJson();
        \ob_flush();
    }

    /**
     * Send the HTML with header Content-Type: text/html
     *
     * @return void
     * @throws \Exception
     */
    public function sendHtml(): void
    {
        $this->responseHttpHeader->sendTextHtml();
        if ($this->getError()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $this->getMsg();
            return;
        }
        echo $this->getHtmlCode() ?? "";
        \ob_flush();
    }

    /**
     * Send argument  as json
     *
     * @throws \Exception
     */
    public function sendAsJson(array|object|bool|int|float|string $json): void
    {
        $this->responseHttpHeader->sendApplicationJson();
        if ($this->getError()) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            echo $this->getMsg();
            return;
        }

        if (\is_array($json)) {
            foreach ($json as $k => $v) {
                if ($v instanceof Date) {
                    $json[$k] = $v->format("d-m-Y");
                    continue;
                }

                if ($v instanceof Decimal) {
                    $json[$k] = $v->valueOf();
                }
            }
        }

        if (\is_bool($json)) {
            echo $json ? "true" : "false";
        } elseif (\is_scalar($json)) {
            echo $json;
        } else {
            //JSON_FORCE_OBJECT
            echo \json_encode($json) ?:
                throw new \Exception("Error ao criar JSON na resposta");
        }
        \ob_flush();
    }
}
