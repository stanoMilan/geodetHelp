<?php

namespace App\Controller;

use App\Model\Height\PointHeightData;
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
     $pointsHeightData = $this->loadHeightDataFromFile(fileName: $basePath . 'protokol_vyska.txt');

     return $this->render('home_page/index.html.twig', [
            'controller_name' => 'HomePageController',
         ]
     );
    }

    /**
     * @param string $fileName
     * @return PointHeightData[]
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
            $pointHeightsData[] = new PointHeightData(
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
}
