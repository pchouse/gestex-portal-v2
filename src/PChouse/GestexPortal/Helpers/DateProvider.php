<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use Rebelo\Date\Date;

class DateProvider implements IDateProvider
{

    /**
     * Get a instance of Date
     * @param string|null $time
     *
     * @return \Rebelo\Date\Date
     */
    public function date(?string $time = "now"): Date
    {
        return new Date($time, new \DateTimeZone("Europe/Lisbon"));
    }

    /**
     * Get a instance of Date with hour zero
     *
     * @param string|null $time
     *
     * @return \Rebelo\Date\Date
     */
    public function dateHourZero(?string $time = "now"): Date
    {
        $date = new Date($time, new \DateTimeZone("Europe/Lisbon"));
        $date->setHour(0);
        $date->setMinutes(0);
        $date->setSeconds(0);
        $date->setMicroseconds(0);
        return $date;
    }

    /**
     * Parse a string to Date
     * @param string $format
     * @param string $time
     *
     * @return \Rebelo\Date\Date
     * @throws \Rebelo\Date\DateParseException
     */
    public function parse(string $format, string $time): Date
    {
        return Date::parse($format, $time, new \DateTimeZone("Europe/Lisbon"));
    }

}
