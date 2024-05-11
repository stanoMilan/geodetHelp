<?php

namespace App\Controller;

use App\Form\PointsFilesDataFormType;
use App\Model\Point\PointFacade;
use App\Model\Xlsx\XlsxWriterFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{

    /**
     * @var \App\Model\Point\PointFacade
     */
    private PointFacade $pointFacade;
    /**
     * @var \App\Model\Xlsx\XlsxWriterFacade
     */
    private XlsxWriterFacade $xlsxWriterFacade;

    public function __construct(
     PointFacade $pointFacade,
     XlsxWriterFacade $xlsxWriterFacade)
    {
        $this->pointFacade = $pointFacade;
        $this->xlsxWriterFacade = $xlsxWriterFacade;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request): Response
    {
        $form = $this->createForm(PointsFilesDataFormType::class);
        $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $geoInfoData = $this->pointFacade->getGeoEtapInfoData($form->get('files')->getData());
        $writer = $this->xlsxWriterFacade->createXlsxDataFromGeoData($geoInfoData);
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
