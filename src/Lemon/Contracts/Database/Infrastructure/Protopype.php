<?php

declare(strict_types=1);

namespace Lemon\Contracts\Database\Infrastructure;

interface Protopype
{
    public function name(string $name): static;

    public function char(string $name, int $size = 1): Column;

    public function varChar(string $name, int $size = 256): Column;

    public function string(string $name, int $size = 256): Column;

    public function binary(string $name, int $size = 1): Column;

    public function varBinary(string $name, int $size = 256): Column;

    public function tinyText(string $name): Column;

    public function text(string $name, int $size = 256): Column;

    public function mediumText(string $name): Column;

    public function longText(string $name): Column;

    public function tinyBlob(string $name): Column;

    public function blob(string $name, int $size = 256): Column;

    public function mediumBlob(string $name): Column;

    public function longBlob(string $name): Column;

    public function bool(string $name): Column; 

    public function bit(string $name, int $size = 1);

    public function tinyInt(string $name, int $size = 255): Column;
 
    public function smallInt(string $name, int $size = 255): Column;
  
    public function mediumInt(string $name, int $size = 255): Column;

    public function int(string $name, int $size = 4): Column;

    public function id(): Column;

    public function bigintInt(string $name, int $size = 255): Column;

    public function float(string $name, int $size = 4, int $digits = 16): Column;

    public function double(string $name, int $size = 4, int $digits = 16): Column;

    public function date(string $name): Column;

    public function datetime(string $name): Column;

    public function timestamp(string $name): Column;

    public function time(string $name): Column;

    public function year(string $name): Column;

    public function build(string $name): Column;
}
