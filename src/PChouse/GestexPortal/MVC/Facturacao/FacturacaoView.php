<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Facturacao;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class FacturacaoView extends AView implements IFacturacaoView
{

    public function __construct()
    {
        parent::__construct();
    }


}
