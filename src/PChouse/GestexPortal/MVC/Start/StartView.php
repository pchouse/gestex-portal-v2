<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Start;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class StartView extends AView implements IStartView
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     *  Render the start main layout
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function sendStart(): void
    {
        $varTwig = [];
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('start.twig', $varTwig);
    }
}
