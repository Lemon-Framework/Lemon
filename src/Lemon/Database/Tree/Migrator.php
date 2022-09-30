<?php

declare(strict_types=1);

namespace Lemon\Database\Tree;

use Lemon\Contracts\Config\Config;
use Lemon\Contracts\Database\Database;
use Lemon\Contracts\Events\Dispatcher;
use Lemon\Database\Exceptions\ModelException;
use Lemon\Database\Tree\Attributes\AutoIncrement;
use Lemon\Database\Tree\Attributes\Binary;
use Lemon\Database\Tree\Attributes\Size;
use Lemon\Database\Tree\Attributes\Table;
use Lemon\Database\Tree\Attributes\Unsigned;
use Lemon\Support\Filesystem;
use ReflectionClass;
use ReflectionProperty;

class Migrator
{
    public const IntSizes = [
        127 => 'TINYINT',
        32767 => 'SMALLINT',
        8388607 => 'MEDIUMINT',
        2147483647 => 'INT',
        9223372036854775807 => 'BIGINT',
    ];

    public const StringSizes = [
        null => '',
        255 => 'TINY',
        65535 => '',
        16777215 => 'MEDIUM',
        4294967295 => 'LONG',
    ];

    public function __construct(
        private Database $database,
        private Config $config,
        private Dispatcher $events
    ) {
        
    }

    public function migrate(): void
    {
        $folder = $this->config->file('database.models');
        foreach (Filesystem::listDir($folder) as $file) {
            preg_match("/^{$folder}(.+?)\.php\$/", $file, $matches);
            $class = str_replace(DIRECTORY_SEPARATOR, '\\', $matches[1]);
            $query = $this->migrateModel($class);
            if ($query) {
                /** @phpstan-ignore-next-line */
                $this->database->query($query);
                $this->events->fire('migration', $class);
            }
        }
    }

    public function migrateModel(string $model): ?string
    {
        if (get_parent_class($model) !== Model::class) {
            return null;
        }

        $reflection = new ReflectionClass($model);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $attributes = $reflection->getAttributes(Table::class);

        if (empty($attributes)) {
            throw new ModelException('Model '.$model.' does not specify table name'); // TODO CONSULTATNT
        }

        $table = $attributes[0]->newInstance()->table;

        $result = [];

        foreach ($properties as $property) {
            $result[] = $this->buildColumn($property);
        }

        return 'CREATE TABLE `'.$table.'` ('.implode(', ', $result).')';
    }
    
    public function buildColumn(ReflectionProperty $property): string
    {
        $type = $property->getType();
        $sql_type = $this->buildType((string) $type, $property);
        $nullable = $type->allowsNull();
        $default = $property->getDefaultValue();
        
        return $property->getName().$sql_type.($nullable ? '' : 'NOT NULL').($default ? 'DEFAULT='.$default : '');
    }

    public function buildType(string $type, ReflectionProperty $property): string
    {
        return match ($type) {
            'bool' => 'BOOL',
            'int' => $this->buildInt($property),
            'float' => 'FLOAT',
            'string' => $this->buildString($property),
            '\DateTime' => 'DATETIME',
            default => throw new ModelException('Property '.$property->getName().' has invalid type'),
        };
    }

    public function buildInt(ReflectionProperty $property): string
    {
        $size = 
            $property->getAttributes(Size::class)[0]->newInstance()->size
            ?? null
            ;

        $unsigned = isset($property->getAttributes(Unsigned::class)[0]);

        foreach (static::IntSizes as $type_size => $type) {
            if ($size <= $type_size * $unsigned + ($unsigned - 1)) {
                if (isset($property->getAttributes(AutoIncrement::class)[0])) {
                    $type = AutoIncrement::compile($this->database->getDriver(), $type);
                }
                return $type;
            }
        }

        throw new ModelException('Int is too big');
    }

    public function buildString(ReflectionProperty $property): string
    {
        $type = 'TEXT';
        if (isset($property->getAttributes(Binary::class)[0])) {
            $type = 'BLOB';
        }

        $size = 
            $property->getAttributes(Size::class)[0]->newInstance()->size
            ?? null
        ;

        foreach (static::StringSizes as $type_size => $type) {
            if ($size <= $type_size) {
                return $type;
            }
        }

        throw new ModelException('String can\'t be bigger than 4294967295');
    }
}
