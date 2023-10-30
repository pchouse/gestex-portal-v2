<?php

namespace PChouse\GestexPortal\MVC\Dashboard;

use PChouse\GestexPortal\MVC\IView;

interface IDashboardView extends IView
{

    public function sendLayout(): void;
}
