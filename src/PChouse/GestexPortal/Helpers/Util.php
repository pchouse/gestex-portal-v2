<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\Helpers;

use Rebelo\Date\Date;

class Util
{
    /**
     * Return if the string is null or empty
     *
     * @param string|null $string
     *
     * @return bool
     */
    public static function isNullOrEmpty(?string $string): bool
    {
        if ($string === null) {
            return true;
        }
        return \trim($string) === "";
    }

    /**
     * Return if the string is not null or neither empty
     *
     * @param string|null $string
     *
     * @return bool
     */
    public static function isNotNullOrEmpty(?string $string): bool
    {
        return !self::isNullOrEmpty($string);
    }

    /**
     * @param int $month
     *
     * @return string
     * @throws \Exception
     */
    public static function monthToString(int $month): string
    {
        $m = array(
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        );
        return $m[$month] ?? throw new \Exception("Month unknown");
    }

    /**
     * @param int $weekday
     *
     * @return string
     * @throws \Exception
     */
    public static function weekdayToString(int $weekday): string
    {
        $day = array(
            1 => '2ª feira',
            2 => '3ª feira',
            3 => '4ª feira',
            4 => '5ª feira',
            5 => '6ª feira',
            6 => 'Sábado',
            7 => 'Domingo',
        );
        return $day[$weekday] ?? throw new \Exception("Month unknown");
    }

    /**
     * @param \Rebelo\Date\Date $date
     *
     * @return string
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     */
    public static function dateToPTString(Date $date): string
    {
        return \sprintf(
            "%s, %s de %s de %s",
            self::weekdayToString((int)$date->format("N")),
            $date->format(Date::DAY_LESS),
            self::monthToString((int)$date->format(Date::MONTH)),
            $date->format(Date::YAER),
        );
    }
}
