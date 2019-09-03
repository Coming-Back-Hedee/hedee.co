<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903130632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE alerte_prix (id INT AUTO_INCREMENT NOT NULL, dossier_id INT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, enseigne VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL, difference_prix DOUBLE PRECISION DEFAULT NULL, cloture_r TINYINT(1) DEFAULT NULL, cloture_nr TINYINT(1) DEFAULT NULL, montant_cloture DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E5714965611C0C56 (dossier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode_versement (id INT AUTO_INCREMENT NOT NULL, swift_bic VARCHAR(255) NOT NULL, iban VARCHAR(255) NOT NULL, proprietaire VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alerte_prix ADD CONSTRAINT FK_E5714965611C0C56 FOREIGN KEY (dossier_id) REFERENCES demandes (id)');
        $this->addSql('ALTER TABLE clients ADD mode_versement_id INT DEFAULT NULL, ADD date_naissance DATE DEFAULT NULL, ADD photo VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E7451B38791 FOREIGN KEY (mode_versement_id) REFERENCES mode_versement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C82E7451B38791 ON clients (mode_versement_id)');
        $this->addSql('ALTER TABLE corresp_cpville ADD ville VARCHAR(255) NOT NULL, DROP villes');
        $this->addSql('ALTER TABLE demandes ADD cgu TINYINT(1) NOT NULL, ADD hidden TINYINT(1) NOT NULL, ADD montant_remboursement DOUBLE PRECISION DEFAULT NULL, DROP code_postal, CHANGE client_id client_id INT DEFAULT NULL, CHANGE numero_dossier numero_dossier VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E7451B38791');
        $this->addSql('DROP TABLE alerte_prix');
        $this->addSql('DROP TABLE mode_versement');
        $this->addSql('DROP INDEX UNIQ_C82E7451B38791 ON clients');
        $this->addSql('ALTER TABLE clients DROP mode_versement_id, DROP date_naissance, DROP photo, CHANGE password password VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE corresp_cpville ADD villes LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', DROP ville');
        $this->addSql('ALTER TABLE demandes ADD code_postal VARCHAR(5) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP cgu, DROP hidden, DROP montant_remboursement, CHANGE client_id client_id INT NOT NULL, CHANGE numero_dossier numero_dossier INT DEFAULT NULL');
    }
}
