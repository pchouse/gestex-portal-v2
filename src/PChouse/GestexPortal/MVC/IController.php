<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

interface IController extends IMvc
{

    public function doAction(string $action): void;

    public function renderModalForm(): void;

    /**
     * Render the HTML for the
     * @return void
     */
    public function renderModalTable(): void;

    public function renderTabTable(): void;

    public function renderForm(): void;
}
