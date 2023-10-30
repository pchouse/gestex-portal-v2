<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Dashboard;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class DashboardView extends AView implements IDashboardView
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws \Exception
     */
    public function sendLayout(): void
    {
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('dashboard_layout.twig');
    }
}
