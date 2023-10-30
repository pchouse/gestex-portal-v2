<?php

namespace PChouse\GestexPortal\MVC\Exame;

use PChouse\GestexPortal\MVC\IView;

interface IExameView extends IView
{

    /**
     * Overwrite this method to send the Modal form
     *
     * @throws \Exception
     */
    public function renderLayout(): void;
}
