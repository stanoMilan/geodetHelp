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
    public function getGeoEtapInfoData(string $hData, string $xyData, string $vyrovnanePolohaData): array
    {
        $points = $this->loadHeightDataFromFile(data: $hData);
        $points = $this->loadPositionDataFromFile(data: $xyData, hData: $points);
        $rows = explode("\n", $vyrovnanePolohaData);

        foreach ($rows as $row) {
            $columns = preg_split('/\s+/', $row, -1, PREG_SPLIT_NO_EMPTY);
            if (count($columns) != 3) {
                continue;
            }
          $xyData = new XyData(
               point:  $columns[0],
               x:  (float)$columns[2],
               y:  (float)$columns[1],
           );
            $points[$columns[0]]['XyData'] = $xyData;
        }

        $etapInfoData = [];
        foreach ($points as $pointName => $data) {
            $x = array_key_exists('x', $data) ? $data['x'] : null;
            $y = array_key_exists('y', $data) ? $data['y'] : null;
            $h = array_key_exists('h', $data) ? $data['h'] : null;
            $vyrovananeXyData = array_key_exists('XyData', $data) ? $data['XyData'] : null;
            $etapInfoData[$pointName] = new GeoEtapInfoData(
                $pointName,
                $x,
                $y,
                $h,
                $vyrovananeXyData
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
            if (count($columns) === 1 && $columns[0] !== '') {
                $currentPoint = $columns[0];
            } elseif ( $currentPoint !== null && count($columns) >= 7)
            {
                $x = (float)$columns[2];
                $y = (float)$columns[3];
                $xCorrection = (float)$columns[4];
                $yCorrection = (float)$columns[5];
                $xAdjusted = (float)$columns[6];
                $hData[$currentPoint][$columns[1]] = new PointData(
                    (int)$columns[0],
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