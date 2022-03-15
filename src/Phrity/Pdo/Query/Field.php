<?php

namespace Phrity\Pdo\Query;

class Field implements ExpressionInterface
{
    private $b;
    private $table;
    private $name;
    private $alias;

    public function __construct(Builder $b, Table $table, string $name, string $alias = null)
    {
        $this->b = $b;
        $this->table = $table;
        $this->name = $name;
        $this->alias = $alias;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(): string
    {
        return $this->alias
            ? "{$this->refer()} {$this->b->e($this->alias)}"
            : $this->refer();
    }

    public function refer(): string
    {
        return "{$this->table->refer()}.{$this->b->e($this->name)}";
    }
}
