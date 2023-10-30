<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

interface ISession
{
    public const USER_IS_LOGGED_IN = "userIsLoggedIn";

    /**
     * Start session
     *
     * @param string|null $name
     * @param string|null $id
     * @param array $options
     *
     * @return void
     */
    public function startSession(string $name = null, string $id = null, array $options = []): void;

    /**
     * Finish session and destroy all stared values
     * @return void
     */
    public function destroySession(): void;

    /**
     * Set user as Logged in
     * @return void
     */
    public function setUseAsLoggedIn(): void;

    /**
     * Set user as logged out, but not destroy all session values
     * @return void
     */
    public function setUseAsLoggedOut(): void;

    /**
     * Get if user is logged in
     * @return bool
     */
    public function isUserLoggedIn(): bool;

    /**
     * Store a string value in session
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function putString(string $key, string $value): void;

    /**
     * Store a integer value in session
     *
     * @param string $key
     * @param int $value
     *
     * @return void
     */
    public function putInt(string $key, int $value): void;

    /**
     * Store a float value in session
     *
     * @param string $key
     * @param float $value
     *
     * @return void
     */
    public function putFloat(string $key, float $value): void;

    /**
     * Store a boolean value in session
     *
     * @param string $key
     * @param bool $value
     *
     * @return void
     */
    public function putBool(string $key, bool $value): void;

    /**
     * Store an array value in session
     *
     * @param string $key
     * @param array $value
     *
     * @return void
     */
    public function putArray(string $key, array $value): void;

    /**
     * Get from session a string value
     * @param string $key
     *
     * @return string|null
     */
    public function getString(string $key): ?string;

    /**
     * Get from session an integer value
     *
     * @param string $key
     *
     * @return int|null
     */
    public function getInt(string $key): ?int;

    /**
     * Get from session a float value
     *
     * @param string $key
     *
     * @return float|null
     */
    public function getFloat(string $key): ?float;

    /**
     * Get from session a boolean value
     *
     * @param string $key
     *
     * @return bool|null
     */
    public function getBool(string $key): ?bool;

    /**
     * Get from session an array value
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key): ?array;

    /**
     * @template T
     *
     * @param class-string<T> $classString
     *
     * @return T|null
     */
    public function getSessionObject(string $classString): mixed;

    /**
     * Store a value in super global session
     *
     * @param object $value
     *
     * @return void
     */
    public function putSessionObject(object $value): void;

    /**
     * Store a value in super global session
     *
     * @param object $value
     *
     * @return void
     */
    public function removeSessionObject(object $value): void;
}
