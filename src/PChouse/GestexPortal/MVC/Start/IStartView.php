<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Start;

use PChouse\GestexPortal\MVC\IView;

interface IStartView extends IView
{
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
    public function sendStart(): void;
}
