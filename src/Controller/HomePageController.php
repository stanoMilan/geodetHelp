<?php

namespace App\Controller;

use App\Model\Height\PointData;
use App\Model\Position\PointPositionData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{

    public function __construct()
    {
    }

    #[Route('/', name: 'app_home_page')]
    public function index(): Response
    {
    $basePath = '../examplefiles/';
    $points = $this->loadHeightDataFromFile(fileName: $basePath . 'protokol_vyska.txt');
    $points = $this->loadPositionDataFromFile(fileName: $basePath . 'protokol_poloha.txt', data: $points);

     return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
         ]
     );
    }

    /**
     * @param string $fileName
     * @return PointData[]
     */
    private function loadHeightDataFromFile(string $fileName): array
    {

        $records = file_get_contents($fileName);
        $pattern = '/\s+(\d+)\s+([^\s]+)\s+([\d.-]+)\s+([\d.-]+)\s+([\d.-]+)\s+([\d.-]+)\s+([\d.-]+)/';
        preg_match_all($pattern, $records, $matches, PREG_SET_ORDER);
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
     * @param string $fileName
     * @return PointData[]
     */
    private function loadPositionDataFromFile(string $fileName, array $data)
    {

        $inputText = file_get_contents($fileName);
        $lines = explode("\n", $inputText);
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
                $data[$currentPoint][$columns[1]] = new PointData(
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
        return $data;
    }
}
