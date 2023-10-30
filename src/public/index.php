<?php
declare(strict_types=1);

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Helpers\IRouter;
use PChouse\GestexPortal\Helpers\ISession;

try {
    require_once \realpath(
        \join(DIRECTORY_SEPARATOR, [ __DIR__ , "..", "..", "vendor", "autoload.php"])
    );

    Container::get(ISession::class)->startSession();
    Container::get(IRouter::class)->route();
} catch (\Throwable|Error|TypeError $e) {
    \Logger::getLogger("INDEX")->error($e->getMessage());
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo $e->getMessage();
}
