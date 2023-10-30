<?php

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\Di\Container;
use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \PChouse\GestexPortal\MVC\Login\UsernameNotExitsException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \PChouse\GestexPortal\MVC\Login\WrongAuthenticationPasswordException
     */
    public function testLogin(): void
    {
        $loginModel = Container::get(ILoginModel::class);

        $auth = $loginModel->authenticate("0009", "macaco");
    }

}
