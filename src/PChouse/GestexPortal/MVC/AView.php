<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AView extends AMvc
{

    #[Inject]
    protected IResponseHttpHeader $responseHttpHeader;

    /**
     *
     * The default twig environment for the MVC view
     *
     * @var \Twig\Environment
     */
    #[Inject]
    protected Environment $twigEnvironment;

    /**
     * The default Twig loader for the MVC view
     *
     * @var \Twig\Loader\FilesystemLoader
     */
    protected FilesystemLoader $twigLoader;


    protected array $searchFields = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Executed before the DI Container return the instance
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Exception
     */
    public function beforeReturnInstance(): void
    {
        parent::beforeReturnInstance();

        /** @noinspection All */
        /** @phpstan-ignore-next-line */
        $this->twigLoader = $this->twigEnvironment->getLoader();

        $mvcHtmlPath = \realpath(
            $this->getMvcPath() . DIRECTORY_SEPARATOR . "HTML"
        );

        if ($mvcHtmlPath) {
            /** @phpstan-ignore-next-line */
            $this->twigLoader->addPath($mvcHtmlPath);
        }
    }

    /**
     * @return \PChouse\GestexPortal\Helpers\IResponseHttpHeader
     */
    public function getResponseHttpHeader(): IResponseHttpHeader
    {
        return $this->responseHttpHeader;
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->twigEnvironment;
    }

    /**
     * @return \Twig\Loader\FilesystemLoader
     */
    public function getTwigLoader(): FilesystemLoader
    {
        return $this->twigLoader;
    }

    /**
     * @throws \Exception
     */
    public function renderTab(): void
    {
        $this->responseHttpHeader->sendTextHtml();
        echo "";
    }

    /**
     * @param array $rows
     *
     * @return void
     * @throws \Exception
     */
    public function dataRowsAsJson(array $rows): void
    {
        $this->responseHttpHeader->sendApplicationJson();
        $stack = [];
        foreach ($rows as $object) {
            $stack[] = $object->toJson(true);
        }
        $json = \sprintf("[%s]", \join(",", $stack));
        echo $json;
    }

    /**
     * @param array $rows
     * @param int $lastPage
     *
     * @return void
     * @throws \Exception
     */
    public function dataRowsProgressiveLoadAsJson(array $rows, int $lastPage): void
    {
        $this->responseHttpHeader->sendApplicationJson();
        $stack = [];
        foreach ($rows as $object) {
            $stack[] = $object->toJson(true);
        }
        $json = \sprintf('{"last_page": %s, "data": [%s]}', $lastPage, \join(",", $stack));
        echo $json;
    }

    /**
     * @param \PChouse\GestexPortal\MVC\IObject|null $iObject
     *
     * @return void
     * @throws \Exception
     */
    public function sendJson(IObject|null $iObject): void
    {
        if ($iObject === null) {
            $this->responseHttpHeader->sendNoContentApplicationJson();
            return;
        }
        $this->responseHttpHeader->sendApplicationJson();
        echo $iObject->toJson(true);
    }


    /**
     * Overwrite this method to send the Modal form
     *
     * @throws \Exception
     */
    public function renderModalForm(): void
    {
        $varTwig = [];
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('modalForm.twig', $varTwig);
    }

    public function addSearchToVarTwig(array &$varTwig): void
    {

        if (\count($this->searchFields) === 0) {
            return;
        }

        $varTwig["searchFields"] = $this->searchFields;

        $varTwig["searchType"] = [
            "="      => "Igual a",
            "!="     => "Diferente de",
            "<"      => "Menor que",
            "<="     => "Menor ou igual a",
            ">"      => "Maior que",
            ">="     => "Maior ou igual",
            "like"   => "Contém",
            "starts" => "Inicia com",
            "ends"   => "Termina com",
            "regex"  => "Expressão regular",
            //"in" => "Em"
        ];
    }

    /**
     * Overwrite this method to send the Modal form
     *
     * @throws \Exception
     */
    public function renderModalTable(): void
    {
        $varTwig = [];
        $this->addSearchToVarTwig($varTwig);
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('modalTable.twig', $varTwig);
    }


    /**
     * Overwrite this method to send the Modal form
     *
     * @throws \Exception
     */
    public function renderTabTable(string $prefix): void
    {
        $varTwig           = [];
        $varTwig["prefix"] = $prefix;
        $this->addSearchToVarTwig($varTwig);
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('tabTable.twig', $varTwig);
    }

    /**
     * Overwrite this method to send the Modal form
     *
     * @throws \Exception
     */
    public function renderForm(): void
    {
        $varTwig = [];
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('form.twig', $varTwig);
    }

    /**
     * Render the cancel form modal dialog
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function renderCancelFormModal(): void
    {
        $varTwig['title']            = "Fechar formulário";
        $varTwig['modalId']          = "confirmDialog";
        $varTwig['modalBody']        = "Os valores foram alterados e ainda não formam gravados. " .
            "Tem a certeza que pretende fechar?";
        $varTwig['confirmDialogYes'] = "Sim";
        $varTwig['confirmDialogNo']  = "Não";
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('confirmDialog.twig', $varTwig);
    }

    /**
     * render the deletation confirmayion dialog
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function renderConfirmDeleteModal(): void
    {
        $varTwig['title']            = "Eliminar";
        $varTwig['modalId']          = "confirmDialog";
        $varTwig['modalBody']        = "Tem a certeza que pretende eliminar?";
        $varTwig['confirmDialogYes'] = "Sim";
        $varTwig['confirmDialogNo']  = "Não";
        $this->getResponseHttpHeader()->sendTextHtml();
        $this->twigEnvironment->display('confirmDialog.twig', $varTwig);
    }

    /**
     * @param string|int|float|array|null $value
     *
     * @return void
     * @throws \Exception
     */
    public function sendValue(string|int|float|array|null $value): void
    {
        $this->responseHttpHeader->sendApplicationJson();

        if (\is_string($value)) {
            echo \sprintf('"%s"', $value);
            return;
        }

        echo \is_array($value) ? \json_encode($value) : $value;
    }

    /**
     * @param string $gridResponse
     *
     * @return void
     * @throws \Exception
     */
    public function sendGridResponse(string $gridResponse): void
    {
        $this->responseHttpHeader->sendApplicationJson();
        echo $gridResponse;
    }
}
