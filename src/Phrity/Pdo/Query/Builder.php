<?php

namespace Phrity\Pdo\Query;

use PDO;

class Builder
{
    private $pdo;
    private $escape;

    public function __construct(PDO $pdo, bool $escape = false)
    {
        $this->pdo = $pdo;
        $this->escape = $escape;
    }


    /* ---------- Formatting methods ------------------------------------------------- */

    /**
     * Quote method, database dependent
     */
    public function quote(string $string, int $type = PDO::PARAM_STR)
    {
        return $this->pdo->quote($string, $type);
    }

    /**
     * Quote utility method, quotes according to type and database
     */
    public function q($input, string $type = null)
    {
        switch ($type ?: gettype($input)) {
            case 'string':
                return $this->quote($input);
            case 'integer':
                return (int)$input;
            case 'float':
                return (float)$input;
            case 'boolean':
                return (int)$input;
            case 'NULL':
                return 'NULL';
            default:
                return $input;
        }
    }

    /**
     * Escape method
     */
    public function escape(string $string): string
    {
        return "`{$string}`";
    }

    /**
     * Escape utility method, applied according to settings
     */
    public function e(string $string): string
    {
        return $this->escape ? "`{$string}`" : $string;
    }


    /* ---------- Generator methods -------------------------------------------------- */

    public function table(string $name, string $alias = null): Table
    {
        return new Table($this, $name, $alias);
    }

    public function field(Table $table, string $name, string $alias = null): Field
    {
        return new Field($this, $table, $name, $alias);
    }

    public function value($value, string $alias = null): Value
    {
        return new Value($this, $value, $alias);
    }


    /* ---------- Query methods ------------------------------------------------------ */

    public function select(Table $table = null, ExpressionInterface ...$select): Select
    {
        return new Select($this, $table, ...$select);
    }

    public function update(Table $table = null, Assign ...$assign): Update
    {
        return new Update($this, $table, ...$assign);
    }

    public function innerJoin(Table $join): InnerJoin
    {
        return new InnerJoin($this, $join);
    }

    public function leftJoin(Table $join): LeftJoin
    {
        return new LeftJoin($this, $join);
    }

    public function assign(ExpressionInterface $target, ExpressionInterface $source): Assign
    {
        return new Assign($this, $target, $source);
    }


    /* ---------- Condition methods -------------------------------------------------- */

    public function and(ExpressionInterface ...$contitions): AndExpression
    {
        return new AndExpression($this, ...$contitions);
    }

    public function eq(ExpressionInterface $left, ExpressionInterface $right): EqExpression
    {
        return new EqExpression($this, $left, $right);
    }

    public function gte(ExpressionInterface $left, ExpressionInterface $right): GteExpression
    {
        return new GteExpression($this, $left, $right);
    }


    /* ---------- Function methods --------------------------------------------------- */

    public function now(): NowFunction
    {
        return new NowFunction($this);
    }

    public function curDate(): CurDateFunction
    {
        return new CurDateFunction($this);
    }


    /* ---------- Sort & limit methods ----------------------------------------------- */

    public function asc(ExpressionInterface $expression): AscSort
    {
        return new AscSort($this, $expression);
    }

    public function desc(ExpressionInterface $expression): DescSort
    {
        return new DescSort($this, $expression);
    }

    public function limit(int $limit = null, int $offset = null): Limit
    {
        return new Limit($this, $limit, $offset);
    }
}
