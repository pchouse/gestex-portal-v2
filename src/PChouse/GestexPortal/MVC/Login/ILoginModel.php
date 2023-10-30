<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\MVC\Escola\IEscola;
use PChouse\GestexPortal\MVC\IModel;

interface ILoginModel extends IModel
{

    /**
     * @param string $username
     * @param string $password
     *
     * @return \PChouse\GestexPortal\MVC\Escola\IEscola
     * @throws \PChouse\GestexPortal\MVC\Login\UsernameNotExitsException
     * @throws \PChouse\GestexPortal\MVC\Login\WrongAuthenticationPasswordException
     */
    public function authenticate(string $username, string $password): void;
}
