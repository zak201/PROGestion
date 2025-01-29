<?php

namespace App\Command;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un nouvel utilisateur administrateur'
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe de l\'utilisateur')
            ->addArgument('nom', InputArgument::REQUIRED, 'Nom de l\'utilisateur')
            ->addArgument('prenom', InputArgument::REQUIRED, 'Prénom de l\'utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = new Utilisateur();
        $user->setEmail($input->getArgument('email'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setNom($input->getArgument('nom'));
        $user->setPrenom($input->getArgument('prenom'));
        $dateInscription = new \DateTimeImmutable();
        $user->setDateInscription($dateInscription);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $input->getArgument('password')
        );
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur administrateur créé avec succès !');

        return Command::SUCCESS;
    }
} 