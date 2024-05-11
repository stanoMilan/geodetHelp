<?php

namespace App\Model\Point;

class XyData
{

        public  string $point;
        public  float $x;
        public  float $y;

    /**
     * @param string $point
     * @param float $x
     * @param float $y
     */
    public function __construct(string $point, float $x, float $y)
    {
        $this->point = $point;
        $this->x = $x;
        $this->y = $y;
    }


}