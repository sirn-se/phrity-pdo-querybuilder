<?php

namespace Phrity\Pdo\Query;

class LeftJoin
{
    private $b;
    private $from;
    private $join;
    private $on;

    public function __construct(Builder $b, Table $join)
    {
        $this->b = $b;
        $this->join = $join;
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function field(string $name, string $alias = null): Field
    {
        return new Field($this->b, $this->join, $name, $alias);
    }

    public function on(ExpressionInterface $on): void
    {
        $this->on = $on;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function sql(): string
    {
        $on = $this->on ? " ON {$this->on->define()}" : '';
        return "LEFT JOIN {$this->join->define()}{$on}";
    }
}
