<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use Laminas\Validator\EmailAddress;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\Util;
use PChouse\GestexPortal\MVC\AObject;
use PChouse\GestexPortal\MVC\TObject;

#[Inject]
#[Autowired]
class Escola extends AObject implements IEscola
{
    use TObject;

    /**
     * The raw password
     *
     * @var string|null
     */
    protected ?string $rawPassword = null;

    public function __construct(protected IEscolaModel $escolaDocCabecalhoModel)
    {
        parent::__construct($escolaDocCabecalhoModel);
    }

    /**
     * @param int $idEscola
     *
     * @return $this
     */
    public function setIdEscola(int $idEscola): self
    {
        $column                    = EscolaModel::CL_ID_ESCOLA;
        $this->attributes[$column] = $idEscola;
        $this->logger->debug($column . ' set to ' . $idEscola);
        return $this;
    }

    /**
     * @return int
     */
    public function getIdEscola(): int
    {
        return $this->attributes[EscolaModel::CL_ID_ESCOLA];
    }

    /**
     * @param string $alvara
     *
     * @return $this
     * @throws \Exception
     */
    public function setAlvara(string $alvara): self
    {
        if (Util::isNullOrEmpty($alvara)) {
            throw new \Exception("O alvará não pode estra vazio");
        }
        $column = EscolaModel::CL_ALVARA;
        $this->testModelRegExp($column, $alvara);
        $this->attributes[$column] = \trim($alvara);
        $this->logger->debug($column . ' set to ' . $alvara);
        return $this;
    }

    /**
     * @return string
     */
    public function getAlvara(): string
    {
        return $this->attributes[EscolaModel::CL_ALVARA];
    }

    /**
     * @param string $nome
     *
     * @return $this
     * @throws \Exception
     */
    public function setNome(string $nome): self
    {
        if (Util::isNullOrEmpty($nome)) {
            throw new \Exception("O nome não pode estra vazio");
        }
        $column = EscolaModel::CL_NOME;
        $this->testModelRegExp($column, $nome);
        $this->attributes[$column] = \strtoupper(\trim($nome));
        $this->logger->debug($column . ' set to ' . $nome);
        return $this;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->attributes[EscolaModel::CL_NOME];
    }

    /**
     * @param string $designacao
     *
     * @return $this
     * @throws \Exception
     */
    public function setDesignacao(string $designacao): self
    {
        if (Util::isNullOrEmpty($designacao)) {
            throw new \Exception("A designação não pode estra vazio");
        }
        $column = EscolaModel::CL_DESIGNACAO;
        $this->testModelRegExp($column, $designacao);
        $this->attributes[$column] = \strtoupper(\trim($designacao));
        $this->logger->debug($column . ' set to ' . $designacao);
        return $this;
    }

    /**
     * @return string
     */
    public function getDesignacao(): string
    {
        return $this->attributes[EscolaModel::CL_DESIGNACAO];
    }

    /**
     * @param string $morada
     *
     * @return $this
     * @throws \Exception
     */
    public function setMorada(string $morada): self
    {
        $column = EscolaModel::CL_MORADA;
        $this->testModelRegExp($column, $morada);
        $this->attributes[$column] = \strtoupper(\trim($morada));
        $this->logger->debug($column . ' set to ' . $morada);
        return $this;
    }

    /**
     * @return string
     */
    public function getMorada(): string
    {
        return $this->attributes[EscolaModel::CL_MORADA];
    }

    /**
     * @param string $cPostal1
     *
     * @return $this
     * @throws \Exception
     */
    public function setCPostal1(string $cPostal1): self
    {
        $column = EscolaModel::CL_C_POSTAL_1;
        $this->testModelRegExp($column, $cPostal1);
        $this->attributes[$column] = \strtoupper(\trim($cPostal1));
        $this->logger->debug($column . ' set to ' . $cPostal1);
        return $this;
    }

    /**
     * @return string
     */
    public function getCPostal1(): string
    {
        return $this->attributes[EscolaModel::CL_C_POSTAL_1];
    }

    /**
     * @param string $cPostal2
     *
     * @return $this
     * @throws \Exception
     */
    public function setCPostal2(string $cPostal2): self
    {
        $column = EscolaModel::CL_C_POSTAL_2;
        $this->testModelRegExp($column, $cPostal2);
        $this->attributes[$column] = \strtoupper(\trim($cPostal2));
        $this->logger->debug($column . ' set to ' . $cPostal2);
        return $this;
    }

    /**
     * @return string
     */
    public function getCPostal2(): string
    {
        return $this->attributes[EscolaModel::CL_C_POSTAL_2];
    }

    /**
     * @param string $cPostalLocalidade
     *
     * @return $this
     * @throws \Exception
     */
    public function setCPostalLocalidade(string $cPostalLocalidade): self
    {
        $column = EscolaModel::CL_C_POSTAL_LOCALIDADE;
        $this->testModelRegExp($column, $cPostalLocalidade);
        $this->attributes[$column] = \strtoupper(\trim($cPostalLocalidade));
        $this->logger->debug($column . ' set to ' . $cPostalLocalidade);
        return $this;
    }

    /**
     * @return string
     */
    public function getCPostalLocalidade(): string
    {
        return $this->attributes[EscolaModel::CL_C_POSTAL_LOCALIDADE];
    }

    /**
     * @param string $localidade
     *
     * @return $this
     * @throws \Exception
     */
    public function setLocalidade(string $localidade): self
    {
        $column = EscolaModel::CL_LOCALIDADE;
        $this->testModelRegExp($column, $localidade);
        $this->attributes[$column] = \strtoupper(\trim($localidade));
        $this->logger->debug($column . ' set to ' . $localidade);
        return $this;
    }

    /**
     * @return string
     */
    public function getLocalidade(): string
    {
        return $this->attributes[EscolaModel::CL_LOCALIDADE];
    }

    /**
     * @param string $telefone
     *
     * @return $this
     * @throws \Exception
     */
    public function setTelefone1(string $telefone): self
    {
        $column = EscolaModel::CL_TELEFONE_1;
        $this->testModelRegExp($column, $telefone);
        $this->attributes[$column] = \strtoupper(\trim($telefone));
        $this->logger->debug($column . ' set to ' . $telefone);
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone1(): string
    {
        return $this->attributes[EscolaModel::CL_TELEFONE_1];
    }

    /**
     * @param string $telefone
     *
     * @return $this
     * @throws \Exception
     */
    public function setTelefone2(string $telefone): self
    {
        $column = EscolaModel::CL_TELEFONE_2;
        $this->testModelRegExp($column, $telefone);
        $this->attributes[$column] = \strtoupper(\trim($telefone));
        $this->logger->debug($column . ' set to ' . $telefone);
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone2(): string
    {
        return $this->attributes[EscolaModel::CL_TELEFONE_2];
    }

    /**
     * @param string $telefone
     *
     * @return $this
     * @throws \Exception
     */
    public function setTelefone3(string $telefone): self
    {
        $column = EscolaModel::CL_TELEFONE_3;
        $this->testModelRegExp($column, $telefone);
        $this->attributes[$column] = \strtoupper(\trim($telefone));
        $this->logger->debug($column . ' set to ' . $telefone);
        return $this;
    }

    /**
     * @return string
     */
    public function getTelefone3(): string
    {
        return $this->attributes[EscolaModel::CL_TELEFONE_3];
    }


    /**
     * @param string $fax
     *
     * @return $this
     * @throws \Exception
     */
    public function setFax(string $fax): self
    {
        $column = EscolaModel::CL_FAX;
        $this->testModelRegExp($column, $fax);
        $this->attributes[$column] = \strtoupper(\trim($fax));
        $this->logger->debug($column . ' set to ' . $fax);
        return $this;
    }

    /**
     * @return string
     */
    public function getFax(): string
    {
        return $this->attributes[EscolaModel::CL_FAX];
    }

    /**
     * @param string $contribuinte
     *
     * @return $this
     * @throws \Exception
     */
    public function setContribuinte(string $contribuinte): self
    {
        $column = EscolaModel::CL_CONTRIBUINTE;
        $this->testModelRegExp($column, $contribuinte);
        $this->attributes[$column] = \strtoupper(\trim($contribuinte));
        $this->logger->debug($column . ' set to ' . $contribuinte);
        return $this;
    }

    /**
     * @return string
     */
    public function getContribuinte(): string
    {
        return $this->attributes[EscolaModel::CL_CONTRIBUINTE];
    }

    /**
     * @param bool $socio
     *
     * @return $this
     */
    public function setSocio(bool $socio): self
    {
        $column                    = EscolaModel::CL_SOCIO;
        $this->attributes[$column] = $socio;
        $this->logger->debug($column . ' set to ' . $socio);
        return $this;
    }

    /**
     * @return bool
     */
    public function getSocio(): bool
    {
        return $this->attributes[EscolaModel::CL_SOCIO];
    }

    /**
     * @param string $dsv
     *
     * @return $this
     * @throws \Exception
     */
    public function setDSV(string $dsv): self
    {
        $column                    = EscolaModel::CL_DSV;
        $this->testModelRegExp($column, $dsv);
        $this->attributes[$column] = \strtoupper(\trim($dsv));
        $this->logger->debug($column . ' set to ' . $dsv);
        return $this;
    }

    /**
     * @return string
     */
    public function getDsv(): string
    {
        return $this->attributes[EscolaModel::CL_DSV];
    }

    /**
     * @param bool $activo
     *
     * @return $this
     */
    public function setActivo(bool $activo): self
    {
        $column                    = EscolaModel::CL_ACTIVO;
        $this->attributes[$column] = $activo;
        $this->logger->debug($column . ' set to ' . $activo);
        return $this;
    }

    /**
     * @return bool
     */
    public function getActivo(): bool
    {
        return $this->attributes[EscolaModel::CL_ACTIVO];
    }

    /**
     * @param string $email
     *
     * @return $this
     * @throws \Exception
     * @throws \Throwable
     */
    public function setEmail(string $email): self
    {
        if (Util::isNotNullOrEmpty($email) && !Container::get(EmailAddress::class)->isValid($email)) {
            throw new \Exception("O email não é válido");
        }
        $column = EscolaModel::CL_EMAIL;
        $this->testModelRegExp($column, $email);
        $this->attributes[$column] = \trim($email);
        $this->logger->debug($column . ' set to ' . $email);
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->attributes[EscolaModel::CL_EMAIL];
    }


    /**
     * @param string $web
     *
     * @return $this
     * @throws \Exception
     */
    public function setWeb(string $web): self
    {
        $column = EscolaModel::CL_WEB;
        $this->testModelRegExp($column, $web);
        $this->attributes[$column] = \trim($web);
        $this->logger->debug($column . ' set to ' . $web);
        return $this;
    }

    /**
     * @return string
     */
    public function getWeb(): string
    {
        return $this->attributes[EscolaModel::CL_WEB];
    }

    /**
     * @param string $obs
     *
     * @return $this
     * @throws \Exception
     */
    public function setObs(string $obs): self
    {
        $column = EscolaModel::CL_OBS;
        $this->testModelRegExp($column, $obs);
        $this->attributes[$column] = \strtoupper(\trim($obs));
        $this->logger->debug($column . ' set to ' . $obs);
        return $this;
    }

    /**
     * @return string
     */
    public function getObs(): string
    {
        return $this->attributes[EscolaModel::CL_OBS];
    }

    /**
     * @param bool $portal
     *
     * @return $this
     */
    public function setPortal(bool $portal): self
    {
        $column                    = EscolaModel::CL_PORTAL;
        $this->attributes[$column] = $portal;
        $this->logger->debug($column . ' set to ' . $portal);
        return $this;
    }

    /**
     * @return bool
     */
    public function getPortal(): bool
    {
        return $this->attributes[EscolaModel::CL_PORTAL];
    }

    /**
     * @param string $portalPassword
     *
     * @return $this
     * @throws \Exception
     */
    public function setPortalPassword(string $portalPassword): self
    {
        $column = EscolaModel::CL_PORTAL_PASSWORD;
        $this->testModelRegExp($column, $portalPassword);
        $this->attributes[$column] = \trim($portalPassword);
        $this->logger->debug($column . ' set to ' . $portalPassword);
        return $this;
    }

    /**
     * @return string
     */
    public function getPortalPassword(): string
    {
        return $this->attributes[EscolaModel::CL_PORTAL_PASSWORD];
    }

    /**
     * @param bool $ivaRegimeCaixa
     *
     * @return $this
     */
    public function setIvaRegimeCaixa(bool $ivaRegimeCaixa): self
    {
        $column                    = EscolaModel::CL_IVA_REGIME_CAIXA;
        $this->attributes[$column] = $ivaRegimeCaixa;
        $this->logger->debug($column . ' set to ' . $ivaRegimeCaixa);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIvaRegimeCaixa(): bool
    {
        return $this->attributes[EscolaModel::CL_IVA_REGIME_CAIXA];
    }

    /**
     * Set raw password
     *
     * @param string $password
     *
     * @return $this
     */
    public function setRawPassword(string $password): self
    {
        $this->rawPassword = $password;
        return $this;
    }

    /**
     *
     * Get raw password
     *
     * @return string|null
     */
    public function getRawPassword(): ?string
    {
        return $this->rawPassword;
    }

    /**
     * Create the hashed password
     *
     * @param string $password
     *
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return \md5($password);
    }
}
