<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

abstract class AController extends AMvc
{

    protected IModel $model;

    protected IView $view;

    public function __construct(IModel $model, IView $view)
    {
        parent::__construct();
        $this->model = $model;
        $this->view  = $view;
    }

    /**
     * @throws \Exception
     */
    public function doAction(?string $action): void
    {
        $this->logger->debug("Action " . ($action ?? "null"));

        switch ($action) {
            case "renderTab":
                $this->renderTab();
                break;
            case "renderModalForm":
                $this->renderModalForm();
                break;
            case "renderModalTable":
                $this->renderModalTable();
                break;
            case "renderForm":
                $this->renderForm();
                break;
            case "renderTabTable":
                $this->renderTabTable();
                break;
            case "renderCancelFormModal":
                $this->renderCancelFormModal();
                break;
            case "renderConfirmDeleteModal":
                $this->renderConfirmDeleteModal();
                break;
            case "insert":
            default:
                if ($action !== null) {
                    $msg = \sprintf("Action %s not exist in %s", $action, static::class);
                    $this->logger->error($msg);
                    throw new \Exception($msg);
                }
        }
    }


    protected function getLayout(): void
    {
    }

    public function getGridXml(): void
    {
    }

    public function editRow(): void
    {
    }

    public function getForm(): void
    {
    }

    public function renderTab(): void
    {
        $this->view->renderTab();
    }

    /**
     * Render the form modal
     *
     * @return void
     */
    public function renderModalForm(): void
    {
        $this->view->renderModalForm();
    }

    /**
     * Render modal table
     *
     * @return void
     */
    public function renderModalTable(): void
    {
        $this->view->renderModalTable();
    }

    /**
     * Render the tab table HTML
     *
     * @return void
     */
    public function renderTabTable(): void
    {
        $this->view->renderTabTable(
            \strtolower($this->model->getMvcName())
        );
    }

    /**
     * Render the form modal
     *
     * @return void
     */
    public function renderForm(): void
    {
        $this->view->renderForm();
    }

    /**
     * Render the form cancel modal dialog
     *
     * @return void
     */
    public function renderCancelFormModal(): void
    {
        $this->view->renderCancelFormModal();
    }

    /**
     * Render de delete confirmation dialog
     *
     * @return void
     */
    public function renderConfirmDeleteModal(): void
    {
        $this->view->renderConfirmDeleteModal();
    }
}
