<?php

namespace PChouse\GestexPortal\Helpers;

interface IResponse
{
    public function setHtmlCode(?string $html): void;

    public function getHtmlCode(): ?string;

    public function setJsCode(?string $jsCode): void;

    public function getJsCode(): ?string;

    public function setError(bool $bool): void;

    public function getError(): bool;

    public function setErrorNumber(?int $errorNumber): void;

    public function getErrorNumber(): ?int;

    public function setMsg(?string $msg): void;

    public function getMsg(): ?string;

    public function setWarning(?string $warning): void;

    public function getWarning(): ?string;

    public function setErrorFields(?array $fields): void;

    public function getErrorFields(): ?array;

    public function setTypeLoad(?string $typeLoad): void;

    public function getTypeLoad(): ?string;

    public function setData(?string $data): void;

    public function getData(): ?string;

    public function setExtraData(?string $extraData): void;

    public function getExtraData(): ?string;

    /**
     * @throws \Exception
     */
    public function toJson(): string;

    /**
     * Send response as json (Response)
     * @throws \Exception
     */
    public function send(): void;

    /**
     * Send the HTML with header Content-Type: text/html
     * @return void
     * @throws \Exception
     */
    public function sendHtml(): void;

    /**
     * Send argument  as json
     * @throws \Exception
     */
    public function sendAsJson(array|object|bool|int|float|string $json): void;
}
