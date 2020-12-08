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

    protected function export($what, $from)
    {
        return (new ExporterService($from))->export($what);
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

    public function testTryToExportPrivateProperty()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => null]),
            $this->export(
                ['foo', 'bar'],
                new class {
                    public $foo = 'testFoo';
                    private $bar = 'testBar';
                }
            )
        );
    }

    public function testExportFunction()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => 'testBar']),
            $this->export(
                ['foo()', 'bar(testBar)'],
                new class {
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
                    ['bar1' => 'testBar11', 'bar2' => 'testBar12'],
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

    public function testExportDotNotationWithNested()
    {
        $this->assertEquals(
            collect([
                'foo.*' => null,
                'bar.*' => ['testBar1', 'testBar2'],
                'nested.*.nested1' => ['testNested1A', 'testNested1B'],
            ]),
            $this->export(['foo.*', 'bar.*', 'nested.*.nested1'], [
                'foo' => 'testFoo',
                'bar' => collect(['bar1' => 'testBar1', 'bar2' => 'testBar2']),
                'nested' => [
                    ['nested1' => 'testNested1A', 'nested2' => 'testNested2A'],
                    ['nested1' => 'testNested1B', 'nested2' => 'testNested2B'],
                ],
            ])
        );
    }


    public function testExportAliases()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'barKey' => 'testBar2', 'bazKey' => 'testBaz']),
            $this->export(['foo', 'bar.bar2 as barKey', 'baz as bazKey'], [
                'foo' => 'testFoo',
                'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
                'baz' => 'testBaz',
            ])
        );
    }

    public function testExportUnknownProperty()
    {
        $this->assertEquals(
            collect(['foo' => 'testFoo', 'bar' => null]),
            $this->export(['foo', 'bar'], new Model(['foo' => 'testFoo', 'baz' => 'testBaz']))
        );
    }

    public function testExportOnlyOneAttributeValue()
    {
        $this->assertEquals(
            'testFoo',
            $this->export('foo', new Model(['foo' => 'testFoo', 'baz' => 'testBaz']))
        );
    }

    public function testExportOnlyOneNestedAttributeValue()
    {
        $this->assertEquals(
            'testBar2',
            $this->export('bar.bar2', [
                'foo' => 'testFoo',
                'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
                'baz' => 'testBaz',
            ])
        );
    }

    public function testPassingUnknownTypeToExportThrowAnException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Exporter only accept array, string or int attribute. 'object' passed.");

        $this->export(
            new \stdClass(),
            ['foo' => 'testFoo', 'bar' => 'testBar', 'baz' => 'testBaz'],
        );
    }

    public function testExportDirectlyWildcardFromIterable()
    {
        $this->assertEquals(
            collect([
                collect(['bar2' => 'testBar02']),
                collect(['bar2' => 'testBar12']),
            ]),
            $this->export(['*' => ['bar2']], [
                ['bar1' => 'testBar01', 'bar2' => 'testBar02'],
                ['bar1' => 'testBar11', 'bar2' => 'testBar12'],
            ])
        );
    }
}
