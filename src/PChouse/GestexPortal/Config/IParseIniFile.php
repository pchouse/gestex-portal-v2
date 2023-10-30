<?php

namespace PChouse\GestexPortal\Config;

interface IParseIniFile
{
    /**
     * Return the configuration options
     *
     * @return array
     * @throws \Exception
     */
    public function getConfigOptions(): array;
}
