<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use PChouse\GestexPortal\MVC\IMvc;
use PChouse\GestexPortal\MVC\IObject;

interface IModel extends IMvc
{

    public function getIdColumnName(): string;

    public function getTableName(): string;

    public function getRegExp(string $column): ?string;

    public function getMaximLength(string $column): ?int;

    public function getAllowNull(string $column): bool;

    public function getColumns(): array;

    public function getTypes(): array;

    public function convertDataAsModel(array $data, bool $localized = false): array;

    public function getPrecision(string $column): int;

    public function load(IObject $obj): void;

    /**
     * @return class-string
     */
    public function getMvcObjectName(): string;

    public function parseStdClass(\stdClass $stdClass, IObject $obj): void;
}
