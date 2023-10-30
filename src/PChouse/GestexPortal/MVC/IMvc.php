<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use PChouse\GestexPortal\Config\IConfig;
use PChouse\GestexPortal\Helpers\IGlobals;
use PChouse\GestexPortal\Helpers\IRequest;
use PChouse\GestexPortal\Helpers\IResponse;
use PChouse\GestexPortal\Helpers\ISession;

interface IMvc
{
    /**
     * Executed after the DI container create instance
     *
     * @return void
     */
    public function afterInstanceCreated(): void;

    /**
     * Executed before the DI Container return the instance
     *
     * @return void
     */
    public function beforeReturnInstance(): void;

    public function getGlobals(): IGlobals;

    /**
     * @return \PChouse\GestexPortal\Helpers\ISession
     */
    public function getSession(): ISession;

    /**
     * @return \PChouse\GestexPortal\Helpers\IRequest
     */
    public function getRequest(): IRequest;

    /**
     * @return \Logger
     */
    public function getLogger(): \Logger;

    /**
     * @return \PChouse\GestexPortal\Config\IConfig
     */
    public function getConfig(): IConfig;

    /**
     * trim the string, the string is passed as reference and
     * also returned to can be use also directly as function argument
     *
     * @param string $str
     *
     * @return string
     */
    public function toLower(string &$str): string;

    /**
     * trim the string, the string is passed as reference and
     * also returned to can be use also directly as function argument
     *
     * @param string $str
     *
     * @return string
     */
    public function toUpper(string &$str): string;

    /**
     * Get the MVC name
     *
     * @return string
     */
    public function getMvcName(): string;

    /**
     * Get the MVC module path
     *
     * @return string
     * @throws \Exception
     */
    public function getMvcPath(): string;

    /**
     * @return \PChouse\GestexPortal\Helpers\IResponse
     */
    public function getResponse(): IResponse;
}
