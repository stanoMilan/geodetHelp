<?php

namespace App\Model\Point;

use App\Model\GeoEtap\GeoEtapInfoData;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function PHPUnit\Framework\stringContains;

class PointFacade
{
    public const POSITION_FILE_NAME = 'protokol_poloha';
    public const HEIGHT_FILE_NAME = 'protokol_vyska';
    public const BALANCED_POSITION_FILE_NAME = 'vyrovnane_poloha';
    public const BALANCED_HEIGHT_FILE_NAME = 'vyrovnane_vyska';

    /**
     * @param UploadedFile[] $files
     * @return GeoEtapInfoData[]
     */
    public function getGeoEtapInfoData(array $files): array
    {
        $hData = null;
        $xyData = null;
        $vyrovnanePolohaData = null;
        $vyrovnaneVyskaData = null;
        foreach ($files as $file) {
            if (str_contains($file->getClientOriginalName(), self::POSITION_FILE_NAME)) {
                $xyData = file_get_contents($file->getPathname());
            } elseif (str_contains($file->getClientOriginalName(), self::HEIGHT_FILE_NAME)) {
                $hData = file_get_contents($file->getPathname());
            } elseif (str_contains($file->getClientOriginalName(), self::BALANCED_POSITION_FILE_NAME)) {
                $vyrovnanePolohaData = file_get_contents($file->getPathname());
            } elseif (str_contains($file->getClientOriginalName(), self::BALANCED_HEIGHT_FILE_NAME)) {
                $vyrovnaneVyskaData = file_get_contents($file->getPathname());
            }
        }
        $points = $this->loadHeightDataFromFile($hData);
        $points = $this->loadPositionDataFromFile( $xyData, $points);
        $rows = explode("\n", $vyrovnanePolohaData);

        foreach ($rows as $row) {
            $columns = preg_split('/\s+/', $row, -1, PREG_SPLIT_NO_EMPTY);
            if (count($columns) != 3) {
                continue;
            }
          $xyData = new XyData(
                $columns[0],
              (float)$columns[2],
                (float)$columns[1],
           );
            $points[$columns[0]]['XyData'] = $xyData;
        }

        $rows = explode("\n", $vyrovnaneVyskaData);

        foreach ($rows as $row) {
            $columns = preg_split('/\s+/', $row, -1, PREG_SPLIT_NO_EMPTY);
           if (count($columns) ===4) {
               $points[$columns[0]]['hData'] = $columns[3];
           }
        }

        $etapInfoData = [];
        foreach ($points as $pointName => $data) {
            $x = array_key_exists('x', $data) ? $data['x'] : null;
            $y = array_key_exists('y', $data) ? $data['y'] : null;
            $h = array_key_exists('h', $data) ? $data['h'] : null;
            $vyrovananeXyData = array_key_exists('XyData', $data) ? $data['XyData'] : null;
            $vyrovananeHData = array_key_exists('hData', $data) ? $data['hData'] : null;
            $etapInfoData[$pointName] = new GeoEtapInfoData(
                $pointName,
                $x,
                $y,
                $h,
                $vyrovananeXyData,
                $vyrovananeHData
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