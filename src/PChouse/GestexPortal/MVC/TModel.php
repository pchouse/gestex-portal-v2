<?php

namespace PChouse\GestexPortal\MVC;

trait TModel
{

    /**
     *
     * the variable type
     *
     * @var int[]
     */
    protected array $types = [];

    /**
     *
     * The data model of the data
     *
     * @var array<string|null>
     */
    protected array $dataModel = [];

    /**
     *
     * Maxlenght to be useb in the html form
     *
     * @var array<int|null>
     */
    protected array $maximLength = [];

    /**
     *
     * Define what columns can be null
     *
     * @var int[]
     */
    protected array $allowNull = [];

    /**
     *
     * Number precision of column
     *
     * @var int[]
     */
    protected array $precision = [];

    const CL_CRIACAO_USER = "criacaoUser";

    const CL_CRIACAO_DATA = "criacaoData";

    const CL_ALTERACAO_USER = "alteracaoUser";

    const CL_ALTERACAO_DATA = "alteracaoData";

    public function set(): void
    {

        $this->maximLength[self::CL_CRIACAO_USER]   = 20;
        $this->maximLength[self::CL_CRIACAO_DATA]   = null;
        $this->maximLength[self::CL_ALTERACAO_USER] = 20;
        $this->maximLength[self::CL_ALTERACAO_DATA] = null;

        $this->precision[self::CL_CRIACAO_USER]   = 0;
        $this->precision[self::CL_CRIACAO_DATA]   = 0;
        $this->precision[self::CL_ALTERACAO_USER] = 0;
        $this->precision[self::CL_ALTERACAO_DATA] = 0;

        $this->dataModel[self::CL_CRIACAO_USER]   = null;
        $this->dataModel[self::CL_CRIACAO_DATA]   = null;
        $this->dataModel[self::CL_ALTERACAO_USER] = null;
        $this->dataModel[self::CL_ALTERACAO_DATA] = null;

        $this->types[self::CL_CRIACAO_USER]   = AModel::DB_STRING;
        $this->types[self::CL_CRIACAO_DATA]   = AModel::DB_DATETIME;
        $this->types[self::CL_ALTERACAO_USER] = AModel::DB_STRING;
        $this->types[self::CL_ALTERACAO_DATA] = AModel::DB_DATETIME;

        $this->allowNull[self::CL_CRIACAO_USER]   = AModel::NULL_ALLOW;
        $this->allowNull[self::CL_CRIACAO_DATA]   = AModel::NULL_ALLOW;
        $this->allowNull[self::CL_ALTERACAO_USER] = AModel::NULL_ALLOW;
        $this->allowNull[self::CL_ALTERACAO_DATA] = AModel::NULL_ALLOW;
    }
}
