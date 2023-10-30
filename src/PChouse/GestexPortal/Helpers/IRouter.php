<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

interface IRouter
{
    /**
     * @throws \ReflectionException
     * @throws \PChouse\Gestex\Di\DiException
     */
    public function route(): void;
}
