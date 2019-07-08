<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190702142214 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients ADD adresse_id INT DEFAULT NULL, DROP adresse');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E744DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C82E744DE7DC5C ON clients (adresse_id)');
        $this->addSql('ALTER TABLE demandes ADD client_id INT DEFAULT NULL, DROP nom_client, DROP prenom_client, DROP mail_client, DROP telephone_client, DROP adresse, DROP ville, DROP code_postal');
        $this->addSql('ALTER TABLE demandes ADD CONSTRAINT FK_BD940CBB19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BD940CBB19EB6921 ON demandes (client_id)');
        $this->addSql('ALTER TABLE magasins ADD adresse_id INT DEFAULT NULL, DROP adresse, DROP ville, DROP code_postal');
        $this->addSql('ALTER TABLE magasins ADD CONSTRAINT FK_BE50D53F4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES adresses (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BE50D53F4DE7DC5C ON magasins (adresse_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E744DE7DC5C');
        $this->addSql('DROP INDEX UNIQ_C82E744DE7DC5C ON clients');
        $this->addSql('ALTER TABLE clients ADD adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP adresse_id');
        $this->addSql('ALTER TABLE demandes DROP FOREIGN KEY FK_BD940CBB19EB6921');
        $this->addSql('DROP INDEX UNIQ_BD940CBB19EB6921 ON demandes');
        $this->addSql('ALTER TABLE demandes ADD nom_client VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD prenom_client VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD mail_client VARCHAR(127) NOT NULL COLLATE utf8mb4_unicode_ci, ADD telephone_client BIGINT NOT NULL, ADD adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD ville VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD code_postal BIGINT NOT NULL, DROP client_id');
        $this->addSql('ALTER TABLE magasins DROP FOREIGN KEY FK_BE50D53F4DE7DC5C');
        $this->addSql('DROP INDEX UNIQ_BE50D53F4DE7DC5C ON magasins');
        $this->addSql('ALTER TABLE magasins ADD adresse VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD ville VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD code_postal VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci, DROP adresse_id');
    }
}
