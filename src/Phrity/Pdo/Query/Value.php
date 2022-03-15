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

    public function define(): string
    {
        return $this->alias
            ? "{$this->refer()} {$this->b->e($this->alias)}"
            : $this->refer();
    }

    public function refer(): string
    {
        return $this->b->q($this->value);
    }
}
