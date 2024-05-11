<?php

namespace App\Model\Point;

class PointData
{
        public  int $position;
        public  string $point;
        public  float $approximateValue;
        public  float $correction;
        public  float $adjustedValue;
        public  float $stdDevConfInterval2;

    /**
     * @param int $position
     * @param string $point
     * @param float $approximateValue
     * @param float $correction
     * @param float $adjustedValue
     * @param float $stdDevConfInterval2
     */
    public function __construct(int $position, string $point, float $approximateValue, float $correction, float $adjustedValue, float $stdDevConfInterval2)
    {
        $this->position = $position;
        $this->point = $point;
        $this->approximateValue = $approximateValue;
        $this->correction = $correction;
        $this->adjustedValue = $adjustedValue;
        $this->stdDevConfInterval2 = $stdDevConfInterval2;
    }


}