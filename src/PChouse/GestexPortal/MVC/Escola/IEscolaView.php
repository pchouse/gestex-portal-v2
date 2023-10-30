<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use Laminas\Mail\Address;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use PChouse\Gestex\Di\Container;
use PChouse\Gestex\Helpers\HtmlMimePart;
use PChouse\GestexPortal\MVC\DbOperType;
use PChouse\GestexPortal\MVC\IView;

interface IEscolaView extends IView
{

    /**
     * @param \PChouse\GestexPortal\MVC\Escola\IEscola $escola
     * @param \PChouse\GestexPortal\MVC\DbOperType $type
     *
     * @throws \PChouse\Gestex\Di\DiException
     * @throws \ReflectionException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendPaswordByEmail(IEscola $escola): void;
}
