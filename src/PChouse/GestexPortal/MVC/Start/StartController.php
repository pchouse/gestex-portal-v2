<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Start;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AController;
use PChouse\GestexPortal\MVC\IController;

#[Inject]
#[Autowired]
class StartController extends AController implements IController
{
    public function __construct(protected IStartModel $startModel, protected IStartView $startView)
    {
        parent::__construct($this->startModel, $this->startView);
    }

    public function doAction(string|null $action): void
    {
        switch ($action) {
            case "start":
                $this->start();
                break;
            default:
                parent::doAction($action);
        }
    }

    /**
     *
     * Send the startup layout
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function start(): void
    {
        $this->logger->debug(__METHOD__);
        $this->startView->sendStart();
    }
}
