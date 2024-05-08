<?php

namespace App\Controller;

use App\Form\PointsFilesDataFormType;
use App\Model\Point\PointFacade;
use App\Model\Xlsx\XlsxWriterFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/', name: 'app_home_page')]
    public function index(Request $request): Response|StreamedResponse
    {
        $form = $this->createForm(PointsFilesDataFormType::class);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $geoInfoData = $this->pointFacade->getGeoEtapInfoData(files: $form->get('files')->getData());
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

        return $this->render('file/upload.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
