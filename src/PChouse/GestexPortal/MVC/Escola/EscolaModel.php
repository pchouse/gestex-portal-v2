<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Escola;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AModel;
use PChouse\GestexPortal\MVC\TModel;

#[Inject]
#[Autowired]
class EscolaModel extends AModel implements IEscolaModel
{

    use TModel;

    const CL_ID_ESCOLA           = "id_escola";
    const CL_ALVARA              = "alvara";
    const CL_NOME                = "nome";
    const CL_DESIGNACAO          = "designacao";
    const CL_MORADA              = "morada";
    const CL_C_POSTAL_1          = "cPostal1";
    const CL_C_POSTAL_2          = "cPostal2";
    const CL_C_POSTAL_LOCALIDADE = "cPostalLocalidade";
    const CL_LOCALIDADE          = "localidade";
    const CL_TELEFONE_1          = "telefone1";
    const CL_TELEFONE_2          = "telefone2";
    const CL_TELEFONE_3          = "telefone3";
    const CL_FAX                 = "fax";
    const CL_CONTRIBUINTE        = "contribuinte";
    const CL_SOCIO               = "socio";
    const CL_DSV                 = "DSV";
    const CL_ACTIVO              = "activo";
    const CL_EMAIL               = "email";
    const CL_WEB                 = "web";
    const CL_OBS                 = "obs";
    const CL_PORTAL              = "portal";
    const CL_PORTAL_PASSWORD     = "portalPassword";
    const CL_IVA_REGIME_CAIXA    = "iva_regime_caixa";

    public function __construct()
    {
        parent::__construct();

        $this->maximLength[self::CL_ID_ESCOLA]           = null;
        $this->maximLength[self::CL_ALVARA]              = 5;
        $this->maximLength[self::CL_NOME]                = 125;
        $this->maximLength[self::CL_DESIGNACAO]          = 125;
        $this->maximLength[self::CL_MORADA]              = 125;
        $this->maximLength[self::CL_C_POSTAL_1]          = 5;
        $this->maximLength[self::CL_C_POSTAL_2]          = 3;
        $this->maximLength[self::CL_C_POSTAL_LOCALIDADE] = 50;
        $this->maximLength[self::CL_LOCALIDADE]          = 50;
        $this->maximLength[self::CL_TELEFONE_1]          = 20;
        $this->maximLength[self::CL_TELEFONE_2]          = 20;
        $this->maximLength[self::CL_TELEFONE_3]          = 20;
        $this->maximLength[self::CL_FAX]                 = 20;
        $this->maximLength[self::CL_CONTRIBUINTE]        = 10;
        $this->maximLength[self::CL_SOCIO]               = null;
        $this->maximLength[self::CL_DSV]                 = 1;
        $this->maximLength[self::CL_ACTIVO]              = null;
        $this->maximLength[self::CL_EMAIL]               = 50;
        $this->maximLength[self::CL_WEB]                 = 125;
        $this->maximLength[self::CL_OBS]                 = 65536;
        $this->maximLength[self::CL_PORTAL]              = null;
        $this->maximLength[self::CL_PORTAL_PASSWORD]     = 125;
        $this->maximLength[self::CL_IVA_REGIME_CAIXA]    = null;

        $this->precision[self::CL_ID_ESCOLA]           = 0;
        $this->precision[self::CL_ALVARA]              = 0;
        $this->precision[self::CL_NOME]                = 0;
        $this->precision[self::CL_DESIGNACAO]          = 0;
        $this->precision[self::CL_MORADA]              = 0;
        $this->precision[self::CL_C_POSTAL_1]          = 0;
        $this->precision[self::CL_C_POSTAL_2]          = 0;
        $this->precision[self::CL_C_POSTAL_LOCALIDADE] = 0;
        $this->precision[self::CL_LOCALIDADE]          = 0;
        $this->precision[self::CL_TELEFONE_1]          = 0;
        $this->precision[self::CL_TELEFONE_2]          = 0;
        $this->precision[self::CL_TELEFONE_3]          = 0;
        $this->precision[self::CL_FAX]                 = 0;
        $this->precision[self::CL_CONTRIBUINTE]        = 0;
        $this->precision[self::CL_SOCIO]               = 0;
        $this->precision[self::CL_DSV]                 = 0;
        $this->precision[self::CL_ACTIVO]              = 0;
        $this->precision[self::CL_EMAIL]               = 0;
        $this->precision[self::CL_WEB]                 = 0;
        $this->precision[self::CL_OBS]                 = 0;
        $this->precision[self::CL_PORTAL]              = 0;
        $this->precision[self::CL_PORTAL_PASSWORD]     = 0;
        $this->precision[self::CL_IVA_REGIME_CAIXA]    = 0;

        $this->dataModel[self::CL_ID_ESCOLA]           = null;
        $this->dataModel[self::CL_ALVARA]              = null;
        $this->dataModel[self::CL_NOME]                = null;
        $this->dataModel[self::CL_DESIGNACAO]          = null;
        $this->dataModel[self::CL_MORADA]              = null;
        $this->dataModel[self::CL_C_POSTAL_1]          = null;
        $this->dataModel[self::CL_C_POSTAL_2]          = null;
        $this->dataModel[self::CL_C_POSTAL_LOCALIDADE] = null;
        $this->dataModel[self::CL_LOCALIDADE]          = null;
        $this->dataModel[self::CL_TELEFONE_1]          = null;
        $this->dataModel[self::CL_TELEFONE_2]          = null;
        $this->dataModel[self::CL_TELEFONE_3]          = null;
        $this->dataModel[self::CL_FAX]                 = null;
        $this->dataModel[self::CL_CONTRIBUINTE]        = null;
        $this->dataModel[self::CL_SOCIO]               = null;
        $this->dataModel[self::CL_DSV]                 = null;
        $this->dataModel[self::CL_ACTIVO]              = null;
        $this->dataModel[self::CL_EMAIL]               = null;
        $this->dataModel[self::CL_WEB]                 = null;
        $this->dataModel[self::CL_OBS]                 = null;
        $this->dataModel[self::CL_PORTAL]              = null;
        $this->dataModel[self::CL_PORTAL_PASSWORD]     = null;
        $this->dataModel[self::CL_IVA_REGIME_CAIXA]    = null;

        $this->types[self::CL_ID_ESCOLA]           = AModel::DB_INT;
        $this->types[self::CL_ALVARA]              = AModel::DB_STRING;
        $this->types[self::CL_NOME]                = AModel::DB_STRING;
        $this->types[self::CL_DESIGNACAO]          = AModel::DB_STRING;
        $this->types[self::CL_MORADA]              = AModel::DB_STRING;
        $this->types[self::CL_C_POSTAL_1]          = AModel::DB_STRING;
        $this->types[self::CL_C_POSTAL_2]          = AModel::DB_STRING;
        $this->types[self::CL_C_POSTAL_LOCALIDADE] = AModel::DB_STRING;
        $this->types[self::CL_LOCALIDADE]          = AModel::DB_STRING;
        $this->types[self::CL_TELEFONE_1]          = AModel::DB_STRING;
        $this->types[self::CL_TELEFONE_2]          = AModel::DB_STRING;
        $this->types[self::CL_TELEFONE_3]          = AModel::DB_STRING;
        $this->types[self::CL_FAX]                 = AModel::DB_STRING;
        $this->types[self::CL_CONTRIBUINTE]        = AModel::DB_STRING;
        $this->types[self::CL_SOCIO]               = AModel::DB_BOOLEAN;
        $this->types[self::CL_DSV]                 = AModel::DB_STRING;
        $this->types[self::CL_ACTIVO]              = AModel::DB_BOOLEAN;
        $this->types[self::CL_EMAIL]               = AModel::DB_STRING;
        $this->types[self::CL_WEB]                 = AModel::DB_STRING;
        $this->types[self::CL_OBS]                 = AModel::DB_STRING;
        $this->types[self::CL_PORTAL]              = AModel::DB_BOOLEAN;
        $this->types[self::CL_PORTAL_PASSWORD]     = AModel::DB_STRING;
        $this->types[self::CL_IVA_REGIME_CAIXA]    = AModel::DB_BOOLEAN;

        $this->allowNull[self::CL_ID_ESCOLA]           = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_ALVARA]              = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_NOME]                = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_DESIGNACAO]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_MORADA]              = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_C_POSTAL_1]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_C_POSTAL_2]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_C_POSTAL_LOCALIDADE] = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_LOCALIDADE]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_TELEFONE_1]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_TELEFONE_2]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_TELEFONE_3]          = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_FAX]                 = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_CONTRIBUINTE]        = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_SOCIO]               = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_DSV]                 = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_ACTIVO]              = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_EMAIL]               = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_WEB]                 = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_OBS]                 = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_PORTAL]              = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_PORTAL_PASSWORD]     = AModel::NULL_NOT_ALLOW;
        $this->allowNull[self::CL_IVA_REGIME_CAIXA]    = AModel::NULL_NOT_ALLOW;
    }

    public function getMvcObjectName(): string
    {
        return IEscola::class;
    }

    public function getIdColumnName(): string
    {
        return EscolaModel::CL_ID_ESCOLA;
    }

    public function getTableName(): string
    {
        return "escolas";
    }


    /**
     * Change the password, the new password wil be the one in field rawPassword
     *
     * @param string $password
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function changePassword(string $password): void
    {
        $jsonBody     = \json_encode([
            "username" => "",
            "password" => $password
        ]);
        $response = Container::get(Client::class)->put(
            "Escola/Password",
            ["body" => $jsonBody]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        $responseBody     = $response->getBody()->getContents();
        $stdClassResponse = \json_decode($responseBody, false);

        if ((string)$stdClassResponse->status !== "OK") {
            throw new \Exception(
                (string)$stdClassResponse->error->description
            );
        }
    }

    /**
     * @throws \Rebelo\Decimal\DecimalException
     * @throws \Rebelo\Date\DateParseException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getEscola(): IEscola
    {
        $response = Container::get(Client::class)->get("Escola");

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        $responseBody     = $response->getBody()->getContents();
        $stdClassResponse = \json_decode($responseBody, false);

        $escola = Container::get(IEscola::class);

        if ((string)$stdClassResponse->status === "OK") {
            $this->parseStdClass($stdClassResponse->instance, $escola);
        }

        return $escola;
    }

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function update(IEscola $escola): void
    {

        $args         = [];
        $args["body"] = $escola->toJson();
        $response     = Container::get(Client::class)->put(
            "Escola",
            $args
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        $responseBody     = $response->getBody()->getContents();
        $stdClassResponse = \json_decode($responseBody, false);

        if ((string)$stdClassResponse->status !== "OK") {
            throw new \Exception(
                (string)$stdClassResponse->error->description
            );
        }
    }
}
