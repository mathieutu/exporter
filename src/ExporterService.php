<?php

namespace MathieuTu\Exporter;

use Tightenco\Collect\Support\Arr;
use Tightenco\Collect\Support\Collection;

class ExporterService
{
    protected $exportable;

    public function __construct($exportable)
    {
        $this->exportable = $exportable;
    }

    public static function exportFrom($exportable, array $attributes): Collection
    {
        return (new self($exportable))->export($attributes);
    }

    public function export(array $attributes): Collection
    {
        return $this->createCollection($attributes, function ($attribute, $key) {
            if (is_array($attribute)) {
                return $this->exportArray($key, $attribute);
            }

            if (!is_int($key)) {
                return $this->exportNestedAttribute($key, $attribute);
            }

            return $this->exportAttribute($attribute);
        });
    }

    protected function createCollection(array $attributes, callable $callback): Collection
    {
        return $this->collect($attributes)->mapWithKeys($callback);
    }

    protected function collect($items): Collection
    {
        return Collection::make($items);
    }

    protected function exportArray(string $key, array $attribute): array
    {
        if ($this->hasWildcard($attribute)) {
            return $this->exportWildcard($key, $attribute);
        }

        return $this->exportNestedAttributes($key, $attribute);
    }

    protected function hasWildcard(array $array): bool
    {
        return array_keys($array) === ['*'];
    }

    protected function exportWildcard(string $key, array $array): array
    {
        return [$key => $this->collect($this->getAttributeValue($key))->map(function ($exportable) use ($array) {
            return self::exportFrom($exportable, $array['*']);
        })];
    }

    protected function getAttributeValue($attributes)
    {
        $attributes = is_array($attributes) ? $attributes : explode('.', $attributes);

        $target = $this->exportable;
        while (($segment = array_shift($attributes)) !== null) {
            if ($segment === '*') {
                return $this->getAttributeValueWithWildcard($target, $attributes);
            }

            try {
                $target = $this->getNewTarget($target, $segment);
            } catch (NotFoundException $e) {
                return null;
            }
        }

        return $target;
    }

    protected function getAttributeValueWithWildcard($target, $attribute)
    {
        if (!is_iterable($target)) {
            return null;
        }

        $result = Arr::pluck($target, $attribute);

        return in_array('*', $attribute) ? Arr::collapse($result) : $result;
    }

    protected function getNewTarget($target, $segment)
    {
        if (Arr::accessible($target) && Arr::exists($target, $segment)) {
            return $target[$segment];
        }

        if (is_object($target) && isset($target->{$segment})) {
            return $target->{$segment};
        }

        if (is_object($target) && method_exists($target, $getter = 'get' . ucfirst($segment))) {
            return call_user_func([$target, $getter]);
        }

        throw new NotFoundException($segment, $target);
    }

    protected function exportNestedAttributes(string $key, array $array): array
    {
        return [$key => self::exportFrom($this->getAttributeValue($key), $array)];
    }

    protected function exportNestedAttribute($key, $attribute): array
    {
        return [$key => $this->getAttributeValue("$key.$attribute")];
    }

    protected function exportAttribute(string $attribute)
    {
        if ($export = $this->attributeIsAFunction($attribute)) {
            return $export;
        }

        if (preg_match('/^(.*) as (.*)$/', $attribute, $matches)) {
            return [$matches[2] => $this->getAttributeValue($matches[1])];
        }

        return [$attribute => $this->getAttributeValue($attribute)];
    }

    protected function attributeIsAFunction($attribute)
    {
        if (preg_match("/(.*)\((.*)\)$/", $attribute, $matches)) {
            return [$matches[1] => call_user_func_array([$this->exportable, $matches[1]], array_map('trim', explode(',', $matches[2])))];
        }

        return null;
    }
}
