<?php /** @noinspection All */
declare(strict_types=1);

\date_default_timezone_set("Europe/Lisbon");

\Rebelo\Date\Date::$defaultTimeZone = new \DateTimeZone("Europe/Lisbon");

define(
    "GESTEX_MAIN_DIR",
    \realpath(
        \join(DIRECTORY_SEPARATOR, [__DIR__, '..'])
    ) ?: throw new \Exception("Gestex MAIN dir error")
);

define(
    "GESTEX_TMP_DIR",
    \realpath(
        \join(DIRECTORY_SEPARATOR, [GESTEX_MAIN_DIR, 'tmp'])
    ) ?: throw new \Exception("Gestex TMP directory not created")
);

define(
    "GESTEX_MAIL_DIR",
    \realpath(
        \join(DIRECTORY_SEPARATOR, [GESTEX_MAIN_DIR, 'MAIL'])
    ) ?: throw new \Exception("Gestex TMP directory not created")
);

define(
    "GESTEX_CACHE",
    \realpath(
        \join(DIRECTORY_SEPARATOR, [GESTEX_MAIN_DIR, 'Cache'])
    ) ?: throw new \Exception("Gestex Cache directory not created")
);


define(
    "GESTEX_CACHE_TWIG",
    \realpath(
        \join(DIRECTORY_SEPARATOR, [GESTEX_CACHE, 'Twig'])
    ) ?: throw new \Exception("Gestex TMP directory not created")
);

define(
    "GESTEX_MAIN_HTML",
    \realpath(
        \join(
            DIRECTORY_SEPARATOR,
            [GESTEX_MAIN_DIR, "src", "PChouse", "GestexPortal", "HTML"]
        )
    ) ?: throw new \Exception("Gestex MAIN HTML directory not created")
);

define(
    "GESTEX_MAIN_MVC",
    \realpath(
        \join(
            DIRECTORY_SEPARATOR,
            [GESTEX_MAIN_DIR, "src", "PChouse", "GestexPortal", "MVC"]
        )
    ) ?: throw new \Exception("Gestex MAIN HTML directory not created")
);


try {
    \Logger::configure(
        \realpath(GESTEX_MAIN_DIR . DIRECTORY_SEPARATOR . "log4php.xml") ?:
            throw new Exception("No log configuration file")
    );
} catch (\Throwable $e) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo "Error set Log configuration in Bootstrap file: " . $e->getMessage();
}

define(
    "GESTEX_PDF", \realpath(
        GESTEX_MAIN_DIR . DIRECTORY_SEPARATOR . "PDF"
    )
);

\set_include_path(
    get_include_path() . PATH_SEPARATOR . \realpath(
        \join(DIRECTORY_SEPARATOR, [GESTEX_MAIN_DIR])
    )
);
