<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128190401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE navire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, origine VARCHAR(255) NOT NULL, date_arrivee DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lot ADD camion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B3A706D3 FOREIGN KEY (camion_id) REFERENCES camion (id)');
        $this->addSql('CREATE INDEX IDX_B81291B3A706D3 ON lot (camion_id)');
        $this->addSql('ALTER TABLE utilisateur ADD last_login DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD is_active TINYINT(1) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE date_inscription date_inscription DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vehicule ADD navire_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE numero_chassis numero_chassis VARCHAR(17) NOT NULL, CHANGE marque marque VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DD840FD82 FOREIGN KEY (navire_id) REFERENCES navire (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_292FFF1DF39D6D7E ON vehicule (numero_chassis)');
        $this->addSql('CREATE INDEX IDX_292FFF1DD840FD82 ON vehicule (navire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DD840FD82');
        $this->addSql('DROP TABLE navire');
        $this->addSql('DROP INDEX UNIQ_292FFF1DF39D6D7E ON vehicule');
        $this->addSql('DROP INDEX IDX_292FFF1DD840FD82 ON vehicule');
        $this->addSql('ALTER TABLE vehicule DROP navire_id, DROP created_at, DROP updated_at, CHANGE numero_chassis numero_chassis VARCHAR(255) NOT NULL, CHANGE marque marque VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP last_login, DROP is_active, CHANGE email email VARCHAR(255) NOT NULL, CHANGE date_inscription date_inscription DATETIME NOT NULL');
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291B3A706D3');
        $this->addSql('DROP INDEX IDX_B81291B3A706D3 ON lot');
        $this->addSql('ALTER TABLE lot DROP camion_id');
    }
}
