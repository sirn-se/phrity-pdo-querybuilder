<?php

namespace Phrity\Pdo\Query;

class EqExpression implements ExpressionInterface
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

    public function define(): string
    {
        return $this->refer();
    }

    public function refer(): string
    {
        return "({$this->left->refer()}={$this->right->refer()})";
    }
}
