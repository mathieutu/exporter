<?php

namespace MathieuTu\Exporter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExporterService
{
    protected const WILDCARD = '*';

    protected $exportable;

    public function __construct($exportable)
    {
        $this->exportable = $exportable;
    }

    /**
     * @param mixed $exportable
     * @param mixed $attributes
     * @return Collection|mixed
     */
    public static function exportFrom($exportable, $attributes)
    {
        return (new self($exportable))->export($attributes);
    }

    /**
     * @param array|int|string $attributes
     * @return Collection|mixed
     */
    public function export($attributes)
    {
        $this->validateAttributesTypes($attributes);

        if (!is_array($attributes) || $this->hasWildcard($attributes)) {
            return $this->exportDirectlyNestedByWrapping($attributes);
        }

        return $this->collect($attributes)
            ->mapWithKeys(function ($attribute, $key) {
                if (is_array($attribute)) {
                    return $this->exportArray($key, $attribute);
                }

                if (!is_int($key)) {
                    return $this->exportNestedAttribute($key, $attribute);
                }

                return $this->exportAttribute($attribute);
            });
    }

    private function validateAttributesTypes($attributes): void
    {
        if (!is_array($attributes) && !is_string($attributes) && !is_int($attributes)) {
            $type = gettype($attributes);

            throw new \InvalidArgumentException("Exporter only accept array, string or int attribute. '{$type}' passed.");
        }
    }

    protected function hasWildcard(array $array): bool
    {
        return array_keys($array) === [self::WILDCARD];
    }

    private function exportDirectlyNestedByWrapping($attributes)
    {
        $key = '***ExporterWrapperKey***';

        return self::exportFrom([$key => $this->exportable], [$key => $attributes])[$key];
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
            if ($segment === self::WILDCARD) {
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

    protected function getAttributeValueWithWildcard($target, $attribute): ?array
    {
        if (!is_iterable($target)) {
            return null;
        }

        $result = Arr::pluck($target, $attribute);

        return in_array(self::WILDCARD, $attribute) ? Arr::collapse($result) : $result;
    }

    protected function getNewTarget($target, $segment)
    {
        if (Arr::accessible($target) && Arr::exists($target, $segment)) {
            return $target[$segment];
        }

        if (is_object($target) && isset($target->{$segment})) {
            return $target->{$segment};
        }

        $studlySegment = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $segment)));
        $getter = "get{$studlySegment}";

        if (is_object($target) && method_exists($target, $getter)) {
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

    protected function exportAttribute(string $attribute): ?array
    {
        if ($export = $this->attributeIsAFunction($attribute)) {
            return $export;
        }

        if (preg_match('/^(.*) as (.*)$/', $attribute, $matches)) {
            return [$matches[2] => $this->getAttributeValue($matches[1])];
        }

        return [$attribute => $this->getAttributeValue($attribute)];
    }

    protected function attributeIsAFunction($attribute): ?array
    {
        if (preg_match("/(.*)\((.*)\)$/", $attribute, $matches)) {
            return [$matches[1] => call_user_func_array([$this->exportable, $matches[1]], array_map('trim', explode(',', $matches[2])))];
        }

        return null;
    }
}
