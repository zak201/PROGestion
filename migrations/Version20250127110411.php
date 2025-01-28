<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127110411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration pour ajuster la base de données sans supprimer la table lot';
    }

    public function up(Schema $schema): void
    {
        // Vérifie et ajuste les modifications nécessaires sans supprimer la table lot
    }

    public function down(Schema $schema): void
    {
        // Assure-toi que la méthode down ne tente pas de supprimer la table lot
    }
}