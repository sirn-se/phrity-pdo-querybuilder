<?php

declare(strict_types=1);

namespace Phrity\Pdo\Query;

use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    use PdoTrait;

    public function setUp(): void
    {
        error_reporting(-1);
    }

    public function testFrom(): void
    {
        $b = new Builder($this->getPdo());
        $t = $b->table('table_name');
        $f = $b->field($t, 'field_name');
        $v = $b->value('my string');
        $s = new Select($b, $t);
        $this->assertSame("SELECT * FROM table_name;", "{$s}");
        $s = new Select($b, $t, $f, $v);
        $this->assertSame("SELECT table_name.field_name,'my string' FROM table_name;", "{$s}");
        $s = new Select($b, $v);
        $this->assertSame("SELECT 'my string';", "{$s}");
        $s = $b->select($t, $f, $v);
        $this->assertSame("SELECT table_name.field_name,'my string' FROM table_name;", "{$s}");
    }

    public function testFromAlias(): void
    {
        $b = new Builder($this->getPdo(), true);
        $t = $b->table('table_name', 'table_alias');
        $f = $b->field($t, 'field_name', 'field_alias');
        $v = $b->value('my string');
        $s = new Select($b, $t);
        $this->assertSame("SELECT * FROM `table_name` AS `table_alias`;", "{$s}");
        $s = new Select($b, $t, $f, $v);
        $this->assertSame("SELECT `table_alias`.`field_name` AS `field_alias`,'my string' FROM `table_name` AS `table_alias`;", "{$s}");
        $s = new Select($b, $v);
        $this->assertSame("SELECT 'my string';", "{$s}");
        $s = $b->select($t, $f, $v);
        $this->assertSame("SELECT `table_alias`.`field_name` AS `field_alias`,'my string' FROM `table_name` AS `table_alias`;", "{$s}");
    }

    public function testRuntime(): void
    {
        $b = new Builder($this->getPdo());
        $s = new Select($b);
        $t1 = $b->table('table_1');
        $t2 = $b->table('table_2');
        $this->assertSame("SELECT *;", "{$s}");
        $s->addFrom($t1);
        $this->assertSame("SELECT * FROM table_1;", "{$s}");
        $s->addFrom($t2);
        $this->assertSame("SELECT * FROM table_1,table_2;", "{$s}");
        $s->addSelect($b->field($t1, 'field_1'));
        $this->assertSame("SELECT table_1.field_1 FROM table_1,table_2;", "{$s}");
        $s->addSelect($b->value('my value'));
        $this->assertSame("SELECT table_1.field_1,'my value' FROM table_1,table_2;", "{$s}");
    }
}
