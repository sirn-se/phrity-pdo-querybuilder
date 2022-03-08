<?php

namespace Phrity\Pdo\Query;

class Value implements SqlInterface
{
    private $b;
    private $value;

    public function __construct(Builder $b, $value)
    {
        $this->b = $b;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->b->q($this->value);
    }
}
