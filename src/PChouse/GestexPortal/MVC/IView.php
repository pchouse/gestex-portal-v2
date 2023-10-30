<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

interface IView extends IMvc
{
    public function renderTab(): void;

    public function dataRowsAsJson(array $rows): void;

    public function renderModalForm(): void;

    public function renderModalTable(): void;
    public function renderTabTable(string $prefix): void;

    public function renderForm(): void;

    public function renderCancelFormModal(): void;

    public function renderConfirmDeleteModal(): void;

    public function sendJson(IObject|null $IObject): void;
    public function dataRowsProgressiveLoadAsJson(array $rows, int $lastPage): void;

    /**
     * @param string|int|float|array|null $value
     *
     * @return void
     * @throws \Exception
     */
    public function sendValue(string|int|float|array|null $value): void;

    public function sendGridResponse(string $gridResponseString): void;
}
