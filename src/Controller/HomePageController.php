<?php

namespace App\Controller;

use App\Model\Point\PointFacade;
use App\Model\Xlsx\XlsxWriterFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{

    public function __construct(
      private readonly PointFacade $pointFacade,
     private readonly XlsxWriterFacade $xlsxWriterFacade,
    )
    {
    }

    #[Route('/test', name: 'app_home_page')]
    public function index(): Response
    {
    $basePath = '../examplefiles/';
    $geoInfoData = $this->pointFacade->getGeoEtapInfoData(
        hData: file_get_contents($basePath . 'protokol_vyska.txt'),
        xyData: file_get_contents($basePath . 'protokol_poloha.txt'),
        vyrovnanePolohaData: file_get_contents($basePath . 'vyrovnane_poloha.txt'),
    );
    $writer = $this->xlsxWriterFacade->createXlsxDataFromGeoData(geoData: $geoInfoData);


    $response =  new StreamedResponse(
        function () use ($writer) {
            $writer->save('php://output');
        }
    );
    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
    $response->headers->set('Content-Disposition', 'attachment;filename='. microtime() .'.xlsx');
    $response->headers->set('Cache-Control','max-age=0');

    return $response;


    }
}
