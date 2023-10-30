<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\MVC\Exame\IExameModel;
use PHPUnit\Framework\TestCase;

class ExameModelTest extends TestCase
{

    public function test(): void
    {
        $model = Container::get(IExameModel::class);
        $contents = $model->getExameBetweenDates(
            Container::get(IDateProvider::class)->date()->addMonths(-2),
            Container::get(IDateProvider::class)->date()
        );
    }

}
