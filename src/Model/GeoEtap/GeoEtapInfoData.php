<?php

namespace App\Model\GeoEtap;

use App\Model\Point\PointData;
use App\Model\Point\XyData;

class GeoEtapInfoData
{
    public string $point;
    public ?PointData $x;
    public ?PointData $y;
    public ?PointData $h;
    public ?XyData $XyData;
    public ?float $hData;

    /**
     * @param string $point
     * @param \App\Model\Point\PointData|null $x
     * @param \App\Model\Point\PointData|null $y
     * @param \App\Model\Point\PointData|null $h
     * @param \App\Model\Point\XyData|null $XyData
     * @param float|null $hData
     */
    public function __construct(string $point, ?PointData $x, ?PointData $y, ?PointData $h, ?XyData $XyData, ?float $hData)
    {
        $this->point = $point;
        $this->x = $x;
        $this->y = $y;
        $this->h = $h;
        $this->XyData = $XyData;
        $this->hData = $hData;
    }


}