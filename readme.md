# Exporter: Export the attributes you need from all your objects and arrays.

<a href="https://travis-ci.org/mathieutu/exporter"><img src="https://img.shields.io/travis/mathieutu/exporter/master.svg?style=flat-square" alt="Build Status"></img></a> 
<a href="https://scrutinizer-ci.com/g/mathieutu/exporter"><img src="https://img.shields.io/scrutinizer/g/mathieutu/exporter.svg?style=flat-square" alt="Quality Score"></img></a>
<a href="https://scrutinizer-ci.com/g/mathieutu/exporter"><img src="https://img.shields.io/scrutinizer/coverage/g/mathieutu/exporter.svg?style=flat-square" alt="Code Coverage"></img></a>
<a href="https://packagist.org/packages/mathieutu/exporter"><img src="https://poser.pugx.org/mathieutu/exporter/d/total.svg?format=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/mathieutu/exporter"><img src="https://poser.pugx.org/mathieutu/exporter/v/stable.svg?format=flat-square" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mathieutu/exporter"><img src="https://poser.pugx.org/mathieutu/exporter/license.svg?format=flat-square" alt="License"></a>

## Installation

Require this package with composer.
```bash
composer require mathieutu/exporter
```

## Use cases

Because pictures are worth thousands words..:

The Exporter let you write this:

<p align="center">
    <a href="https://raw.githubusercontent.com/mathieutu/exporter/master/assets/after.png">
        <img height=400 src="https://raw.githubusercontent.com/mathieutu/exporter/master/assets/after.png" alt="Exporter use case: After">
    </a>
</p>

instead of that:

<p align="center">
    <a href="https://raw.githubusercontent.com/mathieutu/exporter/master/assets/before.png">
        <img height=600 src="https://raw.githubusercontent.com/mathieutu/exporter/master/assets/before.png" alt="Exporter use case: Before">
    </a> 
</p>

For example, I use it a lot with Laravel Eloquent resources:

<p align="center">
    <a href="https://raw.githubusercontent.com/mathieutu/exporter/master/assets/resource.png">
        <img height=500 src="https://raw.githubusercontent.com/mathieutu/exporter/master/assets/resource.png" alt="Exporter use case: Resource">
    </a>
</p>

## Usage

Just use the `\MathieuTu\Exporter\Exporter` trait on your objects. Or use directly the `\MathieuTu\Exporter\ExporterService` on array, or if you don't want to add the trait.

You can export from arrays, objects with `ArrayAccess` interface, or any standard objects.

The response will be a [Laravel Collection](https://laravel.com/docs/master/collections) (but you absolutely don't need Laravel, **this package is totally framework agnostic**). If you don't know how to use collection, you can use it exactly like an array.

### Examples
_(You can find all this examples in package tests)_

For the examples, and to cover all the possible ways to use this package, we'll consider this object as input:

```php
$object = new class
{
    use \MathieuTu\Exporter\Exporter;

    public $foo = 'testFoo';
    public $bar = ['bar1' => 'testBar1', 'bar2' => 'testBar2', 'bar3' => 'testBar3'];
    public $baz = [
        (object) ['baz1' => 'baz1A', 'baz2' => 'baz2A', 'baz3' => 'baz3A'],
        (object) ['baz1' => 'baz1B', 'baz2' => 'baz2B', 'baz3' => 'baz3B'],
        (object) ['baz1' => 'baz1C', 'baz2' => 'baz2C', 'baz3' => 'baz3C'],
    ];

    public function testWithParam(string $param): string
    {
        return 'test' . $param;
    }

    public function test(): string
    {
        return 'test' . date("l");
    }
};
```

and a standard array as output (in comment), instead of a Collection (result from the `$collection->toArray()` method).



#### Export root attributes

```php
$object->export(['foo']); // ['foo' => testFoo]
$object->export(['foo', 'bar']); 
/* 
[
    'foo' => testFoo,
    'bar' => ['bar1' => 'testBar1', 'bar2' => 'testBar2'],
]
*/
```



#### Export from nested array/object

- ##### In an array:

```php
$object->export(['bar' => ['bar2', 'bar3']]);
/* 
[
    'bar' => [
        'bar2' => testBar2',
        'bar3' => testBar3',
    ],
]
*/
```

- ##### Only one attribute:

```php
$object->export(['bar' => 'bar1']); // ['bar' => 'testBar1']
```

- ##### With dot notation:

```php
$object->export(['bar.bar1']); // ['bar.bar1' => 'testBar1']
```

- ##### Using a wildcard to export from lists:

```php
$object->export(['baz' => ['*' => ['baz1', 'baz3']]]); 
/* 
[
    'baz' => [
        ['baz1' => 'baz1A', 'baz3' => 'baz3A'],
        ['baz1' => 'baz1B', 'baz3' => 'baz3B'],
        ['baz1' => 'baz1C', 'baz3' => 'baz3C'],
    ],
]
*/        
```



#### Export result of a function

```php
$object->export(['testWithParam(Mathieu)']); // ['test' => testMathieu]
$object->export(['test()']); // ['test' => testFriday]
```



### License

This Exporter package is an open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

### Contributing

Issues and PRs are obviously welcomed and encouraged, as well for new features than documentation.
Each piece of code added should be fully tested, but we can do that all together, so please don't be afraid by that. 

