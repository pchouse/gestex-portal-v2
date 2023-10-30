<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC\Start;

use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\Inject;
use PChouse\GestexPortal\MVC\AModel;

#[Inject]
#[Autowired]
class StartModel extends AModel implements IStartModel
{

    public function getMvcObjectName(): string
    {
        return IStart::class;
    }

    public function getIdColumnName(): string
    {
        return "";
    }

    public function getTableName(): string
    {
        return "";
    }
}
