<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Exame;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class ExameView extends AView implements IExameView
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Overwrite this method to send the Modal form
     *
     * @throws \Exception
     */
    public function renderLayout(): void
    {
        $varTwig = [];
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('layout.twig', $varTwig);
    }
}
