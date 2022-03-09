<?php

namespace Phrity\Pdo\Query;

class Value implements SqlInterface
{
    private $b;
    private $value;

    public function __construct(Builder $b, $value, string $alias = null)
    {
        $this->b = $b;
        $this->value = $value;
        $this->alias = $alias;
    }

    public function define(): string
    {
        return $this->alias
            ? "{$this->b->q($this->value)} {$this->b->e($this->alias)}"
            : "{$this->b->q($this->value)}";
    }

    public function refer(): string
    {
        return $this->b->q($this->value);
    }
}
