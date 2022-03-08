<?php

namespace Phrity\Pdo\Query;

class Table implements SqlInterface
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

    public function __toString(): string
    {
        return $this->alias
            ? "{$this->b->e($this->name)} AS {$this->b->e($this->alias)}"
            : "{$this->b->e($this->name)}";
    }

    public function ref(): string
    {
        return $this->alias ? $this->alias : $this->name;
    }
}
