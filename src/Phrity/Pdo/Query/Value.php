<?php

namespace Phrity\Pdo\Query;

class Value implements ExpressionInterface
{
    private $b;
    private $value;
    private $alias;

    public function __construct(Builder $b, $value, string $alias = null)
    {
        $this->b = $b;
        $this->value = $value;
        $this->alias = $alias;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(bool $use_alias = false): string
    {
        return $use_alias && $this->alias
            ? "{$this->b->q($this->value)} {$this->b->e($this->alias)}"
            : $this->b->q($this->value);
    }

    public function refer(bool $use_alias = false): string
    {
        return $use_alias && $this->alias ? $this->b->e($this->alias) : $this->b->q($this->value);
    }
}
