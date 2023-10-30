<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\MVC\IView;

interface ILoginView extends IView
{
    public function renderLoginForm(): void;

    /**
     * Send the rendered logout confirmation dialog
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws \Exception
     */
    public function renderLogoutConfirmDialog(): void;

    /**
     * Render the chenge password form
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function renderChangePdwForm(): void;

    /**
     * Redirect user to change password form
     *
     * @return void
     */
    public function redirectToChangePwd(): void;

}
