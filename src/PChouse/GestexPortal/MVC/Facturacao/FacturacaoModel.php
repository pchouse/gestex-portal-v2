<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Facturacao;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AModel;

#[Inject]
#[Autowired]
class FacturacaoModel extends AModel implements IFacturacaoModel
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function getMvcObjectName(): string
    {
        throw new \Exception("NOt implemented");
    }

    /**
     * @throws \Exception
     */
    public function getIdColumnName(): string
    {
        throw new \Exception("NOt implemented");
    }

    /**
     * @throws \Exception
     */
    public function getTableName(): string
    {
        throw new \Exception("NOt implemented");
    }

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridData(string $json): string
    {
        $response = Container::get(Client::class)->post(
            "Faturacao/GridData/Fatura",
            [ "body" =>  $json]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }


    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridDataChild(string $json): string
    {
        $response = Container::get(Client::class)->post(
            "Faturacao/GridData/Fatura/Itens",
            [ "body" =>  $json]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

}
