<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190703141817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients  CHANGE email email VARCHAR(60) NOT NULL');
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_C82E74E7927C74 ON clients');
        $this->addSql('ALTER TABLE clients ADD nom_client VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD prenom_client VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, DROP nom, DROP prenom, DROP id_parrain, DROP code_parrainage, DROP is_active, CHANGE email email VARCHAR(127) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
