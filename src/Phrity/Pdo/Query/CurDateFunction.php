<?php

namespace Phrity\Pdo\Query;

class CurDateFunction implements ExpressionInterface
{
    private $b;

    public function __construct(Builder $b)
    {
        $this->b = $b;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function define(): string
    {
        return $this->refer();
    }

    public function refer(): string
    {
        return 'CURDATE()';
    }
}
