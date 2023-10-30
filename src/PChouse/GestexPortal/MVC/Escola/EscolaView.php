<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use Laminas\Mail\Address;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Laminas\Validator\EmailAddress;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\HtmlMimePart;
use PChouse\GestexPortal\MVC\AView;

#[Inject]
#[Autowired]
class EscolaView extends AView implements IEscolaView
{

    public function __construct()
    {
        parent::__construct();
        $this->searchFields[EscolaModel::CL_ALVARA]              = "Alvará";
        $this->searchFields[EscolaModel::CL_NOME]                = "Nome";
        $this->searchFields[EscolaModel::CL_DESIGNACAO]          = "Designação";
        $this->searchFields[EscolaModel::CL_MORADA]              = "Morada";
        $this->searchFields[EscolaModel::CL_C_POSTAL_1]          = "Código postal 1";
        $this->searchFields[EscolaModel::CL_C_POSTAL_2]          = "Código postal 2";
        $this->searchFields[EscolaModel::CL_C_POSTAL_LOCALIDADE] = "Código postal localidade";
        $this->searchFields[EscolaModel::CL_LOCALIDADE]          = "Localidade";
        $this->searchFields[EscolaModel::CL_TELEFONE_1]          = "Telefone 1";
        $this->searchFields[EscolaModel::CL_TELEFONE_2]          = "Telefone 2";
        $this->searchFields[EscolaModel::CL_TELEFONE_3]          = "Telefone 3";
        $this->searchFields[EscolaModel::CL_FAX]                 = "Fax";
        $this->searchFields[EscolaModel::CL_CONTRIBUINTE]        = "Contribuinte";
        $this->searchFields[EscolaModel::CL_SOCIO]               = "Sócio";
        $this->searchFields[EscolaModel::CL_DSV]                 = "DSV";
        $this->searchFields[EscolaModel::CL_ACTIVO]              = "Activo";
        $this->searchFields[EscolaModel::CL_EMAIL]               = "Email";
        $this->searchFields[EscolaModel::CL_WEB]                 = "Web";
        $this->searchFields[EscolaModel::CL_OBS]                 = "Observações";
        $this->searchFields[EscolaModel::CL_PORTAL]              = "Portal";
        $this->searchFields[EscolaModel::CL_IVA_REGIME_CAIXA]    = "Iva regime de caixa";
    }

    /**
     * @param \PChouse\GestexPortal\MVC\IObject[] $rows
     *
     * @return void
     * @throws \Exception
     */
    public function dataRowsAsJson(array $rows): void
    {
        $this->responseHttpHeader->sendApplicationJson();
        $stack = [];
        foreach ($rows as $object) {
            $object->unsetAttribute(EscolaModel::CL_PORTAL_PASSWORD);
            $stack[] = $object->toJson(true);
        }
        $json = \sprintf("[%s]", \join(",", $stack));
        echo $json;
    }

    /**
     * @param \PChouse\GestexPortal\MVC\Escola\IEscola $escola
     *
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     * @throws \Throwable
     */
    public function sendPaswordByEmail(IEscola $escola): void
    {
        if (!Container::get(EmailAddress::class)->isValid($escola->getEmail())) {
            throw new \Exception("O email para o envio da password não é válido");
        }

        $modules               = [];
        $modules["utilizador"] = $escola->getAlvara();
        $modules["password"]   = $escola->getRawPassword();

        $html = $this->twigEnvironment->render("portalPasswordEmail.twig", $modules);

        $body = Container::get(HtmlMimePart::class);
        $body->setContent($html);

        $message = Container::get(Message::class);

        $message->setBody(
            (new \Laminas\Mime\Message())->setParts([$body])
        );

        $message->setTo(new Address($escola->getEmail()));
        $message->setSubject("Acesso ao portal APEC no Gestex");

        Container::get(Smtp::class)->send($message);
    }
}
