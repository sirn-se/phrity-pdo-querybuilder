<?php

namespace Phrity\Pdo\Query;

class Select implements StatementInterface
{
    private $b;
    private $table;
    private $select = [];
    private $where;
    private $joins = [];
    private $order_by;
    private $for;
    private $limit;

    public function __construct(Builder $b, Table $table = null, ExpressionInterface ...$select)
    {
        $this->b = $b;
        $this->table($table);
        $this->select(...$select);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function table(Table $table = null): ?Table
    {
        $this->table = $table;
        return $table;
    }

    public function select(ExpressionInterface ...$select): self
    {
        $this->select = $select;
        return $this;
    }

    public function where(ExpressionInterface $where): self
    {
        $this->where = $where;
        return $this;
    }

    public function innerJoin(Table $join): InnerJoin
    {
        $join = $this->b->innerJoin($join);
        $this->joins[] = $join;
        return $join;
    }

    public function leftJoin(Table $join): LeftJoin
    {
        $join = $this->b->leftJoin($join);
        $this->joins[] = $join;
        return $join;
    }

    public function orderBy(ExpressionInterface ...$order_by): self
    {
        $this->order_by = $order_by;
        return $this;
    }

    public function limit(int $limit = null, int $offset = null): self
    {
        $this->limit = $this->b->limit($limit, $offset);
        return $this;
    }

    public function forUpdate(): self
    {
        $this->for = 'UPDATE';
        return $this;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $table = $this->table ? " FROM {$this->table->define(true)}" : '';
        $fields = $this->select ? implode(',', array_map(function ($select) {
            return $select->define(true, (bool)$this->joins);
        }, $this->select)) : '*';
        $joins = $this->joins ? ' ' . implode(' ', array_map(function ($join) {
            return $join->sql();
        }, $this->joins)) : '';
        $where = $this->where ? " WHERE {$this->where->refer(true, (bool)$this->joins)}" : '';
        $order_by = $this->order_by ? ' ORDER BY ' . implode(',', array_map(function ($order_by) {
            return $order_by->define(true, (bool)$this->joins);
        }, $this->order_by)) : '';
        $limit = $this->limit ? " {$this->limit->define()}" : '';
        $for = $this->for ? " FOR {$this->for}" : '';
        return "SELECT {$fields}{$table}{$joins}{$where}{$order_by}{$limit}{$for};";
    }
}
