<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Dashboard;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\AController;

#[Inject]
#[Autowired]
class DashboardController extends AController implements IDashboardController
{

    public function __construct(protected IDashboardModel $dashboardModel, protected IDashboardView $dashboardView)
    {
        parent::__construct($this->dashboardModel, $this->dashboardView);
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function doAction(string|null $action): void
    {
        switch ($action) {
            case "Invoices":
                $this->getInvoices();
                break;
            case "Categoria":
                $this->getCategoria();
                break;
            case "ResultadoPratico":
                $this->getResultadoPratico();
                break;
            case "ResultadoTeorico":
                $this->getResultadoTeorico();
                break;
            case "layout":
                $this->getLayout();
                break;
            default:
                parent::doAction($action);
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getInvoices(): void
    {
        try {
            $response = $this->dashboardModel->getInvoices();
            $this->dashboardView->sendGridResponse($response);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getResultadoPratico(): void
    {
        try {
            $response = $this->dashboardModel->getResultadoPratico();
            $this->dashboardView->sendGridResponse($response);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getResultadoTeorico(): void
    {
        try {
            $response = $this->dashboardModel->getResultadoTeorico();
            $this->dashboardView->sendGridResponse($response);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    public function getCategoria(): void
    {
        try {
            $response = $this->dashboardModel->getCategoria();
            $this->dashboardView->sendGridResponse($response);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @return void
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    protected function getLayout(): void
    {
        try {
            $this->dashboardView->sendLayout();
        } catch (\Throwable $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }
}
