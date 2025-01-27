<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version[DATE] extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Supprimer la colonne camion_id de la table lot
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291B1B2D4C32');  // Nom de la contrainte FK
        $this->addSql('ALTER TABLE lot DROP camion_id');
    }

    public function down(Schema $schema): void
    {
        // Recréer la colonne si besoin de revenir en arrière
        $this->addSql('ALTER TABLE lot ADD camion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B1B2D4C32 FOREIGN KEY (camion_id) REFERENCES camion (id)');
    }
} 