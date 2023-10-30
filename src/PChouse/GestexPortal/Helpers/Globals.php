<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

class Globals implements IGlobals
{

    protected \Logger $logger;

    public function __construct()
    {
        $this->logger = \Logger::getLogger(self::class);
        $this->logger->debug("New Instance");
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $_SERVER;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getServerKey(string $key): mixed
    {
        return $_SERVER[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $_POST;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getPostKey(string $key): mixed
    {
        return $_POST[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $_GET;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getGetKey(string $key): mixed
    {
        return $_GET[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getRequest(): array
    {
        return $_REQUEST;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getRequestKey(string $key): mixed
    {
        return $_REQUEST[$key] ?? null;
    }

    /**
     * @return array
     */
    public function getSession(): array
    {
        return $_SESSION;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getSessionKey(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Store a value in super global session
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function putSessionKey(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset a value in super global session
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function unsetSessionKey(string $key, mixed $value): void
    {
        if (\array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @return void
     */
    public function resetSession(): void
    {
        $_SESSION = [];
    }

    public function getFile(string $name): ?array
    {
        return $_FILES[$name] ?? null;
    }
}
