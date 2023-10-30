<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use PChouse\GestexPortal\MVC\IController;

interface IEscolaController extends IController
{

    /**
     * Renew and send a new password to the user
     *
     * @return void
     * @throws \Exception
     */
    public function renewPassword(): void;

}
