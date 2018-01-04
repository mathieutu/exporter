<?php

namespace MathieuTu\Exporter\Tests;

use MathieuTu\Exporter\Tests\Fixtures\Model;
use PHPUnit\Framework\TestCase;

class ExporterTest extends TestCase
{
    public function testExport()
    {
        $exportable = new Model([
            'foo'    => 'testFoo',
            'bar'    => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
            'foobar' => ['foobar1' => 'testFooBar1', 'foobar2' => 'testFooBar2'],
            'baz'    => [
                ['baz1' => 'baz1A', 'baz2' => 'baz2A', 'baz3' => 'baz3A'],
                ['baz1' => 'baz1B', 'baz2' => 'baz2B', 'baz3' => 'baz3B'],
                ['baz1' => 'baz1C', 'baz2' => 'baz2C', 'baz3' => 'baz3C'],
            ],
        ]);

        $attributes = [
            'test(Mathieu)',
            'test(TUDISCO)',
            'bar'    => ['bar2'],
            'bar.bar1',
            'foobar' => 'foobar2',
            'baz'    => ['*' => ['baz1', 'baz3']],
        ];

        $expectedExport = [
            'test'     => 'testTUDISCO',
            'bar'      => ['bar2' => 'testBar2'],
            'bar.bar1' => 'testBar1',
            'foobar'   => 'testFooBar2',
            'baz'      => [
                ['baz1' => 'baz1A', 'baz3' => 'baz3A'],
                ['baz1' => 'baz1B', 'baz3' => 'baz3B'],
                ['baz1' => 'baz1C', 'baz3' => 'baz3C'],
            ],
        ];

        $this->assertEquals($expectedExport, $exportable->export($attributes)->toArray());
    }
}
