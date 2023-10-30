<?php

namespace PChouse\GestexPortal\MVC\Licenca;

use PChouse\GestexPortal\MVC\IModel;

interface ILicencaModel extends IModel
{

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridData(string $json): string;
}
