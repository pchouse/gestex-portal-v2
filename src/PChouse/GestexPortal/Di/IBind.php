<?php

namespace PChouse\GestexPortal\Di;

interface IBind
{
    /**
     * The instance name or value name
     * @return class-string|string
     */
    public function getBinds(): string;

    /**
     * The scope
     * @return \PChouse\GestexPortal\Di\Scope
     */
    public function getScope(): Scope;

    /**
     * The class name to instantiate or teh value to store
     * @return class-string|mixed
     */
    public function getValue(): mixed;
}
