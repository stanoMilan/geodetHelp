<?php

namespace App\Model\GeoEtap;

use App\Model\Point\PointData;
use App\Model\Point\XyData;

class GeoEtapInfoData
{
    public function __construct(
        public readonly string $point,
        public readonly ?PointData $x,
        public readonly ?PointData $y,
        public readonly ?PointData $h,
        public readonly ?XyData $XyData,
        public readonly ?float $hData,
    )
    {
    }

}