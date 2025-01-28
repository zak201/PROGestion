<?php

namespace App\Tests\Command;

use App\Command\CreateAdminCommand;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateAdminCommandTest extends TestCase
{
    private $entityManager;
    private $passwordHasher;
    private $validator;
    private $commandTester;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $command = new CreateAdminCommand(
            $this->entityManager,
            $this->passwordHasher,
            $this->validator
        );

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($command);
    }

    public function testExecute(): void
    {
        // Configuration des mocks
        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Utilisateur::class));

        $this->passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password');

        // Simulation des entrées utilisateur
        $this->commandTester->setInputs([
            'test@example.com', // email
            'Password123!',     // password
            'Doe',             // nom
            'John',            // prénom
            'yes'              // confirmation
        ]);

        // Exécution de la commande
        $this->commandTester->execute([]);

        // Vérifications
        $display = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Administrateur créé avec succès', $display);
    }

    public function testPasswordValidation(): void
    {
        $this->commandTester->setInputs([
            'test@example.com',
            'weak',           // Mot de passe faible
            'Password123!',   // Mot de passe correct
            'Doe',
            'John',
            'yes'
        ]);

        $this->commandTester->execute([]);
        
        $display = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Le mot de passe doit contenir', $display);
    }

    public function testEmailValidation(): void
    {
        $this->commandTester->setInputs([
            'invalid-email',    // Email invalide
            'test@example.com', // Email valide
            'Password123!',
            'Doe',
            'John',
            'yes'
        ]);

        $this->commandTester->execute([]);
        
        $display = $this->commandTester->getDisplay();
        $this->assertStringContainsString('n\'est pas valide', $display);
    }
} 