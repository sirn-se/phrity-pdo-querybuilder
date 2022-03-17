<?php

namespace Phrity\Pdo\Query;

class Select implements StatementInterface
{
    private $b;
    private $from;
    private $select = [];
    private $where;
    private $joins = [];
    private $order_by;
    private $for;
    private $limit;

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

    public function leftJoin(Table $join): LeftJoin
    {
        $join = $this->b->leftJoin($join);
        $this->joins[] = $join;
        return $join;
    }

    public function orderBy(ExpressionInterface ...$order_by): void
    {
        $this->order_by = $order_by;
    }

    public function limit(int $limit = null, int $offset = null): void
    {
        $this->limit = $this->b->limit($limit, $offset);
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
        $order_by = $this->order_by ? ' ORDER BY ' . implode(',', array_map(function ($order_by) {
            return $order_by->define();
        }, $this->order_by)) : '';
        $limit = $this->limit ? " {$this->limit->define()}" : '';
        $for = $this->for ? " FOR {$this->for}" : '';
        return "SELECT {$fields}{$from}{$joins}{$where}{$order_by}{$limit}{$for};";
    }
}
