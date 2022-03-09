<?php

namespace Phrity\Pdo\Query;

class Field implements SqlInterface
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

    public function define(): string
    {
        $ref = $this->table->refer();
        return $this->alias
            ? "{$ref}.{$this->b->e($this->name)} {$this->b->e($this->alias)}"
            : "{$ref}.{$this->b->e($this->name)}";
    }

    public function refer(): string
    {
        return "{$this->table->refer()}.{$this->b->e($this->name)}";
    }
}
