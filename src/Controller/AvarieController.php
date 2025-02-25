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
use Doctrine\ORM\EntityManagerInterface;

#[Route('/avaries')]
// #[IsGranted('ROLE_USER')]
class AvarieController extends AbstractController
{
    public function __construct(
        private AvarieService $avarieService,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/', name: 'app_avaries')]
    public function index(Request $request): Response
    {
        try {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->all('filter');

            $pagination = $this->avarieService->getPaginatedAvaries($page, $filters);

            return $this->render('avarie/index.html.twig', [
                'pagination' => $pagination,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'affichage des avaries', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('danger', 'Une erreur est survenue');
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/new', name: 'app_avarie_new')]
    public function new(Request $request): Response
    {
        try {
            $avarie = new Avarie();
            $form = $this->createForm(AvarieType::class, $avarie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($avarie);
                $this->entityManager->flush();

                $this->addFlash('success', 'Avarie signalée avec succès');
                return $this->redirectToRoute('app_avaries');
            }

            return $this->render('avarie/new.html.twig', [
                'form' => $form->createView(),
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de la création de l\'avarie', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('danger', 'Erreur lors de la création de l\'avarie');
            return $this->redirectToRoute('app_avaries');
        }
    }

    #[Route('/{id}', name: 'app_avarie_show', methods: ['GET'])]
    public function show(Avarie $avarie): Response
    {
        return $this->render('avarie/show.html.twig', [
            'avarie' => $avarie,
        ]);
    }
}
