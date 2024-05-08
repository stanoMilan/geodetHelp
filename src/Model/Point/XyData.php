<?php

namespace App\Model\Point;

class XyData
{
    public function __construct(
        public readonly string $point,
        public readonly float $x,
        public readonly float $y,
    )
    {
    }

}