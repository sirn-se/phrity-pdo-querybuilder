<?php

namespace Phrity\Pdo\Query;

class Update implements StatementInterface
{
    private $b;
    private $table;
    private $assign = [];
    private $where;

    public function __construct(Builder $b, Table $table, Assign ...$assign)
    {
        $this->b = $b;
        $this->table($table);
        $this->assign(...$assign);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function table(Table $table): Table
    {
        $this->table = $table;
        return $table;
    }

    public function assign(Assign ...$assign): self
    {
        $this->assign = $assign;
        return $this;
    }

    public function where(ExpressionInterface $where): self
    {
        $this->where = $where;
        return $this;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $assign = $this->assign ? ' SET ' . implode(',', array_map(function ($assign) {
            return $assign->define();
        }, $this->assign)) : '';
        $where = $this->where ? " WHERE {$this->where->refer()}" : '';
        return "UPDATE {$this->table->define()}{$assign}{$where};";
    }
}
