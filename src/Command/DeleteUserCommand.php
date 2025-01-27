<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-user',
    description: 'Supprime un utilisateur par son email'
)]
class DeleteUserCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur à supprimer');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        
        $user = $this->entityManager->getRepository('App\Entity\Utilisateur')
            ->findOneBy(['email' => $email]);

        if (!$user) {
            $output->writeln('Aucun utilisateur trouvé avec cet email.');
            return Command::FAILURE;
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $output->writeln('Utilisateur supprimé avec succès !');

        return Command::SUCCESS;
    }
} 