<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Avarie;
use App\Form\AvarieType;
use App\Service\AvarieService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Psr\Log\LoggerInterface;
use App\Repository\AvarieRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/avaries')]

class AvarieController extends AbstractController
{
    public function __construct(
        private AvarieService $avarieService,
        private LoggerInterface $logger
    ) {}

    #[Route('/avarie', name: 'app_avarie')]
    public function index(AvarieRepository $avarieRepository): Response
    {
        return $this->render('avarie/index.html.twig', [
            'avaries' => $avarieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_avarie_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avarie = new Avarie();
        $form = $this->createForm(AvarieType::class, $avarie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avarie);
            $entityManager->flush();

            return $this->redirectToRoute('app_avarie');
        }

        return $this->render('avarie/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
