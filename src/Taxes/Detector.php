<?php

namespace App\Taxes;

class Detector
{
    protected $threshold;

    public function __construct(int $threshold)
    {
        $this->threshold = $threshold;
    }

    public function detect(float $prix): bool
    {
        if ($prix > $this->threshold) {
            return true;
        }

        return false;
    }
}
