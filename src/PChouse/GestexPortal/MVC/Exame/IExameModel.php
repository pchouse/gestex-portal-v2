<?php

namespace PChouse\GestexPortal\MVC\Exame;

use GuzzleHttp\Client;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\MVC\IModel;
use Rebelo\Date\Date;

interface IExameModel extends IModel
{

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridData(string $json): string;

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridDataTeoricos(string $json): string;

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function getGridDataPraticos(string $json): string;

    public function getExameBetweenDates(Date $fromDay, Date $toDay): string;

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
    public function getCategoriasPraticoWithExam(Date $fromDay, Date $toDay): string;

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
    public function getCategoriasTeoricoWithExam(Date $fromDay, Date $toDay): string;

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
    public function getExameOfCategoriaBetweenDates(Date $fromDay, Date $toDay, string $categoria): string;
}
