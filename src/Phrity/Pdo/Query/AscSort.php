<?php

namespace Phrity\Pdo\Query;

class AscSort implements ExpressionInterface
{
    private $b;
    private $expression;

    public function __construct(Builder $b, ExpressionInterface $expression)
    {
        $this->b = $b;
        $this->expression = $expression;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(): string
    {
        return "{$this->expression->refer()} ASC";
    }
}
