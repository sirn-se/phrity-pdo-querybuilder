<?php

namespace Phrity\Pdo\Query;

class IsNullExpression implements ExpressionInterface
{
    private $b;
    private $left;

    public function __construct(Builder $b, ExpressionInterface $left)
    {
        $this->b = $b;
        $this->left = $left;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function refer(bool $use_alias = false, bool $use_context = false): string
    {
        return "({$this->left->refer($use_alias, $use_context)} IS NULL)";
    }
}
