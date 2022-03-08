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
     * Database dependent quote method
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
}
