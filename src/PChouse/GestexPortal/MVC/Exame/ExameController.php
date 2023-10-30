<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Exame;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\AController;
use Rebelo\Date\Date;

#[Inject]
#[Autowired]
class ExameController extends AController implements IExameController
{

    public function __construct(protected IExameModel $exameModel, protected IExameView $exameView)
    {
        parent::__construct($this->exameModel, $this->exameView);
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function doAction(string|null $action): void
    {
        switch ($action) {
            case "dataGrid":
                $this->dataGrid();
                break;
            case "dataGridPratico":
                $this->dataGridPratico();
                break;
            case "dataGridTeoricos":
                $this->dataGridTeoricos();
                break;
            case "layout":
                $this->getLayout();
                break;
            case "pdfAll":
                $this->pdfAll();
                break;
            case "pdfTeoricos":
                $this->pdfTeoricos();
                break;
            case "pdfPraticos":
                $this->pdfPraticos();
                break;
            default:
                parent::doAction($action);
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function dataGridPratico(): void
    {
        try {
            $json         = $this->request->getRequestBody() ?? throw new \Exception("No request body");
            $gridResponse = $this->exameModel->getGridDataPraticos($json);
            $this->exameView->sendGridResponse($gridResponse);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }


    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function dataGridTeoricos(): void
    {
        try {
            $json         = $this->request->getRequestBody() ?? throw new \Exception("No request body");
            $gridResponse = $this->exameModel->getGridDataTeoricos($json);
            $this->exameView->sendGridResponse($gridResponse);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @throws \ReflectionException
     * @throws \Throwable
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    protected function getLayout(): void
    {
        try {
            $this->exameView->renderLayout();
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @throws \Throwable
     * @throws \ReflectionException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PChouse\GestexPortal\Di\DiException
     */
    public function dataGrid(): void
    {
        try {
            $json         = $this->request->getRequestBody() ?? throw new \Exception("No request body");
            $gridResponse = $this->exameModel->getGridData($json);
            $this->exameView->sendGridResponse($gridResponse);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }

    /**
     * @throws \Exception
     */
    public function pdfAll(): void
    {
        try {
            $dateProvider = Container::get(IDateProvider::class);
            $fromDay      = $dateProvider->parse(Date::SQL_DATE, $this->globals->getRequestKey("fromDay"));
            $toDay        = $dateProvider->parse(Date::SQL_DATE, $this->globals->getRequestKey("toDay"));

            $pdf = Container::get(IExameHelper::class)->examesPDF($fromDay, $toDay);

            $this->response->setError(false);
            $this->response->setData((\base64_encode($pdf->pdfToString())));
            $this->response->setExtraData(
                \sprintf("Exames_%s_a_%s.pdf", $fromDay->format("Ymd"), $toDay->format("Ymd"))
            );
            $this->response->send();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->send();
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function pdfTeoricos(): void
    {
        try {
            $dateProvider = Container::get(IDateProvider::class);
            $fromDay      = $dateProvider->parse(Date::SQL_DATE, $this->globals->getRequestKey("fromDay"));
            $toDay        = $dateProvider->parse(Date::SQL_DATE, $this->globals->getRequestKey("toDay"));

            $pdf = Container::get(IExameHelper::class)->examesTeoricosPDF($fromDay, $toDay);

            $this->response->setError(false);
            $this->response->setData((\base64_encode($pdf->pdfToString())));
            $this->response->setExtraData(
                \sprintf("Exames_teoricos_%s_a_%s.pdf", $fromDay->format("Ymd"), $toDay->format("Ymd"))
            );
            $this->response->send();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->send();
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function pdfPraticos(): void
    {
        try {
            $dateProvider = Container::get(IDateProvider::class);
            $fromDay      = $dateProvider->parse(Date::SQL_DATE, $this->globals->getRequestKey("fromDay"));
            $toDay        = $dateProvider->parse(Date::SQL_DATE, $this->globals->getRequestKey("toDay"));

            $pdf = Container::get(IExameHelper::class)->examesPraticosPDF($fromDay, $toDay);

            $this->response->setError(false);
            $this->response->setData((\base64_encode($pdf->pdfToString())));
            $this->response->setExtraData(
                \sprintf("Exames_praticos_%s_a_%s.pdf", $fromDay->format("Ymd"), $toDay->format("Ymd"))
            );
            $this->response->send();
        } catch (\Throwable $e) {
            $this->response->setError(true);
            $this->response->setMsg($e->getMessage());
            $this->response->send();
        }
    }
}
