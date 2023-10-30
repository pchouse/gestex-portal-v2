<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\Login\ILoginController;
use PChouse\GestexPortal\MVC\Start\IStartController;

#[Inject]
class Router implements IRouter
{

    private \Logger $logger;

    public function __construct(protected IRequest $request, protected ISession $session, protected IGlobals $globals)
    {
        $this->logger = \Logger::getLogger(static::class);
        $this->logger->debug("New instance");
    }

    /**
     * @throws \ReflectionException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Throwable
     */
    public function route(): void
    {
        if ($this->globals->getGetKey("logout") === "1") {
            Container::getRouteController(ILoginController::class)->doAction("logout");
            // continue to start page, do no return
        }

        $requestController = $this->request->getController();
        $requestAction = $this->request->getAction();

        if (!$this->session->isUserLoggedIn()) {
            $controller = Container::getRouteController(ILoginController::class);

            if (Util::isNullOrEmpty($requestController) || Util::isNullOrEmpty($requestAction)) {
                $controller->doAction("loginForm");
                return;
            }

            $controller->doAction($requestAction);
            return;
        }

        if (Util::isNullOrEmpty($requestController)) {
            Container::getRouteController(IStartController::class)->doAction("start");
            return;
        }

        $fqn = $this->getFQNForControllerName($requestController);
        $controllerInstance = Container::getRouteController($fqn);//@phpstan-ignore-line
        $controllerInstance->doAction($this->request->getAction());
    }

    /**
     * Construct the FQN for the request route controller name
     *
     * @param string $controllerName
     *
     * @return class-string
     */
    protected function getFQNForControllerName(string $controllerName): string
    {
        if (\str_starts_with($controllerName, "VT")) {
            $fqcn = \sprintf(
                'PChouse\\GestexPortal\\MVC\\%1$s\\I%2$sController',
                \preg_replace("/^VT/", "", $controllerName),
                \ucfirst($controllerName)
            );
        } else {
            $fqcn = \sprintf(
                'PChouse\\GestexPortal\\MVC\\%1$s\\I%1$sController',
                \ucfirst($controllerName)
            );
        }
        $this->logger->debug("FQCN is $fqcn");
        //@phpstan-ignore-next-line
        return $fqcn;
    }
}
