<?php

namespace MathieuTu\Exporter\Tests;

use MathieuTu\Exporter\ExporterService;
use MathieuTu\Exporter\Tests\Fixtures\Collection;
use MathieuTu\Exporter\Tests\Fixtures\Model;
use PHPUnit\Framework\TestCase;

class ExporterServiceTest extends TestCase
{

    public function testExportFromObject()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => 'testBar']),
            $this->export(['foo', 'bar'], new Model(['foo' => 'testFoo', 'bar' => 'testBar', 'baz' => 'testBaz']))
        );
    }

    public function testExportFromObjectWithGetter()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'otherProperty' => 'testOtherProperty']),
            $this->export(['foo', 'otherProperty'], new Model(['foo' => 'testFoo'], 'testOtherProperty'))
        );
    }

    public function testExportFromArray()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => 'testBar']),
            $this->export(['foo', 'bar'], ['foo' => 'testFoo', 'bar' => 'testBar', 'baz' => 'testBaz'])
        );
    }

    protected function export(array $what, $from): \Illuminate\Support\Collection
    {
        return (new ExporterService($from))->export($what);
    }

    public function testExportFromArrayAccess()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => 'testBar']),
            $this->export(['foo', 'bar'], new Collection([
                'foo' => 'testFoo',
                'bar' => 'testBar',
                'baz' => 'testBaz',
            ]))
        );
    }

    public function testExportFunction()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => 'testBar']),
            $this->export(
                ['foo()', 'bar(testBar)'],
                new class
                {
                    public function foo(): string
                    {
                        return 'testFoo';
                    }

                    public function bar($arg)
                    {
                        return $arg;
                    }
                }
            )
        );
    }

    public function testExportNestedArray()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => collect(['bar2' => 'testBar2'])]),
            $this->export(['foo', 'bar' => ['bar2']], [
                'foo' => 'testFoo',
                'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
                'baz' => 'testBaz',
            ])
        );
    }

    public function testExportNestedArrayWithWildcard()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => collect([
                collect(['bar2' => 'testBar02']),
                collect(['bar2' => 'testBar12']),
            ])]),
            $this->export(['foo', 'bar' => ['*' => ['bar2']]], [
                'foo' => 'testFoo',
                'bar' => [
                    ['bar1' => 'testBar01', 'bar2' => 'testBar02'],
                    ['bar1' => 'testBar11', 'bar2' => 'testBar12']
                ],
            ])
        );
    }

    public function testExportNestedAttribute()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => 'testBar2']),
            $this->export(['foo', 'bar' => 'bar2'], [
                'foo' => 'testFoo',
                'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
                'baz' => 'testBaz',
            ])
        );
    }

    public function testExportDotNotation()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar.bar2' => 'testBar2']),
            $this->export(['foo', 'bar.bar2'], [
                'foo' => 'testFoo',
                'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
                'baz' => 'testBaz',
            ])
        );
    }
}
