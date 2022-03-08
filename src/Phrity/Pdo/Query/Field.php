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

    public function __toString(): string
    {
        $ref = $this->table->ref();
        return $this->alias
            ? "{$this->b->e($ref)}.{$this->b->e($this->name)} AS {$this->b->e($this->alias)}"
            : "{$this->b->e($ref)}.{$this->b->e($this->name)}";
    }

    public function ref(): string
    {
        return $this->alias ? $this->alias : $this->name;
    }
}
