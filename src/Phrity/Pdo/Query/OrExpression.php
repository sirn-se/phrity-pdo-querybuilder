<?php

namespace Phrity\Pdo\Query;

class OrExpression implements ExpressionInterface
{
    private $b;
    private $contitions;

    public function __construct(Builder $b, ExpressionInterface ...$contitions)
    {
        $this->b = $b;
        $this->contitions = $contitions;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function refer(bool $use_alias = false, bool $use_context = false): string
    {
        return '(' . implode(' OR ', array_map(function ($contition) use ($use_alias, $use_context) {
            return $contition->refer($use_alias, $use_context);
        }, $this->contitions)) . ')';
    }
}
