<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Facturacao;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\AController;

#[Inject]
#[Autowired]
class FacturacaoController extends AController implements IFacturacaoController
{

    public function __construct(protected IFacturacaoModel $facturacaoModel, protected IFacturacaoView $facturacaoView)
    {
        parent::__construct($this->facturacaoModel, $this->facturacaoView);
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
            case "dataGridChild":
                $this->dataGridChild();
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
    public function dataGrid(): void
    {
        try {
            $json         = $this->request->getRequestBody() ?? throw new \Exception("No request body");
            $gridResponse = $this->facturacaoModel->getGridData($json);
            $this->facturacaoView->sendGridResponse($gridResponse);
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
    public function dataGridChild(): void
    {
        try {
            $json         = $this->request->getRequestBody() ?? throw new \Exception("No request body");
            $gridResponse = $this->facturacaoModel->getGridDataChild($json);
            $this->facturacaoView->sendGridResponse($gridResponse);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }


}
