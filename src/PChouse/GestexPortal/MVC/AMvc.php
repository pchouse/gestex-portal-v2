<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use PChouse\GestexPortal\Config\IConfig;
use PChouse\GestexPortal\Di\Autowired;
use PChouse\GestexPortal\Di\IDIEvents;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\Helpers\IGlobals;
use PChouse\GestexPortal\Helpers\IRequest;
use PChouse\GestexPortal\Helpers\IResponse;
use PChouse\GestexPortal\Helpers\ISession;

#[Autowired]
abstract class AMvc implements IDIEvents
{
    #[Autowired]
    protected IGlobals $globals;

    #[Autowired]
    protected ISession $session;

    #[Autowired]
    protected IRequest $request;

    #[Autowired]
    protected IResponse $response;

    #[Autowired]
    protected IConfig $config;

    #[Autowired]
    protected IDateProvider $dateProvider;

    protected \Logger $logger;

    /**
     * Executed after the DI container create instance
     *
     * @return void
     */
    public function afterInstanceCreated(): void
    {
        // Override this method to execute the code desired on event
    }

    /**
     * Executed before the DI Container return the instance
     *
     * @return void
     */
    public function beforeReturnInstance(): void
    {
        // Override this method to execute the code desired on event
    }


    public function getGlobals(): IGlobals
    {
        return $this->globals;
    }

    /**
     * @return \PChouse\GestexPortal\Helpers\ISession
     */
    public function getSession(): ISession
    {
        return $this->session;
    }

    /**
     * @return \PChouse\GestexPortal\Helpers\IRequest
     */
    public function getRequest(): IRequest
    {
        return $this->request;
    }

    /**
     * @return \Logger
     */
    public function getLogger(): \Logger
    {
        return $this->logger;
    }

    public function __construct()
    {
        $this->logger = \Logger::getLogger(static::class);
        $this->logger->debug("New instance");
    }

    /**
     * @return \PChouse\GestexPortal\Config\IConfig
     */
    public function getConfig(): IConfig
    {
        return $this->config;
    }

    /**
     * trim the string, the string is passed as reference and
     * also returned to can be use also directly as function argument
     *
     * @param string $str
     *
     * @return string
     */
    public static function trim(string &$str): string
    {
        $str = \trim($str);
        return $str;
    }

    /**
     * trim the string, the string is passed as reference and
     * also returned to can be use also directly as function argument
     *
     * @param string $str
     *
     * @return string
     */
    public function toLower(string &$str): string
    {
        $str = \strtolower($str);
        return $str;
    }

    /**
     * trim the string, the string is passed as reference and
     * also returned to can be use also directly as function argument
     *
     * @param string $str
     *
     * @return string
     */
    public function toUpper(string &$str): string
    {
        $str = \strtoupper($str);
        return $str;
    }

    /**
     * Parse as int, from the numeric value sent by the browser
     *
     * @param string $int
     *
     * @return int
     * @throws \Exception
     */
    public static function parseIntFromRequest(string $int): int
    {
        if (\is_numeric($int) && \preg_match("/([.,])/", $int) !== 1) {
            return (int)$int;
        }
        throw new \Exception("is_not_int");
    }

    /**
     * Parse as int, from the numeric value sent by the browser
     *
     * @param string $int
     *
     * @return int
     * @throws \Exception
     */
    public static function parsePositiveIntFromRequest(string $int): int
    {
        $intInt = static::parseIntFromRequest($int);
        if ($intInt < 0) {
            throw new \Exception("is_negative");
        }
        return $intInt;
    }


    /**
     * Parse as int, from the numeric value sent by the browser
     *
     * @param string|null $int
     *
     * @return int|null
     * @throws \Exception
     */
    public static function parsePositiveIntOrNullFromRequest(?string $int): ?int
    {
        $intInt = static::parseIntOrNullFromRequest($int);
        if ($intInt !== null && $intInt < 0) {
            throw new \Exception("is_negative");
        }
        return $intInt;
    }

    /**
     * Parse as int or null, from the numeric value sent by the browser
     *
     * @param string|null $int
     *
     * @return int|null
     * @throws \Exception
     */
    public static function parseIntOrNullFromRequest(?string $int): ?int
    {
        if ($int === null || static::trim($int) === "") {
            return null;
        }
        return self::parseIntFromRequest($int);
    }

    /**
     * Parse a string number, based on the user decimal mark
     *
     * @param string $number
     *
     * @return float
     * @throws \Exception
     */
    public static function parseFloatFromBrowser(string $number): float
    {
        $number = \str_replace(
            ",",
            ".",
            \str_replace(".", "", $number)
        );

        if (\is_numeric($number)) {
            return \floatval($number);
        }

        throw new \Exception(\sprintf("'%s' is not a string number", $number));
    }

    /**
     * Parse a string number, based on the user decimal mark
     *
     * @param string|null $number
     *
     * @return float|null
     * @throws \Exception
     */
    public static function parseFloatOrNullFromBrowser(?string $number): ?float
    {
        if ($number === null || static::trim($number) === "") {
            return null;
        }
        return self::parseFloatFromBrowser($number);
    }

    /**
     * Parse a string number, based on the user decimal mark and throw \Exception if negative
     *
     * @param string|null $number
     *
     * @return float|null
     * @throws \Exception
     */
    public static function parsePositiveFloatOrNullFromBrowser(?string $number): ?float
    {
        $float = self::parseFloatOrNullfromBrowser($number);
        if ($float !== null && $float < 0) {
            throw new \Exception("is_negative");
        }
        return $float;
    }

    /**
     * Parse a string number, based on the user decimal mark and throw \Exception if negative
     *
     * @param string $number
     *
     * @return float
     * @throws \Exception
     */
    public static function parsePositiveFloatFromBrowser(string $number): float
    {
        $float = self::parseFloatFromBrowser($number);
        if ($float < 0) {
            throw new \Exception("is_negative");
        }
        return $float;
    }

    /**
     * Parse from browser (user decimal mark) a float or int string to int
     *
     * @param string $number
     *
     * @return int
     * @throws \Exception
     */
    public static function parseNumberStringToIntFromBrowser(string $number): int
    {
        return (int)static::parseFloatFromBrowser($number);
    }

    /**
     * Get the MVC name
     *
     * @return string
     */
    public function getMvcName(): string
    {
        $classNameParts = \explode("\\", static::class);
        $className      = $classNameParts[\count($classNameParts) - 1];
        return \preg_replace("/(View|Controller|Model)$/", "", $className);
    }

    /**
     * Get the MVC module path
     *
     * @return string
     * @throws \Exception
     */
    public function getMvcPath(): string
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . \preg_replace(["/^VT/", "/^IVT/"], "", $this->getMvcName());

        return realpath($path) ?: throw new \Exception("MVC path '$path' not exist");
    }

    /**
     * @return \PChouse\GestexPortal\Helpers\IResponse
     */
    public function getResponse(): IResponse
    {
        return $this->response;
    }
}
