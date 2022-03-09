<?php

namespace Phrity\Pdo\Query;

class Eq implements SqlInterface
{
    private $b;
    private $left;
    private $right;

    public function __construct(SqlInterface $left, SqlInterface $right)
    {
        $this->b = $b;
        $this->left = $left;
        $this->right = $right;
    }

    public function define(): string
    {
        return "({$this->left->refer()}={$this->right->refer()})";
    }
}
