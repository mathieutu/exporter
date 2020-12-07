<?php

namespace MathieuTu\Exporter;

use Illuminate\Support\Collection;

trait Exporter
{
    public function export(array $attributes): Collection
    {
        return ExporterService::exportFrom($this, $attributes);
    }
}
