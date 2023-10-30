<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

interface IRequest
{
    public function getUrlParts(): array;

    public function getController(): ?string;

    public function getAction(): ?string;

    public function getRequestBody(): ?string;
}
