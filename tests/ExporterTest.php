<?php
declare(strict_types=1);

namespace MathieuTu\Exporter\Tests;

use DateTime;
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

    public function testCompleteExample()
    {
        $blogPost = new class {
            use \MathieuTu\Exporter\Exporter;

            public int $id = 42;
            public string $title = 'Exporting Complex Data Structures with PHP Exporter';
            public DateTime $publishedAt {
                get => new DateTime('2026-02-15');
            }

            public array $author = [
                'id' => 1,
                'name' => 'Mathieu Tudisco',
                'email' => 'oss@mathieutu.dev',
                'bio' => 'PHP Developer',
            ];

            public array $comments {
                get => [
                    (object)['id' => 1, 'author' => 'Alice', 'content' => 'Great article!', 'likes' => 5],
                    (object)['id' => 2, 'author' => 'Bob', 'content' => 'Very helpful', 'likes' => 3],
                    (object)['id' => 3, 'author' => 'Charlie', 'content' => 'Thanks for sharing', 'likes' => 7],
                ];
            }

                public function getSlug(): string
                {
                    return strtolower(str_replace(' ', '-', $this->title));
                }

                public function getExcerpt(): string
                {
                    return substr($this->title, 0, 17) . '...';
                }
        };

        // Export a complete, structured API response with all features combined
        $apiResponse = $blogPost->export([
            'id',
            'title',
            'slug',                                       // Automatic getter (getSlug)
            'publishedAt' => 'format(j F Y)',           // Native nested function with parameter
            'excerpt as summary',                         // Automatic getter + alias
            'author as writer' => ['name', 'bio'],        // Nested export with alias on key
            'author' => 'name',                           // Nested export
            'author.email as contact',                    // Nested export with dot notation + alias
            'comments as feedback' => [                   // Collection with alias on key
                '*' => ['author as commenter', 'likes']   // Wildcard + alias on nested attribute
            ],
        ]);

        $this->assertSame([
            'id' => 42,
            'title' => 'Exporting Complex Data Structures with PHP Exporter',
            'slug' => 'exporting-complex-data-structures-with-php-exporter',
            'publishedAt' => '15 February 2026',
            'summary' => 'Exporting Complex...',
            'writer' => [
                'name' => 'Mathieu Tudisco',
                'bio' => 'PHP Developer',
            ],
            'author' => 'Mathieu Tudisco',
            'contact' => 'oss@mathieutu.dev',
            'feedback' => [
                ['commenter' => 'Alice', 'likes' => 5],
                ['commenter' => 'Bob', 'likes' => 3],
                ['commenter' => 'Charlie', 'likes' => 7],
            ],
        ], $apiResponse->toArray());
    }
}
