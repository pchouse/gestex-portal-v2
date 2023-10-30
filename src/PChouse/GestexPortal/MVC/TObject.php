<?php
declare(strict_types=1);

namespace PChouse\GestexPortal\MVC;

use Rebelo\Date\Date;

trait TObject
{
    use TModel;

    protected \Logger $logger;

    abstract public function testModelRegExp(string $column, ?string &$value): void;

    abstract public function setAttribute(string $key, mixed $value): void;

    abstract public function getAttribute(string $key): mixed;

    /**
     * @param string $user
     *
     * @return $this
     * @throws \Exception
     */
    public function setCriacaoUser(string $user): self
    {
        $column = $this::CL_CRIACAO_USER;
        $this->testModelRegExp($column, $user);
        $this->setAttribute($column, $user);
        $this->logger->debug($column . ' set to ' . $user);
        return $this;
    }

    /**
     * @return string
     */
    public function getCriacaoUser(): string
    {
        return $this->getAttribute($this::CL_CRIACAO_USER);
    }

    /**
     * @param \Rebelo\Date\Date $data
     *
     * @return $this
     * @throws \Rebelo\Date\DateFormatException
     */
    public function setCriacaoData(Date $data): self
    {
        $column                    = $this::CL_CRIACAO_DATA;
        $this->setAttribute($column, $data);
        $this->logger->debug($column . ' set to ' . $data->format(Date::SQL_DATETIME));
        return $this;
    }

    /**
     * @return \Rebelo\Date\Date
     */
    public function getCriacaoData(): Date
    {
        return $this->getAttribute($this::CL_CRIACAO_DATA);
    }

    /**
     * @param string $user
     *
     * @return $this
     * @throws \Exception
     */
    public function setAlteracaUser(string $user): self
    {
        $column = $this::CL_ALTERACAO_USER;
        $this->testModelRegExp($column, $user);
        $this->setAttribute($column, $user);
        $this->logger->debug($column . ' set to ' . $user);
        return $this;
    }

    /**
     * @return string
     */
    public function getAlteracaUser(): string
    {
        return $this->getAttribute($this::CL_ALTERACAO_USER);
    }

    /**
     * @param \Rebelo\Date\Date $data
     *
     * @return $this
     * @throws \Exception
     */
    public function setAlteracaoData(Date $data): self
    {
        $column                    = $this::CL_ALTERACAO_DATA;
        $this->setAttribute($column, $data);
        $this->logger->debug($column . ' set to ' . $data->format(Date::SQL_DATETIME));
        return $this;
    }

    /**
     * @return \Rebelo\Date\Date
     */
    public function getAlteracaoData(): Date
    {
        return $this->getAttribute($this::CL_ALTERACAO_DATA);
    }
}
