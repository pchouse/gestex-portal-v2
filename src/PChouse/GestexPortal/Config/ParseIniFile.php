<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Config;

class ParseIniFile implements IParseIniFile
{
    private \Logger $logger;

    private static array|false $ini = false;

    public function __construct()
    {
        $this->logger = \Logger::getLogger(static::class);
    }

    /**
     * Return the configuration options
     * @return array
     * @throws \Exception
     */
    public function getConfigOptions(): array
    {
        $propertiesFilePath = GESTEX_MAIN_DIR . DIRECTORY_SEPARATOR . "gestex.properties";

        if (self::$ini === false) {
            if (false === self::$ini = \parse_ini_file($propertiesFilePath)) {
                $msg = "Error reading file configuration properties '$propertiesFilePath'";
                $this->logger->error($msg);
                throw new \Exception($msg);
            }
        }
        return self::$ini;
    }
}
