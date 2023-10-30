<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use PChouse\GestexPortal\Di\Inject;

#[Inject]
class Session implements ISession
{
    private \Logger $logger;

    public function __construct(protected IGlobals $globals)
    {
        $this->logger = \Logger::getLogger(static::class);
        $this->logger->debug("New instance");
    }

    /**
     * Start session
     *
     * @param string|null $name
     * @param string|null $id
     * @param array $options
     *
     * @return void
     */
    public function startSession(string $name = null, string $id = null, array $options = []): void
    {
        @\session_name($name ?? "GESTEX");

        if ($id !== null) {
            @\session_id($id);
        }

        if (@\session_start($options)) {
            $this->logger->debug(
                \sprintf("Session started with name %s ", $name ?? "not set")
            );
        } else {
            $this->logger->error(
                \sprintf("Error starting session with name %s ", $name ?? "not set")
            );
        }
    }

    /**
     * Finish session and destroy all stared values
     *
     * @return void
     */
    public function destroySession(): void
    {
        $this->globals->resetSession();
        if (\session_destroy()) {
            $this->logger->debug("Session destroyed");
        } else {
            $this->logger->error("Error destroying session");
        }
    }

    /**
     * Set user as Logged in
     *
     * @return void
     */
    public function setUseAsLoggedIn(): void
    {
        $this->globals->putSessionKey(ISession::USER_IS_LOGGED_IN, true);
        $this->logger->debug("User was set as logged in session");
    }

    /**
     * Set user as logged out, but not destroy all session values
     *
     * @return void
     */
    public function setUseAsLoggedOut(): void
    {
        $this->globals->putSessionKey(ISession::USER_IS_LOGGED_IN, false);
        $this->logger->debug("User was set as not logged in session");
    }

    /**
     * Get if user is logged in
     *
     * @return bool
     */
    public function isUserLoggedIn(): bool
    {
        return $this->globals->getSessionKey(ISession::USER_IS_LOGGED_IN) ?? false;
    }

    /**
     * Store a string value in session
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function putString(string $key, string $value): void
    {
        $this->putInSession($key, $value);
    }

    /**
     * Store a integer value in session
     *
     * @param string $key
     * @param int $value
     *
     * @return void
     */
    public function putInt(string $key, int $value): void
    {
        $this->putInSession($key, $value);
    }

    /**
     * Store a float value in session
     *
     * @param string $key
     * @param float $value
     *
     * @return void
     */
    public function putFloat(string $key, float $value): void
    {
        $this->putInSession($key, $value);
    }

    /**
     * Store a boolean value in session
     *
     * @param string $key
     * @param bool $value
     *
     * @return void
     */
    public function putBool(string $key, bool $value): void
    {
        $this->putInSession($key, $value);
    }

    /**
     * Store an array value in session
     *
     * @param string $key
     * @param array $value
     *
     * @return void
     */
    public function putArray(string $key, array $value): void
    {
        $this->putInSession($key, $value);
    }

    /**
     * Get from session a string value
     *
     * @param string $key
     *
     * @return string|null
     */
    public function getString(string $key): ?string
    {
        return $this->getFromSession($key);
    }

    /**
     * Get from session a integer value
     *
     * @param string $key
     *
     * @return int|null
     */
    public function getInt(string $key): ?int
    {
        return $this->getFromSession($key);
    }

    /**
     * Get from session a float value
     *
     * @param string $key
     *
     * @return float|null
     */
    public function getFloat(string $key): ?float
    {
        return $this->getFromSession($key);
    }

    /**
     * Get from session a boolean value
     *
     * @param string $key
     *
     * @return bool|null
     */
    public function getBool(string $key): ?bool
    {
        return $this->getFromSession($key);
    }

    /**
     * Get from session an array value
     *
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key): ?array
    {
        return $this->getFromSession($key);
    }

    /**
     * Get session value from strore
     *
     * @param string $key
     *
     * @return mixed
     */
    protected function getFromSession(string $key): mixed
    {
        return $this->globals->getSessionKey($key);
    }

    /**
     * Put the value in session store
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    protected function putInSession(string $key, mixed $value): void
    {
        $this->globals->putSessionKey($key, $value);
    }

    /**
     * @template T
     *
     * @param class-string<T> $classString
     *
     * @return T|null
     */
    public function getSessionObject(string $classString): mixed
    {
        if (null === ($obj = $this->globals->getSessionKey($classString) ?? null)) {
            return null;
        }
        return \unserialize($obj);
    }

    /**
     * Store a value in super global session
     *
     * @param object $value
     *
     * @return void
     */
    public function putSessionObject(object $value): void
    {
        $this->globals->putSessionKey($value::class, \serialize($value));
    }

    /**
     * Store a value in super global session
     *
     * @param object $value
     *
     * @return void
     */
    public function removeSessionObject(object $value): void
    {
        unset($_SESSION[$value::class]);
    }
}
