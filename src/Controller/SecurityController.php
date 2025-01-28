<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        try {
            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
            $this->logger->info('Accès à la page d\'accueil', [
                'user' => $this->getUser()->getUserIdentifier()
            ]);
            return $this->redirectToRoute('app_home');
        } catch (\Exception $e) {
            $this->logger->error('Erreur d\'accès', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        try {
            if ($this->getUser()) {
                return $this->redirectToRoute('app_home');
            }

            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            if ($error) {
                $this->logger->warning('Tentative de connexion échouée', [
                    'username' => $lastUsername,
                    'error' => $error->getMessage()
                ]);
            }

            return $this->render('security/login.html.twig', [
                'last_username' => $lastUsername,
                'error' => $error,
                'page_title' => 'Connexion'
            ]);
        } catch (AuthenticationException $e) {
            $this->logger->error('Erreur d\'authentification', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // La logique est gérée par le firewall
    }
}
