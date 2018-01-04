<?php

namespace MathieuTu\Exporter;

use Illuminate\Support\Collection;

class ExporterService
{
    protected $exportable;

    public function __construct($exportable)
    {
        $this->exportable = $exportable;
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
        return [$key => $this->collect($this->$key)->map(function ($exportable) use ($array) {
            return (new self($exportable))->export($array['*']);
        })];
    }

    protected function exportNestedAttributes(string $key, array $array): array
    {
        return [$key => (new self($this->$key))->export($array)];
    }

    protected function exportNestedAttribute($key, $attribute): array
    {
        return [$key => $this->{"$key.$attribute"}];
    }

    protected function exportAttribute(string $attribute)
    {
        if ($export = $this->attributeIsAFunction($attribute)) {
            return $export;
        }

        return [$attribute => $this->$attribute];
    }

    protected function attributeIsAFunction($attribute)
    {
        if (preg_match("/(.*)\((.*)\)$/", $attribute, $groups)) {
            return [$groups[1] => call_user_func_array([$this->exportable, $groups[1]], array_map('trim', explode(',', $groups[2])))];
        }

        return null;
    }

    public function __get($attribute)
    {
        return data_get($this->exportable, $attribute);
    }
}
