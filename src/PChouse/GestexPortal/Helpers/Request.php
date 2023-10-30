<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use PChouse\GestexPortal\Di\Inject;

#[Inject]
class Request implements IRequest
{

    private \Logger $logger;

    private array $url;

    public function __construct(protected IGlobals $globals)
    {
        $this->logger  = \Logger::getLogger(static::class);

        $rawUrl = \sprintf(
            "%s://%s%s",
            $this->globals->getServer()["REQUEST_SCHEME"] ?? "",
            $this->globals->getServer()["HTTP_HOST"] ?? "",
            $this->globals->getServer()["REQUEST_URI"] ?? ""
        );

        $this->logger->info("Request: $rawUrl");

        $url       = \parse_url($rawUrl);
        $this->url = \is_array($url) ? $url : [];
    }

    public function getUrlParts(): array
    {
        return $this->url;
    }

    public function getController(): ?string
    {
        return $this->globals->getRequestKey("controller");
    }

    public function getAction(): string
    {
        return $this->globals->getRequestKey("action") ?? "";
    }

    public function getRequestBody(): ?string
    {
        return \file_get_contents('php://input');
    }
}
