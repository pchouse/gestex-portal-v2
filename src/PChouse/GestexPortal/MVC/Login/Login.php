<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AObject;

#[Inject]
#[Autowired]
class Login extends AObject implements ILogin
{

    public function __construct(protected ILoginModel $loginDocCabecalhoModel)
    {
        parent::__construct($this->loginDocCabecalhoModel);
    }
}
