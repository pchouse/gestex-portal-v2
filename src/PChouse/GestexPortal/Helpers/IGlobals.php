<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

interface IGlobals
{
    /**
     * Get the SERVER super global
     *
     * @return array $_SERVER
     */
    public function getServer(): array;

    /**
     * Get the value of $_SERVER with this key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getServerKey(string $key): mixed;
    /**
     * Get the POST super global
     *
     * @return array $_POST
     */
    public function getPost(): array;

    /**
     * Get the value of $_POST with this key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getPostKey(string $key): mixed;
    /**
     * Get the GET super global
     *
     * @return array $_GET
     */
    public function getGet(): array;

    /**
     * Get the value of $_GET with this key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getGetKey(string $key): mixed;

    /**
     * Get the REQUEST super global
     *
     * @return array $_POST
     */
    public function getRequest(): array;

    /**
     * Get the value of $_REQUEST with this key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRequestKey(string $key): mixed;

    /**
     * Get the SESSION super global
     *
     * @return array $_SESSION
     */
    public function getSession(): array;

    /**
     * Get the value of $_SESSION with this key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getSessionKey(string $key): mixed;

    /**
     * Store a value in super global session
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function putSessionKey(string $key, mixed $value): void;


    /**
     * Reset session values. Set to an empty array
     */
    public function resetSession(): void;

    /**
     * Unset a value in super global $_SESSION
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function unsetSessionKey(string $key, mixed $value): void;

    public function getFile(string $name): ?array;

}
