<?php

namespace App\Model\Position;

class PointPositionData
{
    public function __construct(
        public string $point,
        public float $x,
        public float $y,
        public float $xCorrection,
        public float $yCorrection,
        public float $xAdjusted,
        public float $yAdjusted,
        public float $stDevX,
        public float $stDevY
    ) {
    }
}