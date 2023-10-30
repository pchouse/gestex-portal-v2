<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\FacturacaoDetalhe;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;
use PChouse\GestexPortal\MVC\Facturacao\IFacturacaoView;

#[Inject]
#[Autowired]
class FacturacaoDetalheView extends AView implements IFacturacaoDetalheView
{

    public function __construct()
    {
        parent::__construct();
    }
}
