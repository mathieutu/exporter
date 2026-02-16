<?php
declare(strict_types=1);

namespace MathieuTu\Exporter;

use Illuminate\Support\Collection;

trait Exporter
{
    public function export(array $attributes): Collection
    {
        return ExporterService::exportFrom($this, $attributes);
    }
}
