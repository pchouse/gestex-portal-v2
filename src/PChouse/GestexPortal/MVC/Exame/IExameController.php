<?php

namespace PChouse\GestexPortal\MVC\Exame;

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\IController;
use Rebelo\Date\Date;

interface IExameController extends IController
{

    public function dataGrid(): void;

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function dataGridTeoricos(): void;

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function dataGridPratico(): void;

    /**
     * @throws \Exception
     */
    public function pdfAll(): void;

    /**
     * @return void
     * @throws \Exception
     */
    public function pdfTeoricos(): void;

    /**
     * @return void
     * @throws \Exception
     */
    public function pdfPraticos(): void;
}
