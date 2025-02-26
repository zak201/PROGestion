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
<<<<<<< HEAD
use App\Repository\AvarieRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/avaries')]

=======
use Doctrine\ORM\EntityManagerInterface;

#[Route('/avaries')]
// #[IsGranted('ROLE_USER')]
>>>>>>> a41ffa60622e7aed453f3d4e9d5deadd3dd2711b
class AvarieController extends AbstractController
{
    public function __construct(
        private AvarieService $avarieService,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {}

<<<<<<< HEAD
    #[Route('/avarie', name: 'app_avarie')]
    public function index(AvarieRepository $avarieRepository): Response
    {
        return $this->render('avarie/index.html.twig', [
            'avaries' => $avarieRepository->findAll(),
        ]);
=======
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
>>>>>>> a41ffa60622e7aed453f3d4e9d5deadd3dd2711b
    }

    #[Route('/new', name: 'app_avarie_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
<<<<<<< HEAD
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
=======
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
>>>>>>> a41ffa60622e7aed453f3d4e9d5deadd3dd2711b
        ]);
    }
}
