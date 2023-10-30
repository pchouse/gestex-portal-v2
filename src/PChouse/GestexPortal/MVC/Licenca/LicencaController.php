<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Licenca;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\Helpers\IResponseHttpHeader;
use PChouse\GestexPortal\MVC\AController;

#[Inject]
#[Autowired]
class LicencaController extends AController implements ILicencaController
{

    public function __construct(protected ILicencaModel $licencaModel, protected ILicencaView $licencaView)
    {
        parent::__construct($this->licencaModel, $this->licencaView);
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
            $gridResponse = $this->licencaModel->getGridData($json);
            $this->licencaView->sendGridResponse($gridResponse);
        } catch (\Exception $e) {
            Container::get(IResponseHttpHeader::class)->sendBadRequestApplicationJson();
            echo $e->getMessage();
        }
    }
}
