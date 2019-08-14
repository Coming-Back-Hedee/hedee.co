<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190812084945 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients ADD mode_versement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE clients ADD CONSTRAINT FK_C82E7451B38791 FOREIGN KEY (mode_versement_id) REFERENCES mode_versement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C82E7451B38791 ON clients (mode_versement_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE clients DROP FOREIGN KEY FK_C82E7451B38791');
        $this->addSql('DROP INDEX UNIQ_C82E7451B38791 ON clients');
        $this->addSql('ALTER TABLE clients DROP mode_versement_id');
    }
}
