<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Commande pour créer un administrateur dans l'application
 * 
 * Cette commande permet de créer un nouvel utilisateur avec le rôle ROLE_ADMIN
 * Elle inclut:
 * - Validation interactive des données
 * - Vérification de la force du mot de passe
 * - Confirmation avant création
 * - Validation stricte des données
 */
#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un nouvel administrateur de manière sécurisée'
)]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher,
        ValidatorInterface $validator
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Collecte interactive des données
        $email = $io->ask('Email', null, function ($email) {
            $constraints = [
                new Assert\NotBlank(),
                new Assert\Email(['message' => 'L\'email {{ value }} n\'est pas valide.']),
            ];
            
            $errors = $this->validator->validate($email, $constraints);
            if (count($errors) > 0) {
                throw new \RuntimeException($errors[0]->getMessage());
            }

            // Vérification de l'unicité
            if ($this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $email])) {
                throw new \RuntimeException('Cet email existe déjà.');
            }

            return $email;
        });

        $password = $io->askHidden('Mot de passe', function ($password) {
            if (!$this->isPasswordStrong($password)) {
                throw new \RuntimeException(
                    'Le mot de passe doit contenir au moins 8 caractères, ' .
                    'une majuscule, une minuscule, un chiffre et un caractère spécial'
                );
            }
            return $password;
        });

        $nom = $io->ask('Nom', null, function ($nom) {
            $constraints = [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
                    'message' => 'Le nom ne peut contenir que des lettres, espaces et tirets'
                ])
            ];
            
            $errors = $this->validator->validate($nom, $constraints);
            if (count($errors) > 0) {
                throw new \RuntimeException($errors[0]->getMessage());
            }
            return $nom;
        });

        $prenom = $io->ask('Prénom', null, function ($prenom) {
            $constraints = [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 2, 'max' => 50]),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-ZÀ-ÿ\s\-]+$/',
                    'message' => 'Le prénom ne peut contenir que des lettres, espaces et tirets'
                ])
            ];
            
            $errors = $this->validator->validate($prenom, $constraints);
            if (count($errors) > 0) {
                throw new \RuntimeException($errors[0]->getMessage());
            }
            return $prenom;
        });

        // Confirmation
        $io->note('Vous allez créer un administrateur avec les informations suivantes :');
        $io->listing([
            'Email: ' . $email,
            'Nom: ' . $nom,
            'Prénom: ' . $prenom
        ]);

        if (!$io->confirm('Voulez-vous continuer ?', false)) {
            $io->warning('Opération annulée');
            return Command::SUCCESS;
        }

        try {
            $user = new Utilisateur();
            $user->setEmail($email)
                ->setNom($nom)
                ->setPrenom($prenom)
                ->setRoles(['ROLE_ADMIN', 'ROLE_USER'])
                ->setDateInscription(new \DateTimeImmutable());

            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $io->success('Administrateur créé avec succès !');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $io->error('Une erreur est survenue : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Vérifie la force du mot de passe
     * 
     * Le mot de passe doit contenir :
     * - Au moins 8 caractères
     * - Au moins une majuscule
     * - Au moins une minuscule
     * - Au moins un chiffre
     * - Au moins un caractère spécial
     */
    private function isPasswordStrong(string $password): bool
    {
        if (strlen($password) < 8) {
            return false;
        }

        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password)) {
            return false;
        }

        return true;
    }
}
