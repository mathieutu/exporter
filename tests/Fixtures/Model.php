<?php

namespace MathieuTu\Exporter\Tests\Fixtures;

use MathieuTu\Exporter\Exporter;

class Model
{
    use Exporter;

    private $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function test($value): string
    {
        return 'test' . $value;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }
}
