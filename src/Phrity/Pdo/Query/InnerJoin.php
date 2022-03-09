<?php

namespace Phrity\Pdo\Query;

class InnerJoin
{
    private $b;
    private $name;
    private $join;
    private $condition;

    public function __construct(Builder $b, Table $table, Table $join)
    {
        $this->b = $b;
        $this->table = $table;
        $this->join = $join;
    }

    public function sql(): string
    {
        $condition = $this->condition ? " ON {$this->condition->define()}" : '';
        return "INNER JOIN {$this->join->define()}{$condition}";
    }

    public function setCondition(SqlInterface $condition): void
    {
        $this->condition = $condition;
    }

    /* ---------- Builder methods ---------------------------------------------------- */

    public function field(string $name, string $alias = null): Field
    {
        return new Field($this->b, $this, $name, $alias);
    }
}
