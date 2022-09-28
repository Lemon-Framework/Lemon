<?php

declare(strict_types=1);

namespace Lemon\Database;

use IteratorAggregate;
use Lemon\Database\Exceptions\QueryException;
use Traversable;

class Query implements IteratorAggregate
{
    public const Operators = [
        '=',
        '>',
        '<',
        '>=',
        '<=',
        '<>',
        'between',
        'like',
        'in'
    ];

    protected string $command = 'SELECT';
    protected string $table;
    protected array $where = [];
    protected array $joins = [];
    protected array $columns = [];
    protected int $count;

    public function where(string $key, string $operator, ?string $value = null): static
    {
        if (!$value) {
            $operator = '=';
            $value = $operator;
        } else {
            $operator = strtolower($operator);
            if (!in_array($operator, self::Operators)) {
                throw new QueryException('Unsupported operator '.$operator);
            }
        }

        $this->where[] = [$key, $operator, $value];
        return $this;
    }

    public function orWhere(string $key, string $operator, ?string $value = null): static
    {
        $this->where[] = 'OR';
        return $this->where($key, $operator, $value);
    }

    public function whereNot(string $key, string $operator, ?string $value = null): static
    {
        $this->where[] = 'NOT';
        return $this->where($key, $operator, $value);
    }

    public function get(array $columns = ['*']): static
    {
        $this->command = 'SELECT';
        $this->columns = $columns;
        return $this;
    }

    public function set(...$data): static
    {
        $this->command = 'UPDATE';
        $this->columns = $data;
        return $this;
    }

    public function insert(...$data): static
    {
        $this->command = 'INSERT';
        $this->columns = $data;
        return $this;
    }

    public function create(...$data): static
    {
        return $this->insert(...$data);
    }

    public function delete(): static
    {
        $this->command = 'DELETE';
        return $this;
    }

    public function first(int $count, array $columns = ['*']): static
    {
        $this->count = $count;
        return $this->get($columns);
    }

    public function join(string $table, string $column, string $relation): static
    {
        $this->joins[] = [
            $table,
            $column,
            $relation,
            'INNER',
        ];
        return $this;
    }

    public function leftJoin(string $table, string $column, string $relation): static
    {
        $this->joins[] = [
            $table,
            $column,
            $relation,
            'LEFT',
        ];
        return $this;
    }

    public function rightJoin(string $table, string $column, string $relation): static
    {
        $this->joins[] = [
            $table,
            $column,
            $relation,
            'RIGHT',
        ];
        return $this;
    }

    public function getIterator(): Traversable
    {
        // TODO
    }

    public function execute()
    {
        
    }
}
