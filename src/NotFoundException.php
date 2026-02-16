<?php
declare(strict_types=1);

namespace MathieuTu\Exporter;

class NotFoundException extends \RuntimeException
{
    public function __construct(string|int $segment, mixed $target)
    {
        parent::__construct("$segment can't be found in " . json_encode($target));
    }
}
