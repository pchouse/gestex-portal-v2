<?php

namespace PChouse\GestexPortal\MVC\Candidato;

use PChouse\GestexPortal\MVC\IController;

interface ICandidatoController extends IController
{

    public function dataGrid(): void;
}
