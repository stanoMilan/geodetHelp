<?php

namespace App\Model\Point;

class PointData
{
    public function __construct(
        public readonly int $position,
        public readonly string $point,
        public readonly float $approximateValue,
        public readonly float $correction,
        public readonly float $adjustedValue,
        public readonly float $stdDevConfInterval1,
        public readonly float $stdDevConfInterval2
    )
    {
    }
}