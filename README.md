[![Build Status](https://github.com/sirn-se/phrity-pdo-querybuilder/actions/workflows/acceptance.yml/badge.svg)](https://github.com/sirn-se/phrity-pdo-querybuilder/actions)
[![Coverage Status](https://coveralls.io/repos/github/sirn-se/phrity-pdo-querybuilder/badge.svg?branch=main)](https://coveralls.io/github/sirn-se/phrity-pdo-querybuilder?branch=main)

# PDO Query Builder

SQL query builder for PHP PDO connections.

Current version supports PHP `^7.3|^8.0`.

## Installation

Install with [Composer](https://getcomposer.org/);
```
composer require phrity/phrity-pdo-querybuilder
```

## Select

### Basic operation

Use the builder and/or convenicance methods to define Select query.

```php
$builder = new Builder($pdo);
$select = $builder->select(
    $table = $builder->table('table_name'),
    $table->field('field_name'),
    $builder->value('my string')
);
echo $select->sql();
```
```
SELECT table_name.field_name,'my string' FROM table_name;
```

### Where conditions

Adding conditions to Where clause.

```php
$builder = new Builder($pdo);
$select = $builder->select(
    $table = $builder->table('table_name'),
    $table->field('field_name')
);
$select->where($builder->and(
  $builder->eq($table->field('field_name'), $builder->value('my string')),
  $builder->gte($table->field('field_name_2'), $builder->value(1234))
));
echo $select->sql();
```
```
SELECT table_name.field_name FROM table_name WHERE ((table_name.field_name='my string') AND (table_name.field_name_2>=1234));
```

### Joins

Supports leftJoin and innerJoin.

```php
$builder = new Builder($pdo);
$table = $builder->table('table_name');
$join_table = $builder->table('join_table_name');
$select = $builder->select(
    $table,
    $table->field('field_name'),
    $join_table->field('join_field_name')
);
$select->innerJoin($join_table)->on(
  $builder->eq($join_table->field('join_field_name'), $table->field('field_name'))
);
echo $select->sql();
```
```
SELECT table_name.field_name,join_table_name.join_field_name FROM table_name INNER JOIN join_table_name ON (join_table_name.join_field_name=table_name.field_name);
```

## Insert

### Basic operation

Use the builder and/or convenicance methods to define Insert query.

```php
$builder = new Builder($pdo);
$insert = $builder->insert(
  $table = $builder->table('table_name'),
  $builder->assign($table->field('field_name'), $builder->value('my string')),
);
echo $update->sql();
```
```
INSERT INTO table_name (table_name.field_name) VALUES ('my string');
```

### Where conditions

Adding conditions to Where clause.

```php
$builder = new Builder($pdo);
$insert = $builder->insert(
  $table = $builder->table('table_name'),
  $builder->assign($table->field('field_name'), $builder->value('my new string')),
);
$insert->where($builder->and(
  $builder->eq($table->field('field_name'), $builder->value('my string')),
  $builder->gte($table->field('field_name_2'), $builder->value(1234))
));
echo $update->sql();
```
```
INSERT INTO table_name (table_name.field_name) VALUES ('my string') WHERE ((table_name.field_name='my string') AND (table_name.field_name_2>=1234));
```

## Update

### Basic operation

Use the builder and/or convenicance methods to define Update query.

```php
$builder = new Builder($pdo);
$update = $builder->update(
  $table = $builder->table('table_name'),
  $builder->assign($table->field('field_name'), $builder->value('my string')),
);
echo $update->sql();
```
```
UPDATE table_name SET table_name.field_name='my string';
```

### Where conditions

Adding conditions to Where clause.

```php
$builder = new Builder($pdo);
$update = $builder->update(
  $table = $builder->table('table_name'),
  $builder->assign($table->field('field_name'), $builder->value('my new string')),
);
$update->where($builder->and(
  $builder->eq($table->field('field_name'), $builder->value('my string')),
  $builder->gte($table->field('field_name_2'), $builder->value(1234))
));
echo $update->sql();
```
```
UPDATE table_name SET table_name.field_name='my new string' WHERE ((table_name.field_name='my string') AND (table_name.field_name_2>=1234));
```

## Build compontens

Methods on Builder.

```php
// Core components
$builder->table(string $name, string $alias = null): Table
$builder->field(Table $table, string $name, string $alias = null): Field
$builder->value($value, string $alias = null): Value

// Query statement components
$builder->select(Table $table = null, ExpressionInterface ...$select): Select
$builder->insert(Table $table, Assign ...$assign): Insert
$builder->update(Table $table, Assign ...$assign): Update

// Join & Assign expressions
$builder->innerJoin(Table $join): InnerJoin
$builder->leftJoin(Table $join): LeftJoin
$builder->assign(ExpressionInterface $target, ExpressionInterface $source): Assign

// Conditional expression sets
$builder->and(ExpressionInterface ...$contitions): AndExpression
$builder->or(ExpressionInterface ...$contitions): OrExpression

// Conditional expressions
$builder->eq(ExpressionInterface $left, ExpressionInterface $right): EqExpression
$builder->neq(ExpressionInterface $left, ExpressionInterface $right): NeqExpression
$builder->gt(ExpressionInterface $left, ExpressionInterface $right): GtExpression
$builder->gte(ExpressionInterface $left, ExpressionInterface $right): GteExpression
$builder->lt(ExpressionInterface $left, ExpressionInterface $right): LtExpression
$builder->lte(ExpressionInterface $left, ExpressionInterface $right): LteExpression

// Function expressions
$builder->now(): NowFunction
$builder->curDate(): CurDateFunction

// Sort & limit expressions
$builder->asc(ExpressionInterface $expression): AscSort
$builder->desc(ExpressionInterface $expression): DescSort
$builder->limit(int $limit = null, int $offset = null): Limit
```
