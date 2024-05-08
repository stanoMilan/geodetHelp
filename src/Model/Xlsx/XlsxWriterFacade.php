<?php

namespace App\Model\Xlsx;

use App\Model\GeoEtap\GeoEtapInfoData;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxWriterFacade
{


    /**
     * @param GeoEtapInfoData[] $geoData
     * @return Xlsx
     */
    public function createXlsxDataFromGeoData(
        array $geoData,
    ): Xlsx
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $rowIndex = 1;
        $sheet->setCellValue('A' . $rowIndex, 'Číslo bodu');
        $sheet->setCellValue('B' . $rowIndex, 'y');
        $sheet->setCellValue('C' . $rowIndex, 'x');
        $sheet->setCellValue('D' . $rowIndex, 'H');
        $sheet->setCellValue('E' . $rowIndex, 'my');
        $sheet->setCellValue('F' . $rowIndex, 'mx');
        $sheet->setCellValue('G' . $rowIndex, 'mH');
        foreach ($geoData as $data) {
            $rowIndex++;
            $sheet->setCellValue('A' . $rowIndex, $data->point);
            $sheet->setCellValue('B' . $rowIndex, $data->y?->adjustedValue);
            $sheet->setCellValue('C' . $rowIndex, $data->x?->adjustedValue);
            $sheet->setCellValue('D' . $rowIndex, $data->h?->adjustedValue);
            $sheet->setCellValue('E' . $rowIndex, $data->y?->stdDevConfInterval1);
            $sheet->setCellValue('F' . $rowIndex, $data->x?->stdDevConfInterval1);
            $sheet->setCellValue('G' . $rowIndex, $data->h?->stdDevConfInterval1);
        }
        return new Xlsx($spreadsheet);
    }

}