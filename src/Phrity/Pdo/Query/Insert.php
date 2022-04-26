<?php

namespace Phrity\Pdo\Query;

class Insert implements StatementInterface
{
    private $b;
    private $into;
    private $assign = [];
    private $where;
    private $joins = [];
    private $for;

    public function __construct(Builder $b, Table $into = null, Assign ...$assign)
    {
        $this->b = $b;
        $this->into($into);
        $this->assign(...$assign);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function into(Table $into = null): ?Table
    {
        if ($into) {
            $this->into = $into;
        }
        return $into;
    }

    public function assign(Assign ...$assign): void
    {
        $this->assign = $assign;
    }

    public function where(ExpressionInterface $where): void
    {
        $this->where = $where;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $into = $this->into ? "{$this->into->define()}" : '';
        $targets = $this->assign ? ' (' . implode(',', array_map(function ($assign) {
            return $assign->target();
        }, $this->assign)) . ')' : '';
        $sources = $this->assign ? ' VALUES (' . implode(',', array_map(function ($assign) {
            return $assign->source();
        }, $this->assign)) . ')' : '';
        $where = $this->where ? " WHERE {$this->where->define()}" : '';
        return "INSERT INTO {$into}{$targets}{$sources}{$where};";
    }
}
