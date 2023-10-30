<?php

namespace PChouse\GestexPortal\MVC\Exame;

use PChouse\GestexPortal\Helpers\IPdf;
use Rebelo\Date\Date;

interface IExameHelper
{
    /**
     * @param \Rebelo\Date\Date $fromDay
     * @param \Rebelo\Date\Date $toDay
     *
     * @return \PChouse\GestexPortal\Helpers\IPdf
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Rebelo\Date\DateParseException
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \Zend_Pdf_Exception
     */
    public function examesPDF(Date $fromDay, Date $toDay): IPdf;

    /**
     * @throws \Zend_Pdf_Exception
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Date\DateFormatException
     * @throws \ReflectionException
     */
    public function examesPraticosPDF(Date $fromDay, Date $toDay): IPdf;

    /**
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \Throwable
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Zend_Pdf_Exception
     * @throws \ReflectionException
     * @throws \Rebelo\Date\DateFormatException
     */
    public function examesTeoricosPDF(Date $fromDay, Date $toDay): IPdf;
}
