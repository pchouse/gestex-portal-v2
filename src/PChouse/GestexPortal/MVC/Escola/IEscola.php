<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use PChouse\GestexPortal\MVC\IObject;

interface IEscola extends IObject
{

    /**
     * @param string $cPostal2
     *
     * @return $this
     * @throws \Exception
     */
    public function setCPostal2(string $cPostal2): self;

    /**
     * @param string $designacao
     *
     * @return $this
     * @throws \Exception
     */
    public function setDesignacao(string $designacao): self;

    /**
     * @param int $idEscola
     *
     * @return $this
     */
    public function setIdEscola(int $idEscola): self;

    /**
     * @param string $web
     *
     * @return $this
     * @throws \Exception
     */
    public function setWeb(string $web): self;

    /**
     * @return string
     */
    public function getTelefone3(): string;

    /**
     * @return string
     */
    public function getDsv(): string;

    /**
     * @return bool
     */
    public function getActivo(): bool;

    /**
     * @return string
     */
    public function getWeb(): string;

    /**
     * @param string $obs
     *
     * @return $this
     * @throws \Exception
     */
    public function setObs(string $obs): self;

    /**
     * @return string
     */
    public function getCPostal1(): string;

    /**
     * @param string $cPostal1
     *
     * @return $this
     * @throws \Exception
     */
    public function setCPostal1(string $cPostal1): self;

    /**
     * @return string
     */
    public function getFax(): string;

    /**
     * @param string $alvara
     *
     * @return $this
     * @throws \Exception
     */
    public function setAlvara(string $alvara): self;

    /**
     * @param string $nome
     *
     * @return $this
     * @throws \Exception
     */
    public function setNome(string $nome): self;

    /**
     * @return int
     */
    public function getIdEscola(): int;

    /**
     * @return string
     */
    public function getAlvara(): string;

    /**
     * @param string $telefone
     *
     * @return $this
     * @throws \Exception
     */
    public function setTelefone2(string $telefone): self;

    /**
     * @param string $telefone
     *
     * @return $this
     * @throws \Exception
     */
    public function setTelefone3(string $telefone): self;

    /**
     * @return string
     */
    public function getNome(): string;

    /**
     * @param string $telefone
     *
     * @return $this
     * @throws \Exception
     */
    public function setTelefone1(string $telefone): self;

    /**
     * @return string
     */
    public function getObs(): string;

    /**
     * @param bool $socio
     *
     * @return $this
     */
    public function setSocio(bool $socio): self;

    /**
     * @return string
     */
    public function getTelefone1(): string;

    /**
     * @return string
     */
    public function getDesignacao(): string;

    /**
     * @param string $portalPassword
     *
     * @return $this
     * @throws \Exception
     */
    public function setPortalPassword(string $portalPassword): self;

    /**
     * @param string $localidade
     *
     * @return $this
     * @throws \Exception
     */
    public function setLocalidade(string $localidade): self;

    /**
     * @param string $fax
     *
     * @return $this
     * @throws \Exception
     */
    public function setFax(string $fax): self;

    /**
     * @param string $email
     *
     * @return $this
     * @throws \Exception
     */
    public function setEmail(string $email): self;

    /**
     * @return string
     */
    public function getCPostal2(): string;

    /**
     * @return string
     */
    public function getPortalPassword(): string;

    /**
     * @param bool $portal
     *
     * @return $this
     */
    public function setPortal(bool $portal): self;

    /**
     * @param string $morada
     *
     * @return $this
     * @throws \Exception
     */
    public function setMorada(string $morada): self;

    /**
     * @return bool
     */
    public function getSocio(): bool;

    /**
     * @return string
     */
    public function getTelefone2(): string;

    /**
     * @param string $dsv
     *
     * @return $this
     */
    public function setDSV(string $dsv): self;

    /**
     * @return string
     */
    public function getContribuinte(): string;

    /**
     * @param string $contribuinte
     *
     * @return $this
     * @throws \Exception
     */
    public function setContribuinte(string $contribuinte): self;

    /**
     * @return bool
     */
    public function getPortal(): bool;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getMorada(): string;

    /**
     * @param bool $activo
     *
     * @return $this
     */
    public function setActivo(bool $activo): self;

    /**
     * @return string
     */
    public function getLocalidade(): string;

    /**
     * @param string $cPostalLocalidade
     *
     * @return $this
     * @throws \Exception
     */
    public function setCPostalLocalidade(string $cPostalLocalidade): self;

    /**
     * @return string
     */
    public function getCPostalLocalidade(): string;

    /**
     * @return bool
     */
    public function getIvaRegimeCaixa(): bool;

    /**
     * @param bool $ivaRegimeCaixa
     *
     * @return $this
     */
    public function setIvaRegimeCaixa(bool $ivaRegimeCaixa): self;

    /**
     * Create the hashed password
     *
     * @param string $password
     *
     * @return string
     */
    public function hashPassword(string $password): string;

    /**
     * Set raw password
     *
     * @param string $password
     *
     * @return \PChouse\GestexPortal\MVC\Escola\IEscola
     */
    public function setRawPassword(string $password): self;

    /**
     *
     * Get raw password
     *
     * @return string|null
     */
    public function getRawPassword(): ?string;
}
