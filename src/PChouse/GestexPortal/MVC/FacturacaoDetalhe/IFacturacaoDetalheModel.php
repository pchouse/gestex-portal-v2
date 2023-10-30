<?php

namespace PChouse\GestexPortal\MVC\FacturacaoDetalhe;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Container;

interface IFacturacaoDetalheModel extends \PChouse\GestexPortal\MVC\IModel
{

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridData(string $json): string;

}
