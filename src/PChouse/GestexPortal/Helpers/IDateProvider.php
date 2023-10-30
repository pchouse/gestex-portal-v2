<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use Rebelo\Date\Date;

interface IDateProvider
{
    /**
     * Get a instance of Date
     *
     * @param string|null $time
     *
     * @return \Rebelo\Date\Date
     */
    public function date(?string $time = "now"): Date;

    /**
     * Get a instance of Date with hour zero
     *
     * @param string|null $time
     *
     * @return \Rebelo\Date\Date
     */
    public function dateHourZero(?string $time = "now"): Date;

    /**
     * Parse a string to Date
     *
     * @param string $format
     * @param string $time
     *
     * @return \Rebelo\Date\Date
     * @throws \Rebelo\Date\DateParseException
     */
    public function parse(string $format, string $time): Date;
}
