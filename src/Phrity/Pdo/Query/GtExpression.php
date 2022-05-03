<?php

namespace Phrity\Pdo\Query;

class GtExpression implements ExpressionInterface
{
    private $b;
    private $left;
    private $right;

    public function __construct(Builder $b, ExpressionInterface $left, ExpressionInterface $right)
    {
        $this->b = $b;
        $this->left = $left;
        $this->right = $right;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function refer(bool $use_alias = false, bool $use_context = false): string
    {
        return "({$this->left->refer($use_alias, $use_context)}>{$this->right->refer($use_alias, $use_context)})";
    }
}
