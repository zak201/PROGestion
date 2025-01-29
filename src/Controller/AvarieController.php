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

#[Route('/avaries')]
#[IsGranted('ROLE_USER')]
class AvarieController extends AbstractController
{
    public function __construct(
        private AvarieService $avarieService,
        private LoggerInterface $logger
    ) {}

    #[Route('/avarie', name: 'app_avarie_index')]
    public function index(): Response
    {
        return $this->render('avarie/index.html.twig');
    }

    #[Route('', name: 'app_avaries')]
    public function index(Request $request): Response
    {
        try {
            $page = $request->query->getInt('page', 1);
            $filters = $request->query->all('filter');

            $pagination = $this->avarieService->getPaginatedAvaries($page, $filters);
            $stats = $this->avarieService->getAvarieStats();

            return $this->render('avarie/index.html.twig', [
                'pagination' => $pagination,
                'stats' => $stats,
                'filters' => $filters
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'affichage des avaries', [
                'error' => $e->getMessage()
            ]);
            $this->addFlash('error', 'Une erreur est survenue');
            return $this->redirectToRoute('app_home');
        }
    }

    #[Route('/new', name: 'app_avarie_new')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(AvarieType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $avarie = $this->avarieService->createAvarie($form->getData());
                $this->addFlash('success', 'Avarie signalée avec succès');
                return $this->redirectToRoute('app_avarie_show', ['id' => $avarie->getId()]);
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de la création de l\'avarie', [
                    'error' => $e->getMessage(),
                    'data' => $form->getData()
                ]);
                $this->addFlash('error', 'Erreur lors du signalement de l\'avarie');
            }
        }

        return $this->render('avarie/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
