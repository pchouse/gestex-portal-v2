<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class LoginView extends AView implements ILoginView
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     *  Render the login form
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function renderLoginForm(): void
    {
        $varTwig['login_type'] = LoginModel::LOGIN_BKO;
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('loginForm.twig', $varTwig);
    }

    /**
     * Send the rendered logout confirmation dialog
     *
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws \Exception
     */
    public function renderLogoutConfirmDialog(): void
    {
        $varTwig['title']            = "Sair";
        $varTwig['modalId']         = "confirmDialog";
        $varTwig['modalBody']        = "Tem a certeza que pretende sair da aplicação?";
        $varTwig['confirmDialogYes'] = "Sim";
        $varTwig['confirmDialogNo']  = "Não";
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('confirmDialog.twig', $varTwig);
    }

    /**
     * Render the chenge password form
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function renderChangePdwForm(): void
    {
        $varTwig['login_type'] = LoginModel::LOGIN_BKO;
        $varTwig['username'] = $this->globals->getRequestKey("user");
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('changePdwForm.twig', $varTwig);
    }

    /**
     * Redirect user to the change password form
     * @return void
     */
    public function redirectToChangePwd(): void
    {
        $link   = \sprintf(
            "%s://%s?controller=login&action=chpwdForm&user=%s",
            $this->globals->getServerKey('REQUEST_SCHEME') ?? "http",
            $this->globals->getServerKey('SERVER_NAME') ?? "activ2sell-dev.localhost",
            $this->globals->getRequestKey("username") ?? ""
        );
        header("Location:$link");
    }

}
