<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127105834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration pour ajuster la base de données sans erreurs de syntaxe';
    }

    public function up(Schema $schema): void
    {
        // Vérifie et ajuste les modifications nécessaires
    }

    public function down(Schema $schema): void
    {
        // Assure-toi que la méthode down ne tente pas de supprimer la table lot
    }
}
