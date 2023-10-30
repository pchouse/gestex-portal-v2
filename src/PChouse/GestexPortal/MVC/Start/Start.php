<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Start;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AObject;
use PChouse\GestexPortal\MVC\IObject;

#[Inject]
#[Autowired]
class Start extends AObject implements IObject
{

    public function __construct(IStartModel $startDocCabecalhoModel)
    {
        parent::__construct($startDocCabecalhoModel);
    }
}
