<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240326000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration initiale après réinitialisation complète';
    }

    public function up(Schema $schema): void
    {
        // Cette migration est vide car le schéma est déjà à jour
    }

    public function down(Schema $schema): void
    {
        // Ne rien faire ici non plus
    }
} 