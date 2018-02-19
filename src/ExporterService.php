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

        return [$attribute => $this->getAttributeValue($attribute)];
    }

    protected function attributeIsAFunction($attribute)
    {
        if (preg_match("/(.*)\((.*)\)$/", $attribute, $groups)) {
            return [$groups[1] => call_user_func_array([$this->exportable, $groups[1]], array_map('trim', explode(',', $groups[2])))];
        }

        return null;
    }

    protected function getAttributeValue($attribute, $default = null)
    {
        // inspired by Laravel's data_get method.
        $target = $this->exportable;

        $attribute = is_array($attribute) ? $attribute : explode('.', $attribute);

        while (($segment = array_shift($attribute)) !== null) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (!is_array($target)) {
                    return value($default);
                }

                $result = Arr::pluck($target, $attribute);

                return in_array('*', $attribute) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } elseif (is_object($target) && method_exists($target, $getter = 'get' . ucfirst($segment))) {
                $target = call_user_func([$target, $getter]);
            } else {
                return value($default);
            }
        }

        return $target;
    }
}
