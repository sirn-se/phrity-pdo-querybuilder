<?php

namespace Phrity\Pdo\Query;

class AndExpression implements ExpressionInterface
{
    private $b;
    private $contitions;

    public function __construct(Builder $b, ExpressionInterface ...$contitions)
    {
        $this->b = $b;
        $this->contitions = $contitions;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(): string
    {
        return $this->refer();
    }

    public function refer(): string
    {
        return '(' . implode(' AND ', array_map(function ($contition) {
            return $contition->define();
        }, $this->contitions)) . ')';
    }
}
