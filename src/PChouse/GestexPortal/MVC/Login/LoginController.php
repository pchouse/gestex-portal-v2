<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\Util;
use PChouse\GestexPortal\MVC\AController;

#[Inject]
#[Autowired]
class LoginController extends AController implements ILoginController
{

    public function __construct(protected ILoginModel $loginModel, protected ILoginView $loginView)
    {
        parent::__construct($this->loginModel, $this->loginView);
    }

    public function doAction(string|null $action): void
    {
        switch ($action) {
            case 'loginForm':
                $this->renderLoginForm();
                break;
            case 'login':
                $this->login();
                break;
            case 'renderLogoutConfirmDialog':
                $this->renderLogoutConfirmDialog();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'chpwd':
                $this->chpwd();
                break;
            default:
                parent::doAction($action);
        }
    }

    public static function verifyStrongPassword(string $pwd): bool
    {
        $reg = '/^.*(?=.{8,20})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#!$%^&+=]).*$/';
        return \preg_match($reg, $pwd) === 1;
    }

    /**
     *
     * Get the login form
     *
     * @return void
     */
    public function renderLoginForm(): void
    {
        $this->logger->debug(__METHOD__);
        $this->loginView->renderLoginForm();
    }

    /**
     * Authenticate user
     *
     * @return void
     * @throws \Exception
     */
    public function login(): void
    {
        try {
            $username = $this->globals->getRequestKey("username");
            $password = $this->globals->getRequestKey("password");

            if (Util::isNullOrEmpty($username)) {
                $this->response->setErrorFields(["username"]);
                throw new \Exception("O campo utilizador não pode estar vazio");
            }

            if (Util::isNullOrEmpty($password)) {
                $this->response->setErrorFields(["password"]);
                throw new \Exception("O campo palavra chave não pode estar vazio");
            }

            $this->loginModel->authenticate($username, $password);

            $this->response->setError(false);
        } catch (UsernameNotExitsException $e) {
            $this->response->setErrorFields(["username"]);
            $this->response->setMsg($e->getMessage());
            $this->response->setError(true);
        } catch (WrongAuthenticationPasswordException $e) {
            $this->response->setErrorFields(["password"]);
            $this->response->setMsg($e->getMessage());
            $this->response->setError(true);
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
        }

        $this->response->send();
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
        try {
            $this->loginView->renderLogoutConfirmDialog();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->sendHtml();
        }
    }

    /**
     * Do the user logout
     *
     * @return void
     */
    public function logout(): void
    {
        $this->session->destroySession();
    }

    /**
     * Render the change password login form
     *
     * @return void
     * @throws \Exception
     */
    public function renderChangePdwForm(): void
    {
        try {
            $this->loginView->renderChangePdwForm();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->sendHtml();
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function chpwd(): void
    {
        try {
            $this->session->destroySession();

            $username        = $this->globals->getRequestKey("username");
            $password        = $this->globals->getRequestKey("old_password");
            $newPassword     = $this->globals->getRequestKey("new_password");
            $confirmPassword = $this->globals->getRequestKey("confirm_password");

            if (Util::isNullOrEmpty($username)) {
                $this->response->setErrorFields(["username"]);
                throw new \Exception("O campo utilizador não pode estar vazio");
            }

            if (Util::isNullOrEmpty($password)) {
                $this->response->setErrorFields(["password"]);
                throw new \Exception("O campo palavra chave não pode estar vazio");
            }

            if (!self::verifyStrongPassword($newPassword)) {
                $this->response->setErrorFields(["new_password"]);
                throw new \Exception(
                    "A nova password não respeita as regras de segurança. " .
                    "Tem que ter entre 8 e 20 caracteres e letras maiúsculas, ".
                    "minúsculas, números e caracteres especiais."
                );
            }

            if ($confirmPassword !== $newPassword) {
                $this->response->setErrorFields(["new_password", "confirm_password"]);
                throw new \Exception("Os compos nova palavra-chave e confirmação têm que ser iguais");
            }

            $utilizador = $this->loginModel->authenticate($username, $password);

            if (!$utilizador->getActivo()) {
                throw new \Exception("Utilizador não activo, por favor contacte o suporte");
            }

            $this->session->startSession();
            $this->session->putSessionObject($utilizador);

            $utilizador->setRawPassword($newPassword);

            //Container::get(IUtilizadorModel::class)->changePassword($utilizador);

            $this->session->setUseAsLoggedIn();

            $this->response->setError(false);
            $this->response->send();
        } catch (\Throwable|UsernameNotExitsException|WrongAuthenticationPasswordException $e) {
            if ($e instanceof UsernameNotExitsException) {
                $this->response->setErrorFields(["username"]);
            }

            if ($e instanceof WrongAuthenticationPasswordException) {
                $this->response->setErrorFields(["password"]);
            }

            $this->session->destroySession();

            $this->logger->error($e->getMessage());

            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->send();
        }
    }
}
