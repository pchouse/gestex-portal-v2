<?php

namespace PChouse\GestexPortal\MVC\Candidato;

use PChouse\GestexPortal\MVC\IModel;

interface ICandidatoModel extends IModel
{

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridData(string $json): string;
}
