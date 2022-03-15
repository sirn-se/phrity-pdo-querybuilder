<?php

namespace Phrity\Pdo\Query;

class Assign
{
    private $b;
    private $target;
    private $source;

    public function __construct(Builder $b, ExpressionInterface $target, ExpressionInterface $source)
    {
        $this->b = $b;
        $this->target = $target;
        $this->source = $source;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(): string
    {
        return "{$this->target->refer()}={$this->source->refer()}";
    }
}
