<?php
declare(strict_types=1);

namespace MathieuTu\Exporter\Tests;

use MathieuTu\Exporter\Tests\Fixtures\Model;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
{
    public function testExport()
    {
        $this->assertSame(
            $this->expectedExport(),
            $this->exportable()->export($this->attributesToExport())->toArray()
        );
    }

    protected function expectedExport(): array
    {
        return [
            'test' => 'testTUDISCO',
            'bar' => ['bar2' => 'testBar2'],
            'bar.bar1' => 'testBar1',
            'foobar' => 'testFooBar2',
            'baz' => [
                ['baz1' => 'baz1A', 'baz3' => 'baz3A'],
                ['baz1' => 'baz1B', 'baz3' => 'baz3B'],
                ['baz1' => 'baz1C', 'baz3' => 'baz3C'],
            ],
            'otherProperty' => ['otherPropery1' => 'testOtherProperty1'],
        ];
    }

    protected function exportable(): Model
    {
        return new Model([
            'foo' => 'testFoo',
            'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
            'foobar' => ['foobar1' => 'testFooBar1', 'foobar2' => 'testFooBar2'],
            'baz' => [
                ['baz1' => 'baz1A', 'baz2' => 'baz2A', 'baz3' => 'baz3A'],
                ['baz1' => 'baz1B', 'baz2' => 'baz2B', 'baz3' => 'baz3B'],
                ['baz1' => 'baz1C', 'baz2' => 'baz2C', 'baz3' => 'baz3C'],
            ],
        ], ['otherPropery1' => 'testOtherProperty1', 'otherPropery2' => 'testOtherProperty2']);
    }

    protected function attributesToExport(): array
    {
        return [
            'test(Mathieu)',
            'test(TUDISCO)',
            'bar' => ['bar2'],
            'bar.bar1',
            'foobar' => 'foobar2',
            'baz' => ['*' => ['baz1', 'baz3']],
            'otherProperty' => ['otherPropery1'],
        ];
    }
}
