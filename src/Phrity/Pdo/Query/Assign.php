<?php

namespace Phrity\Pdo\Query;

class Assign
{
    private $b;
    private $target;
    private $source;

    public function __construct(Builder $b, Field $target, ExpressionInterface $source)
    {
        $this->b = $b;
        $this->target = $target;
        $this->source = $source;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function target(bool $use_alias = false, bool $use_context = false): string
    {
        return $this->target->refer($use_alias, $use_context);
    }

    public function source(bool $use_alias = false, bool $use_context = false): string
    {
        return $this->source->refer($use_alias, $use_context);
    }

    public function define(bool $use_alias = false, bool $use_context = false): string
    {
        return "{$this->target->refer($use_alias, $use_context)}={$this->source->refer($use_alias, $use_context)}";
    }
}
