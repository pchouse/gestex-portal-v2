<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use PChouse\GestexPortal\MVC\IModel;

interface IEscolaModel extends IModel
{


    /**
     * Change the password, the new password wil be the one in field rawPassword
     *
     * @param string $password
     *
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     */
    public function changePassword(string $password): void;

    public function getEscola(): IEscola;
}
