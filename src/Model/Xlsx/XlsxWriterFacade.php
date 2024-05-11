<?php

namespace App\Model\Xlsx;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxWriterFacade
{


    /**
     * @param array $geoData
     * @return Xlsx
     */
    public function createXlsxDataFromGeoData(
        array $geoData
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
            $sheet->setCellValue('B' . $rowIndex, $data->XyData === null ? null : $data->XyData->y);
            $sheet->setCellValue('C' . $rowIndex, $data->XyData === null ? null : $data->XyData->x);
            $sheet->setCellValue('D' . $rowIndex, $data->hData);
            $sheet->setCellValue('E' . $rowIndex, $data->y === null ? null : $data->y->stdDevConfInterval1);
            $sheet->setCellValue('F' . $rowIndex,  $data->x === null ? null : $data->x->stdDevConfInterval1);
            $sheet->setCellValue('G' . $rowIndex, $data->h === null ? null : $data->h->stdDevConfInterval1);
        }
        $xlsx = new Xlsx($spreadsheet);

        return $xlsx;
    }

}