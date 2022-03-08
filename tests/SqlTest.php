<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class SqlTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testTable(): void
    {
        $b = new Builder($this->getPdo());
        $t = new Table($b, 'table_name');
        $this->assertSame('table_name', "{$t}");
        $t = $b->table('table_name');
        $this->assertSame('table_name', "{$t}");
        $t = new Table($b, 'table_name', 'table_alias');
        $this->assertSame('table_name AS table_alias', "{$t}");
        $t = $b->table('table_name', 'table_alias');
        $this->assertSame('table_name AS table_alias', "{$t}");

        $b = new Builder($this->getPdo(), true);
        $t = new Table($b, 'table_name');
        $this->assertSame('`table_name`', "{$t}");
        $t = $b->table('table_name');
        $this->assertSame('`table_name`', "{$t}");
        $t = new Table($b, 'table_name', 'table_alias');
        $this->assertSame('`table_name` AS `table_alias`', "{$t}");
        $t = $b->table('table_name', 'table_alias');
        $this->assertSame('`table_name` AS `table_alias`', "{$t}");
    }

    public function testField(): void
    {
        $b = new Builder($this->getPdo());
        $t = $b->table('table_name');
        $f = new Field($b, $t, 'field_name');
        $this->assertSame('table_name.field_name', "{$f}");
        $f = $b->field($t, 'field_name');
        $this->assertSame('table_name.field_name', "{$f}");
        $f = new Field($b, $t, 'field_name', 'field_alias');
        $this->assertSame('table_name.field_name AS field_alias', "{$f}");
        $f = $b->field($t, 'field_name', 'field_alias');
        $this->assertSame('table_name.field_name AS field_alias', "{$f}");

        $b = new Builder($this->getPdo(), true);
        $t = $b->table('table_name');
        $f = new Field($b, $t, 'field_name');
        $this->assertSame('`table_name`.`field_name`', "{$f}");
        $f = $b->field($t, 'field_name');
        $this->assertSame('`table_name`.`field_name`', "{$f}");
        $f = new Field($b, $t, 'field_name', 'field_alias');
        $this->assertSame('`table_name`.`field_name` AS `field_alias`', "{$f}");
        $f = $b->field($t, 'field_name', 'field_alias');
        $this->assertSame('`table_name`.`field_name` AS `field_alias`', "{$f}");
    }

    public function testValue(): void
    {
        $b = new Builder($this->getPdo());
        $v = new Value($b, 'my string');
        $this->assertSame("'my string'", "{$v}");
        $v = $b->value('my string');
        $this->assertSame("'my string'", "{$v}");
        $v = new Value($b, 1234);
        $this->assertSame('1234', "{$v}");
        $v = $b->value(1234);
        $this->assertSame('1234', "{$v}");
        $v = new Value($b, true);
        $this->assertSame('1', "{$v}");
        $v = $b->value(true);
        $this->assertSame('1', "{$v}");
    }
}
