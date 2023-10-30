<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use JetBrains\PhpStorm\Pure;

abstract class AObject extends AMvc implements IObject
{

    /**
     * @var array
     */
    protected array $cache = [];

    public array $attributes = [];

    public function __construct(protected IModel $model)
    {
        parent::__construct();
    }

    /**
     * Executed after the DI container create instance
     *
     * @return void
     * @throws \Throwable
     */
    public function beforeReturnInstance(): void
    {
        parent::afterInstanceCreated();
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function unsetAttribute(string $key): void
    {
        unset($this->attributes[$key]);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Invoked when clone is called
     */
    public function __clone()
    {
        foreach ($this->attributes as $k => $v) {
            if (\is_object($v)) {
                $this->attributes[$k] = clone $v;
            } else {
                $this->attributes[$k] = $v;
            }
        }
    }


    /**
     * Test the string value for a column
     *
     * @param string $column
     * @param string|null $value
     *
     * @return void
     * @throws \Exception
     */
    public function testModelRegExp(string $column, ?string &$value): void
    {
        $this->testModelAllowNull($column, $value === null ? null : $this->trim($value));

        if ($value === null) {
            return;
        }

        if (null !== $regexp = $this->model->getRegExp($column)) {
            if (\preg_match($regexp, $value) !== 1) {
                throw new \Exception("Campo(s) com formato errado");
            }
        }

        if (null !== $maxLength = $this->model->getMaximLength($column)) {
            if (\strlen($value) > $maxLength) {
                throw new \Exception("Campo(s) com formato errado");
            }
        }
    }

    /**
     * Test allow null
     *
     * @param string $column
     * @param mixed $value
     *
     * @return void
     * @throws \Exception
     */
    public function testModelAllowNull(string $column, mixed $value = null): void
    {
        if ($value === null && $this->model->getAllowNull($column) === false) {
            throw new \Exception("Column '$column' can not be null");
        }
    }

    /**
     * Get the mvc model instance
     *
     * @return \PChouse\GestexPortal\MVC\IModel
     */
    public function getModel(): IModel
    {
        return $this->model;
    }

    /**
     *
     * Get the instance from cache, if not exist get from database
     *
     * @template T
     *
     * @param class-string<T> $class
     * @param string|int $id
     *
     * @return T
     * @throws \Exception
     */
    public function fromCache(string $class, string|int $id): mixed
    {
        $classHash = $this->classCacheKey($class);
        if (\array_key_exists($classHash, $this->cache) === false) {
            $this->cache[$classHash] = [];
        }

        if (\array_key_exists($id, $this->cache[$classHash]) === false) {
            $this->cache[$classHash][$id] = (new $class($id))->load();
        }

        return $this->cache[$classHash][$id];
    }

    /**
     * Invalidate this cache all cache or only for an instance type
     *
     * @param class-string|null $class
     *
     * @return void
     */
    public function invalidateCache(?string $class = null): void
    {
        if ($class === null) {
            $this->cache = [];
        } else {
            $classHash = $this->classCacheKey($class);
            if (\array_key_exists($classHash, $this->cache)) {
                unset($this->cache[$classHash]);
            }
        }
    }

    /**
     * Remove an item from cache
     *
     * @param class-string $class
     * @param string|int $id
     *
     * @return void
     */
    public function removeFromCache(string $class, string|int $id): void
    {
        $classHash = $this->classCacheKey($class);
        if (\array_key_exists($classHash, $this->cache) === false) {
            return;
        }

        if (\array_key_exists($id, $this->cache[$classHash]) === false) {
            return;
        }
        unset($this->cache[$classHash][$id]);
    }

    /**
     * Put an item in the instance cache
     *
     * @param \PChouse\GestexPortal\MVC\IObject $obj
     * @param string|int|null $key Use also this key for cache, the instance will be accessed by both this key and id
     *
     * @return void
     */
    public function putInCache(IObject $obj, string|int|null $key = null): void
    {
        $hashClass                    = $this->classCacheKey($obj::class);
        $id                           = $obj->attributes[$obj->getModel()->getIdColumnName()];
        $this->cache[$hashClass][$id] = $obj;

        if ($key !== null) {
            $this->cache[$hashClass][$key] = $obj;
        }
    }

    /**
     * Verify if exist in cache
     *
     * @param class-string $class
     * @param string|int $key Instance id or key
     *
     * @return bool
     */
    #[Pure] public function existInCache(string $class, string|int $key): bool
    {
        $hashClass = $this->classCacheKey($class);
        if (\array_key_exists($hashClass, $this->cache) === false) {
            return false;
        }

        return \is_array($this->cache[$hashClass]) && \array_key_exists($key, $this->cache[$hashClass]);
    }

    /**
     * Generate the key to use in cache for class
     *
     * @param class-string $class
     *
     * @return string
     */
    public function classCacheKey(string $class): string
    {
        return $class;
    }

    /**
     *
     * Get the object as an array of scalar type
     *
     * @param bool $localized Localize dates and decimals defaults false
     *
     * @return array
     */
    public function toArray(bool $localized = false): array
    {
        return $this->model->convertDataAsModel(
            $this->attributes,
            $localized
        );
    }

    /**
     * Get this object as json string
     *
     * @param bool $localized Localize dates and decimals. Defaults false
     *
     * @return string
     * @throws \Exception
     */
    public function toJson(bool $localized = false): string
    {
        if (false === $json = \json_encode($this->toArray($localized))) {
            throw new \Exception("error encoding json");
        }
        return $json;
    }

    /**
     * Load the object from database
     *
     * @return self
     * @phpstan-return $this
     */
    public function load(): self
    {
        $this->model->load($this);
        return $this;
    }
}
