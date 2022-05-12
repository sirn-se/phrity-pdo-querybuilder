<?php

namespace Phrity\Pdo\Query;

class Delete implements StatementInterface
{
    private $b;
    private $table;
    private $where;

    public function __construct(Builder $b, Table $table)
    {
        $this->b = $b;
        $this->table($table);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function table(Table $table): Table
    {
        $this->table = $table;
        return $table;
    }

    public function where(ExpressionInterface $where): self
    {
        $this->where = $where;
        return $this;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $where = $this->where ? " WHERE {$this->where->refer()}" : '';
        return "DELETE FROM {$this->table->refer()}{$where};";
    }
}
