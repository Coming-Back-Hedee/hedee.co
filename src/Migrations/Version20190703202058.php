<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190703202058 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients CHANGE id_parrain id_parrain INT, CHANGE code_parrainage code_parrainage VARCHAR(255), CHANGE is_active is_active TINYINT(1)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients DROP roles, CHANGE nom nom VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE prenom prenom VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE numero_telephone numero_telephone BIGINT DEFAULT NULL, CHANGE id_parrain id_parrain INT DEFAULT NULL, CHANGE code_parrainage code_parrainage VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE is_active is_active TINYINT(1) DEFAULT NULL');
    }
}
