<?php
declare(strict_types=1);

namespace MathieuTu\Exporter;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ExporterService
{
    protected const WILDCARD = '*';

    public static bool $strict = false;

    public function __construct(
        protected mixed $exportable
    ) {
    }

    /**
     * @return Collection|mixed
     */
    public static function exportFrom(mixed $exportable, array|int|string $attributes): mixed
    {
        return (new self($exportable))->export($attributes);
    }

    /**
     * @return Collection|mixed
     */
    public function export(int|array|string $attributes): mixed
    {
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

    protected function hasWildcard(array $array): bool
    {
        return array_keys($array) === [self::WILDCARD];
    }

    private function exportDirectlyNestedByWrapping(int|array|string $attributes)
    {
        $key = '***ExporterWrapperKey***';

        return self::exportFrom([$key => $this->exportable], [$key => $attributes])[$key];
    }

    protected function collect(mixed $items): Collection
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
        [$key, $attribute] = $this->parseAttributeName($key);

        return [
            $key => $this->collect($this->getAttributeValue($attribute))->map(function ($exportable) use ($array) {
                return self::exportFrom($exportable, $array['*']);
            })
        ];
    }

    protected function getAttributeValue(int|array|string $attributes): mixed
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
                if (self::$strict) {
                    throw $e;
                }

                return null;
            }
        }

        return $target;
    }

    protected function getAttributeValueWithWildcard(mixed $target, int|array|string $attribute): ?array
    {
        if (!is_iterable($target)) {
            return null;
        }

        $result = Arr::pluck($target, $attribute);

        return in_array(self::WILDCARD, $attribute) ? Arr::collapse($result) : $result;
    }

    protected function getNewTarget(array|object|null $target, string|int $segment): mixed
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

        [$method, $args] = $this->parseFunction($segment);
        if ($method && is_object($target) && method_exists($target, $method)) {
            return call_user_func_array([$target, $method], $args);
        }

        throw new NotFoundException($segment, $target);
    }

    protected function exportNestedAttributes(string $key, array $array): array
    {
        [$key, $attribute] = $this->parseAttributeName($key);

        return [$key => self::exportFrom($this->getAttributeValue($attribute), $array)];
    }

    protected function exportNestedAttribute(string $key, int|string $nestedAttribute): array
    {
        [$key, $attribute] = $this->parseAttributeName($key);

        return [$key => $this->getAttributeValue("$attribute.$nestedAttribute")];
    }

    protected function parseAttributeName(string $key): array
    {
        if (preg_match('/^(.*) as (.*)$/', $key, $matches)) {
            return [$matches[2], $matches[1]];
        }

        [$method] = $this->parseFunction($key);

        return [$method ?? $key, $key];
    }

    protected function exportAttribute(string $attribute): ?array
    {
        [$key, $attribute] = $this->parseAttributeName($attribute);

        return [$key => $this->getAttributeValue($attribute)];
    }

    protected function parseFunction($attribute): array
    {
        if (preg_match('/(.*)\((.*)\)$/', $attribute, $matches)) {
            return [$matches[1], array_map('trim', explode(',', $matches[2]))];
        }

        return [null, []];
    }
}
