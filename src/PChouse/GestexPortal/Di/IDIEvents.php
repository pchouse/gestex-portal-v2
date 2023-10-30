<?php

namespace PChouse\GestexPortal\Di;

interface IDIEvents
{

    /**
     * Executed after the DI container create instance
     * @return void
     */
    public function afterInstanceCreated(): void;

    /**
     * Executed before the DI Container return the instance
     * @return void
     */
    public function beforeReturnInstance(): void;
}
