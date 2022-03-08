<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;
use PDO;

class FormattingTest extends TestCase
{
    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testQuote(): void
    {
        $b = new Builder($this->getPdo());
        $this->assertSame("'My string'", $b->quote('My string'));
        $this->assertSame("'My ''string'", $b->quote("My 'string"));
        $this->assertSame("'My string'", $b->quote('My string', PDO::PARAM_STR));
        $this->assertSame("'123'", $b->quote('123'));
        $this->assertSame("'123'", $b->quote('123', PDO::PARAM_INT));
    }

    public function testQ(): void
    {
        $b = new Builder($this->getPdo());
        $this->assertSame("'My string'", $b->q('My string'));
        $this->assertSame(1234, $b->q(1234));
        $this->assertSame(1234.567, $b->q(1234.567));
        $this->assertSame(0, $b->q(false));
        $this->assertSame(1, $b->q(true));
        $this->assertSame('NULL', $b->q(null));
        $this->assertSame("'1234'", $b->q('1234'));
        $this->assertSame(1234, $b->q('1234', 'integer'));
    }

    public function testEscape(): void
    {
        $b = new Builder($this->getPdo());
        $this->assertSame('`My string`', $b->escape('My string'));
        $b = new Builder($this->getPdo(), true);
        $this->assertSame('`My string`', $b->escape('My string'));
    }

    public function testE(): void
    {
        $b = new Builder($this->getPdo());
        $this->assertSame('My string', $b->e('My string'));
    }

    private function getPdo(): PDO
    {
        return new Pdo('sqlite:sqlite.temp');
    }
}
