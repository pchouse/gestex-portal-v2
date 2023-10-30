<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Login;

use PChouse\GestexPortal\MVC\IController;

interface ILoginController extends IController
{
    /**
     * Render the login form
     * @return void
     */
    public function renderLoginForm(): void;

    /**
     * Test user credencials and id ok do the login
     * @return void
     */
    public function login(): void;

    /**
     * Render the logout form
     * @return void
     */
    public function renderLogoutConfirmDialog(): void;

    /**
     * Do the user logout
     * @return void
     */
    public function logout(): void;

    /**
     * Render the change password form
     * @return void
     */
    public function renderChangePdwForm(): void;

    /**
     * Change the user password
     * @return void
     */
    public function chpwd(): void;
}
