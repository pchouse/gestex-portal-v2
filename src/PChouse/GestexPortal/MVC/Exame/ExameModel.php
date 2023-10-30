<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Exame;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AModel;
use Rebelo\Date\Date;

#[Inject]
#[Autowired]
class ExameModel extends AModel implements IExameModel
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
            "Exame/GridData",
            ["body" => $json]
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
    public function getGridDataTeoricos(string $json): string
    {
        $response = Container::get(Client::class)->post(
            "Exame/GridData/Teoricos",
            ["body" => $json]
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
    public function getGridDataPraticos(string $json): string
    {
        $response = Container::get(Client::class)->post(
            "Exame/GridData/Praticos",
            ["body" => $json]
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param \Rebelo\Date\Date $fromDay
     * @param \Rebelo\Date\Date $toDay
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getExameBetweenDates(Date $fromDay, Date $toDay): string
    {
        $response = Container::get(Client::class)->get(
            \sprintf("Exame/%s/%s", $fromDay->format(Date::SQL_DATE), $toDay->format(Date::SQL_DATE))
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param \Rebelo\Date\Date $fromDay
     * @param \Rebelo\Date\Date $toDay
     * @param string $categoria
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getExameOfCategoriaBetweenDates(Date $fromDay, Date $toDay, string $categoria): string
    {
        $response = Container::get(Client::class)->get(
            \sprintf(
                "Exame/%s/%s/%s",
                $fromDay->format(Date::SQL_DATE),
                $toDay->format(Date::SQL_DATE),
                $categoria
            )
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param \Rebelo\Date\Date $fromDay
     * @param \Rebelo\Date\Date $toDay
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getCategoriasPraticoWithExam(Date $fromDay, Date $toDay): string
    {
        $response = Container::get(Client::class)->get(
            \sprintf("Categoria/pratico/%s/%s", $fromDay->format(Date::SQL_DATE), $toDay->format(Date::SQL_DATE))
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    /**
     * @param \Rebelo\Date\Date $fromDay
     * @param \Rebelo\Date\Date $toDay
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getCategoriasTeoricoWithExam(Date $fromDay, Date $toDay): string
    {
        $response = Container::get(Client::class)->get(
            \sprintf(
                "Categoria/teorico/%s/%s",
                $fromDay->format(Date::SQL_DATE),
                $toDay->format(Date::SQL_DATE)
            )
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }
}
