<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\Helpers\Util;
use PChouse\GestexPortal\MVC\AController;
use PChouse\GestexPortal\MVC\AModel;

#[Inject]
#[Autowired]
class EscolaController extends AController implements IEscolaController
{

    public function __construct(protected IEscolaModel $escolaModel, protected IEscolaView $escolaView)
    {
        parent::__construct($this->escolaModel, $this->escolaView);
    }

    /**
     * @param string|null $action
     *
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function doAction(?string $action): void
    {
        switch ($action) {
            case "getEscola":
                $this->getEscola();
                break;
            case "update":
                $this->update();
                break;
            case "renewPassword":
                $this->renewPassword();
                break;
            default:
                parent::doAction($action);
        }
    }

    /**
     * @param array $errors
     * @param array $warnings
     * @param array $fields
     *
     * @return \PChouse\GestexPortal\MVC\Escola\IEscola
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function parseRequest(array &$errors, array &$warnings, array &$fields): IEscola
    {
        $escola = Container::get(IEscola::class);

        $formData = $this->globals->getPost();

        try {
            $escola->setAlvara($formData[EscolaModel::CL_ALVARA] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_ALVARA;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setMorada($formData[EscolaModel::CL_MORADA] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_MORADA;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setCPostal1($formData[EscolaModel::CL_C_POSTAL_1] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_C_POSTAL_1;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setCPostal2($formData[EscolaModel::CL_C_POSTAL_2] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_C_POSTAL_2;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setLocalidade($formData[EscolaModel::CL_LOCALIDADE] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_LOCALIDADE;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setTelefone1($formData[EscolaModel::CL_TELEFONE_1] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_TELEFONE_1;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setTelefone2($formData[EscolaModel::CL_TELEFONE_2] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_TELEFONE_2;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setTelefone3($formData[EscolaModel::CL_TELEFONE_3] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_TELEFONE_3;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setFax($formData[EscolaModel::CL_FAX] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_FAX;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setEmail($formData[EscolaModel::CL_EMAIL] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_EMAIL;
            $errors[] = $e->getMessage();
        }

        try {
            $escola->setWeb($formData[EscolaModel::CL_WEB] ?? "");
        } catch (\Throwable $e) {
            $this->logger->debug($e);
            $fields[] = EscolaModel::CL_WEB;
            $errors[] = $e->getMessage();
        }

        return $escola;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function update(): void
    {
        $errors   = [];
        $warnings = [];
        $fields   = [];
        try {
            $escola = $this->parseRequest($errors, $warnings, $fields);

            if (\count($errors) > 0) {
                throw new \Exception("Foram encontardos os seguintes erros");
            }

            $escola->attributes[EscolaModel::CL_ID_ESCOLA]           = 0;
            $escola->attributes[EscolaModel::CL_NOME]                = "";
            $escola->attributes[EscolaModel::CL_DESIGNACAO]          = "";
            $escola->attributes[EscolaModel::CL_C_POSTAL_LOCALIDADE] = "";
            $escola->attributes[EscolaModel::CL_CONTRIBUINTE]        = "";
            $escola->attributes[EscolaModel::CL_SOCIO]               = false;
            $escola->attributes[EscolaModel::CL_DSV]                 = "1";
            $escola->attributes[EscolaModel::CL_ACTIVO]              = false;
            $escola->attributes[EscolaModel::CL_OBS]                 = "";
            $escola->attributes[EscolaModel::CL_PORTAL]              = false;
            $escola->attributes[EscolaModel::CL_IVA_REGIME_CAIXA]    = false;

            $this->escolaModel->update($escola);

            $this->response->setError(false);
            $this->response->send();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setErrorFields($fields);
            $this->response->setMsg($e->getMessage() . ". " .\join(". ", $errors));
            if (\count($warnings) > 0) {
                $this->response->setWarning(\join(". ", $warnings));
            }
            $this->response->send();
        }
    }


    /**
     * Renew and send a new password to the user
     *
     * @return void
     * @throws \Exception
     */
    public function renewPassword(): void
    {

        try {
            $password = $this->globals->getPostKey("newPassword");
            $password2 = $this->globals->getPostKey("newPassword2");

            if (Util::isNullOrEmpty($password)) {
                throw new \Exception("A nova password não foi indicada");
            }

            if (Util::isNullOrEmpty($password2)) {
                throw new \Exception("A nova password não foi confirmada");
            }

            if (\trim($password) !== \trim($password2)) {
                throw new \Exception("Password de confirmação errada");
            }

            $this->escolaModel->changePassword($password);

            $this->response->setError(false);
            $this->response->send();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->send();
        }
    }

    /**
     * @return void
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getEscola(): void
    {
        try {
            /** @var \PChouse\GestexPortal\MVC\Escola\Escola $escola */
            $escola = $this->escolaModel->getEscola();
            $escola->setPortalPassword("");
            $escola->setCriacaoUser("");
            $escola->setAlteracaUser("");
            $escola->setObs("");
            $escola->setIdEscola(0);
            $escola->attributes[AModel::CL_ALTERACAO_DATA] = null;
            $escola->attributes[AModel::CL_CRIACAO_DATA]   = null;
            $this->escolaView->sendJson($escola);
        } catch (\Throwable $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }
}
