<?php

namespace Phrity\Pdo\Query;

class Limit implements ExpressionInterface
{
    private $b;
    private $limit;
    private $offset;

    public function __construct(Builder $b, int $limit = null, int $offset = null)
    {
        $this->b = $b;
        $this->limit = $limit;
        $this->offset = $offset;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(): string
    {
        if (is_null($this->limit)) {
            return '';
        }
        if (is_null($this->offset)) {
            return "LIMIT {$this->limit}";
        }
        return "LIMIT {$this->offset},{$this->limit}";
    }
}
