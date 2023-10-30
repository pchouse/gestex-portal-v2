<?php

namespace PChouse\GestexPortal\MVC\FacturacaoDetalhe;

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\IController;

interface IFacturacaoDetalheController extends IController
{

    public function dataGrid(): void;
}
