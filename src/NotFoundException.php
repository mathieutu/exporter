<?php


namespace MathieuTu\Exporter;

class NotFoundException extends \RuntimeException
{
    public function __construct($segment, $target)
    {
        return parent::__construct("$segment can't be found in " . json_encode($target));
    }
}
