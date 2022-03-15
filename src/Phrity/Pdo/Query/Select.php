<?php

namespace Phrity\Pdo\Query;

class Select implements StatementInterface
{
    private $b;
    private $from;
    private $select = [];
    private $where;
    private $joins = [];
    private $for;

    public function __construct(Builder $b, Table $from = null, ExpressionInterface ...$select)
    {
        $this->b = $b;
        $this->from($from);
        $this->select(...$select);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function from(Table $from = null): ?Table
    {
        if ($from) {
            $this->from = $from;
        }
        return $from;
    }

    public function select(ExpressionInterface ...$select): void
    {
        $this->select = $select;
    }

    public function where(ExpressionInterface $where): void
    {
        $this->where = $where;
    }

    public function innerJoin(Table $join): InnerJoin
    {
        $join = $this->b->innerJoin($join);
        $this->joins[] = $join;
        return $join;
    }

    public function forUpdate(): void
    {
        $this->for = 'UPDATE';
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $from = $this->from ? " FROM {$this->from->define()}" : '';
        $fields = $this->select ? implode(',', array_map(function ($select) {
            return $select->define();
        }, $this->select)) : '*';
        $joins = $this->joins ? ' ' . implode(' ', array_map(function ($join) {
            return $join->sql();
        }, $this->joins)) : '';
        $where = $this->where ? " WHERE {$this->where->define()}" : '';
        $for = $this->for ? " FOR {$this->for}" : '';
        return "SELECT {$fields}{$from}{$joins}{$where}{$for};";
    }
}
