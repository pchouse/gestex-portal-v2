<?php

namespace PChouse\GestexPortal\MVC\Facturacao;

use PChouse\GestexPortal\MVC\IController;

interface IFacturacaoController extends IController
{

    public function dataGrid(): void;

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function dataGridChild(): void;
}
