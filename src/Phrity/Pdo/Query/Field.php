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

    public function define(bool $use_alias = false, bool $use_context = false): string
    {
        $ref = $use_alias && $this->alias
            ? "{$this->b->e($this->name)} {$this->b->e($this->alias)}"
            : $this->b->e($this->name);
        return $use_context ? "{$this->table->refer($use_alias)}.{$ref}" : $ref;
    }

    public function refer(bool $use_alias = false, bool $use_context = false): string
    {
        $ref = $use_alias && $this->alias ? $this->b->e($this->alias) : $this->b->e($this->name);
        return $use_context ? "{$this->table->refer($use_alias)}.{$ref}" : $ref;
    }
}
