<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Dashboard;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AModel;

#[Inject]
#[Autowired]
class DashboardModel extends AModel implements IDashboardModel
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
    public function getInvoices(): string
    {
        $response = Container::get(Client::class)->get("Dashboard/Invoices");

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
    public function getCategoria(): string
    {
        $response = Container::get(Client::class)->get("Dashboard/Categoria");

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
    public function getResultadoPratico(): string
    {
        $response = Container::get(Client::class)->get("Dashboard/ResultadoPratico");

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
    public function getResultadoTeorico(): string
    {
        $response = Container::get(Client::class)->get("Dashboard/ResultadoTeorico");

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }
}
