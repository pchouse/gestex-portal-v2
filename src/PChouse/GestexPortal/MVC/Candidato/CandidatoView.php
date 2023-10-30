<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Candidato;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class CandidatoView extends AView implements ICandidatoView
{

    public function __construct()
    {
        parent::__construct();
    }
}
