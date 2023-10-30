<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Di;

class Bind implements IBind
{
    /**
     * @param \PChouse\GestexPortal\Di\Scope $scope The scope
     * @param class-string|string $binds The instance name or value name
     * @param mixed $value The class name to instantiate or teh value to store
     */
    public function __construct(
        protected Scope $scope,
        protected string $binds,
        protected mixed $value
    ) {
    }

    /**
     * The instance name or value name
     *
     * @return string
     */
    public function getBinds(): string
    {
        return $this->binds;
    }

    /**
     * The scope
     *
     * @return \PChouse\GestexPortal\Di\Scope
     */
    public function getScope(): Scope
    {
        return $this->scope;
    }

    /**
     * The class name to instantiate or teh value to store
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
