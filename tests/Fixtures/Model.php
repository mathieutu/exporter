<?php
declare(strict_types=1);

namespace MathieuTu\Exporter\Tests\Fixtures;

use MathieuTu\Exporter\Exporter;

class Model
{
    use Exporter;

    private $attributes;

    private $otherProperty;

    public function __construct(array $attributes, $otherProperty = null)
    {
        $this->attributes = $attributes;
        $this->otherProperty = $otherProperty;
    }

    public function test($value): string
    {
        return 'test' . $value;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __isset($name): bool
    {
        return isset($this->attributes[$name]);
    }

    public function getOtherProperty()
    {
        return $this->otherProperty;
    }
}
