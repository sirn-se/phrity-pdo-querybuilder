<?php

namespace Phrity\Pdo\Query;

use PDO;

trait PdoTrait
{
    private function getPdo(): PDO
    {
        return new Pdo('sqlite:sqlite.temp');
    }
}
