<?php

namespace Phrity\Pdo\Query;

class Table
{
    private $b;
    private $name;
    private $alias;

    public function __construct(Builder $b, string $name, string $alias = null)
    {
        $this->b = $b;
        $this->name = $name;
        $this->alias = $alias;
    }

    public function define(): string
    {
        return $this->alias
            ? "{$this->b->e($this->name)} {$this->b->e($this->alias)}"
            : "{$this->b->e($this->name)}";
    }

    public function refer(): string
    {
        return $this->b->e($this->alias ?: $this->name);
    }


    /* ---------- Builder methods ---------------------------------------------------- */

    public function field(string $name, string $alias = null): Field
    {
        return new Field($this->b, $this, $name, $alias);
    }
}
