<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use JetBrains\PhpStorm\Pure;

interface IObject extends IMvc
{

    public function getModel(): IModel;

    public function setAttribute(string $key, mixed $value): void;

    public function getAttribute(string $key): mixed;

    public function unsetAttribute(string $key): void;

    public function getAttributes(): array;

    public function toArray(bool $localized = false): array;

    public function toJson(bool $localized = false): string;

    public function load(): self;

    /**
     *
     * Get the instance from cache, if not exist get from database
     *
     * @template T
     * @param class-string<T> $class
     * @param string|int $id
     * @return T
     * @throws \Exception
     */
    public function fromCache(string $class, string|int $id): mixed;

    /**
     * Invalidate this cache all cache or only for an instance type
     *
     * @param class-string|null $class
     * @return void
     */
    public function invalidateCache(?string $class = null): void;

    /**
     * Remove an item from cache
     *
     * @param class-string $class
     * @param string|int $id
     * @return void
     */
    public function removeFromCache(string $class, string|int $id): void;

    /**
     * Put an item in the instance cache
     *
     * @param \PChouse\GestexPortal\MVC\AObject $obj
     * @param string|int|null $key Use also this key for cache, the instance will be accessed by both this key and id
     *
     * @return void
     */
    public function putInCache(IObject $obj, string|int|null $key = null): void;

    /**
     * Verify if exist in cache
     *
     * @param class-string $class
     * @param string|int $key Instance id or key
     * @return bool
     */
    #[Pure] public function existInCache(string $class, string|int $key): bool;

    /**
     * Generate the key to use in cache for class
     * @param class-string $class
     * @return string
     */
    public function classCacheKey(string $class): string;
}
