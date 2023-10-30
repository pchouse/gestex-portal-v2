<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\FacturacaoDetalhe;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\AController;

#[Inject]
#[Autowired]
class FacturacaoDetalheController extends AController implements IFacturacaoDetalheController
{

    public function __construct(
        protected IFacturacaoDetalheModel $facturacaoDetalheModel,
        protected IFacturacaoDetalheView  $facturacaoDetalheView
    ) {
        parent::__construct($this->facturacaoDetalheModel, $this->facturacaoDetalheView);
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
            $gridResponse = $this->facturacaoDetalheModel->getGridData($json);
            $this->facturacaoDetalheView->sendGridResponse($gridResponse);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }
}
