<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Licenca;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class LicencaView extends AView implements ILicencaView
{

    public function __construct()
    {
        parent::__construct();
    }
}
