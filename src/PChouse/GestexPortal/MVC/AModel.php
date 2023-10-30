<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use PChouse\GestexPortal\Di\Container;
use PChouse\GestexPortal\Helpers\IDateProvider;
use PChouse\GestexPortal\Helpers\ISession;
use PChouse\GestexPortal\Helpers\Util;
use PChouse\GestexPortal\MVC\Login\ILoginModel;
use PChouse\GestexPortal\MVC\Login\LoginModel;
use Rebelo\Date\Date;
use Rebelo\Decimal\Decimal;

abstract class AModel extends AMvc
{

    use TModel;

    /**
     * DB string type
     */
    const DB_STRING = 1;

    /**
     * DB Integer type
     */
    const DB_INT = 2;

    /**
     * DB Date type
     */
    const DB_DATE = 4;

    /**
     * Datetime type
     */
    const DB_DATETIME = 5;

    /**
     * DB Boolean type
     */
    const DB_BOOLEAN = 6;

    /**
     * DB Binary type
     */
    const DB_BLOB = 7;

    /**
     * DB UDecimal type
     */
    const DB_DECIMAL = 8;

    /**
     * DB time type
     */
    const DB_TIME = 9;

    /**
     * DB timestamp type
     */
    const DB_TIMESTAMP = 10;

    /**
     * Allow null
     */
    const NULL_ALLOW = 1;

    /**
     * Not allow null
     */
    const NULL_NOT_ALLOW = 2;

    /**
     * Max rows that can be exported
     */
    const MAX_EXPORT_ROWS = 3500;

    /**
     * @throws \Rebelo\Date\DateParseException
     * @throws \PChouse\GestexPortal\MVC\Login\UsernameNotExitsException
     * @throws \PChouse\GestexPortal\Di\DiException
     * @throws \PChouse\GestexPortal\MVC\Login\WrongAuthenticationPasswordException
     * @throws \Throwable
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
        $this->set();

        if ($this instanceof LoginModel) {
            return;
        }

        $session = Container::get(ISession::class);
        if ($session->isUserLoggedIn()) {
            $token   = $session->getString("token");
            $expires = $session->getString("expiares");

            if (Util::isNullOrEmpty($token) || Util::isNullOrEmpty($expires)) {
                return;
            }

            $dateExpires = Container::get(IDateProvider::class)->parse(
                Date::DATE_T_TIME,
                $expires
            );

            $now = Container::get(IDateProvider::class)->date();

            if ($dateExpires->addMinutes(-5)->isEarlier($now)) {
                Container::get(ILoginModel::class)->authenticate(
                    $session->getString("username"),
                    $session->getString("password"),
                );
            }
        }
    }

    /**
     *
     * Return the columns list defined in the data model
     *
     * @return array
     */
    public function getColumns(): array
    {
        return \array_keys($this->dataModel);
    }

    /**
     *
     * Returns if a column allows null or not
     *
     * @param string $column
     *
     * @return bool
     * @throws \Exception
     */
    public function getAllowNull(string $column): bool
    {
        if (\array_key_exists($column, $this->allowNull) === false) {
            return false;
        }
        return $this->allowNull[$column] === self::NULL_ALLOW;
    }

    /**
     *
     * Get the field types
     *
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Return the regexp defined in the data model
     *
     * @param string $column
     *
     * @return string|null
     * @throws \Exception
     */
    public function getRegExp(string $column): ?string
    {
        if (\array_key_exists($column, $this->dataModel) === false) {
            throw new \Exception(
                \sprintf(
                    "Column '%s' in MVC '%s' not exist",
                    $column,
                    $this->getMvcName()
                )
            );
        }

        return $this->dataModel[$column];
    }

    /**
     * Return the regexp defined in the data model
     *
     * @param string $column
     *
     * @return int|null
     * @throws \Exception
     */
    public function getMaximLength(string $column): ?int
    {
        if (\array_key_exists($column, $this->maximLength) === false) {
            throw new \Exception(
                \sprintf(
                    "Maxim Length '%s' in MVC '%s' not exist",
                    $column,
                    $this->getMvcName()
                )
            );
        }
        return $this->maximLength[$column];
    }

    /**
     *
     * Cleans from the data array the pairs that the key
     * is not in the data model (columns), and trim the values,
     * in order to prepare the array to be used width Db adapter
     *
     * @param array $data
     * @param bool $localized Date and decimals localized
     *
     * @return array
     * @throws \Rebelo\Date\DateFormatException
     * @throws \Exception
     */
    public function convertDataAsModel(array $data, bool $localized = false): array
    {
        $model = [];
        $error = [];

        $dataModel = $this->getColumns();

        foreach ($data as $key => $value) {
            if (\in_array($key, $dataModel) === false) {
                continue;
            }

            if ($value === null && $this->getAllowNull($key)) {
                $model[$key] = null;
                continue;
            }

            if ($value === null && $this->getAllowNull($key) === false) {
                $error[] = 'NULL value not allowed for column ' . $key . ' in ' . __METHOD__;
                continue;
            }

            $type = $this->getTypes();

            $v = null;
            switch ($type[$key]) {
                case self::DB_BLOB:
                    $v = $value;
                    break;
                case self::DB_TIME:
                case self::DB_STRING:
                    if (\is_string($value) === false) {
                        $error[] = 'wrong data type for column ' . $key . ' in ' . __METHOD__;
                        break;
                    }
                    $v      = \trim($value);
                    $regexp = $this->getRegExp($key);
                    if ($regexp !== null && $key !== 'password') {
                        if (\preg_match($regexp, $v) !== 1) {
                            $error[] = $v . ' does not match regexp for ' . $key . ' in ' . __METHOD__;
                        }
                    }
                    break;
                case self::DB_DATE:
                    if (($value instanceof Date) === false) {
                        $error[] = 'wrong data type for column ' . $key . ' in ' . __METHOD__;
                        break;
                    }
                    $v = $value->format(
                        $localized ? "d-m-Y" : Date::SQL_DATE
                    );
                    break;
                case self::DB_DATETIME:
                    if (($value instanceof Date) === false) {
                        $error[] = 'wrong data type for column ' . $key . ' in ' . __METHOD__;
                        break;
                    }

                    $v = $value->format(
                        $localized ? "d-m-Y" : Date::SQL_DATETIME
                    );

                    break;
                case self::DB_DECIMAL:
                    if (($value instanceof Decimal) === false) {
                        $error[] = 'wrong data type for column ' . $key . ' in ' . __METHOD__;
                        break;
                    }

                    //@phpstan-ignore-next-line
                    if ($localized === false) {
                        $v = $value->valueOf();
                    } else {
                        $v = \number_format(
                            $value->valueOf(),
                            $this->precision[$key],
                            ",",
                            ""
                        );
                    }
                    break;
                case self::DB_INT:
                    if (\is_int($value) === false) {
                        $error[] = 'wrong data type for column ' . $key . ' in ' . __METHOD__;
                        break;
                    }
                    $v = $value;
                    break;
                case self::DB_BOOLEAN:
                    if (\is_bool($value) === false) {
                        $error[] = 'wrong data type for column ' . $key . ' in ' . __METHOD__;
                        break;
                    }
                    $v = $value;//? 1 : 0;
                    break;
                default:
                    throw new \Exception('unknown type ' . $type[$key] . ' in ' . __METHOD__);
            }

            $model[$key] = $v ?? throw new \Exception('Value $v not parsed to key ->  ' . $key);
            unset($v);
        }

        if (\count($error) > 0) {
            throw new \Exception(join("\n", $error));
        }

        return $model;
    }

    /**
     *
     * Get the number precision for float
     * other will return 0
     *
     * @param string $column
     *
     * @return int
     */
    public function getPrecision(string $column): int
    {
        return $this->precision[$column];
    }

    abstract public function getMvcObjectName(): string;


    /**
     * Load the object from database
     *
     * @param \PChouse\GestexPortal\MVC\IObject $obj
     *
     * @return void
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\Decimal\DecimalException
     * @throws \Exception
     */
    public function load(IObject $obj): void
    {
        if (!\is_a($obj, $this->getMvcObjectName())) {
            throw new \Exception("object is not a instance of " . $this->getMvcName());
        }
    }

    /**
     *
     * Parse the fetch db row or array of attributs
     *
     * @param \stdClass $stdClass
     * @param \PChouse\GestexPortal\MVC\IObject $obj
     *
     * @return void
     * @throws \Rebelo\Date\DateParseException
     * @throws \Rebelo\Decimal\DecimalException
     * @throws \Exception
     */
    public function parseStdClass(\stdClass $stdClass, IObject $obj): void
    {

        $row = \json_decode(\json_encode($stdClass), true);

        if (\count($row) == 0) {
            throw new \InvalidArgumentException('empty_array_to_parse');
        }

        $types = $this->getTypes();

        foreach ($row as $k => $v) {
            if ($v === null) {
                $obj->setAttribute($k, null);
                continue;
            }

            if (!\array_key_exists($k, $types) &&
                !\array_key_exists(\strtolower($k), $types) &&
                !\array_key_exists(\strtoupper($k), $types)
            ) {
                throw new \Exception(
                    \sprintf(
                        "Unknown db type to parse for key '%s' of object '%s'",
                        $k,
                        \str_replace('Model', '', get_class($this))
                    )
                );
            }

            if (isset($types[$k]) === false) {
                if (isset($types[\strtolower($k)])) {
                    $k = \strtolower($k);
                } elseif (isset($types[\strtoupper($k)])) {
                    $k = \strtoupper($k);
                }
            }

            switch ($types[$k]) {
                case self::DB_TIME:
                case self::DB_STRING:
                    $p = $v;
                    break;
                case self::DB_BOOLEAN:
                    if (\in_array($v, ["0", "1", 1, 0, true, false], true) === false) {
                        throw new \Exception("Value '$v' on key '$k' is not a bool");
                    }
                    $p = ($v === "1" || $v === 1 || $v === true);
                    break;
                case self::DB_DATE:
                    if ($v === "0000-00-00") {
                        $p = null;
                    } else {
                        $p = $this->dateProvider->parse(Date::SQL_DATE, $v);
                        $p->setHour(0);
                        $p->setMinutes(0);
                        $p->setMicroseconds(0);
                    }
                    break;
                case self::DB_DATETIME:
                    if (\str_starts_with($v, "0000-00-00")) {
                        $p = null;
                    } else {
                        $p = $this->dateProvider->parse(
                            Date::SQL_DATETIME,
                            \str_replace("T", " ", $v)
                        );
                    }
                    break;
                case self::DB_DECIMAL:
                    $p = new Decimal($v, $this->getPrecision($k));
                    break;
                case self::DB_INT:
                    if (\is_int($v) === false && \preg_match("/^-?[0-9]+$/", $v) !== 1) {
                        throw new \Exception("Value '$v' is not a Integer for '$k'");
                    }
                    $p = (int)$v;
                    break;
                case self::DB_BLOB:
                    $p = \base64_encode($v);
                    break;
                default:
                    throw new \Exception(
                        \sprintf(
                            "Unknown db type to parse for key '%s' of object '%s'",
                            $k,
                            \str_replace('Model', '', get_class($this))
                        )
                    );
            }
            $obj->setAttribute($k, $p);
        }
    }
}
