<?php

namespace App\Model\Point;

use App\Model\GeoEtap\GeoEtapInfoData;

class PointFacade
{
    /**
     * @param string $hData
     * @param string $xyData
     * @return GeoEtapInfoData[]
     */
    public function getGeoEtapInfoData(string $hData, string $xyData): array
    {
        $points = $this->loadHeightDataFromFile(data: $hData);
        $points = $this->loadPositionDataFromFile(data: $xyData, hData: $points);
        $etapInfoData = [];
        foreach ($points as $pointName => $data){
            $x = $data['x'];
            $y = $data['y'];
            $h = $data['h'];
            $etapInfoData[$pointName] = new GeoEtapInfoData(
                $pointName,
                $x,
                $y,
                $h
            );
        }

        sort($etapInfoData);

        return $etapInfoData;
    }

    /**
     * @param string $data
     * @return PointData[][]
     */
    private function loadHeightDataFromFile(string $data): array
    {

        $pattern = '/\s+(\d+)\s+([^\s]+)\s+([\d.-]+)\s+([\d.-]+)\s+([\d.-]+)\s+([\d.-]+)\s+([\d.-]+)/';
        preg_match_all($pattern, $data, $matches, PREG_SET_ORDER);
        $pointHeightsData = [];
        foreach ($matches as $match) {
            // $match[1] je číslo
            // $match[2] je bod
            // $match[3] je přibližná hodnota
            // $match[4] je korekce
            // $match[5] je vyrovnaná hodnota
            // $match[6] je stř.ch. konf.i.
            // $match[7] je stř.ch. konf.i.
            $pointHeightsData[$match[2]]['h'] = new PointData(
                (int)$match[1],
                $match[2],
                (float)$match[3],
                (float)$match[4],
                (float)$match[5],
                (float)$match[6],
                (float)$match[7]
            );
        }

        return $pointHeightsData;
    }

    /**
     * @param string $data
     * @param array $hData
     * @return PointData[][]
     */
    private function loadPositionDataFromFile(string $data, array $hData)
    {

        $lines = explode("\n", $data);
        $currentPoint = null;
        foreach ($lines as $line) {
            $line = trim($line);
            $columns = preg_split('/\s+/', $line);
            if (preg_match('/^\s*([A-Z0-9-]+)\s*$/', $line, $matches)) {
                $currentPoint = $matches[1];
            } elseif ( $currentPoint !== null && count($columns) >= 7)
            {
                $x = (float)$columns[2];
                $y = (float)$columns[3];
                $xCorrection = (float)$columns[4];
                $yCorrection = (float)$columns[5];
                $xAdjusted = (float)$columns[6];
                $hData[$currentPoint][$columns[1]] = new PointData(
                    $columns[0],
                    $currentPoint,
                    $x,
                    $y,
                    $xCorrection,
                    $yCorrection,
                    $xAdjusted
                );
            }
        }
        return $hData;
    }

}