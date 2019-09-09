<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190909092209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE marques (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marques_categories (marques_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_7CA22318C256483C (marques_id), INDEX IDX_7CA22318A21214B7 (categories_id), PRIMARY KEY(marques_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE marques_categories ADD CONSTRAINT FK_7CA22318C256483C FOREIGN KEY (marques_id) REFERENCES marques (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE marques_categories ADD CONSTRAINT FK_7CA22318A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE marques_categories DROP FOREIGN KEY FK_7CA22318C256483C');
        $this->addSql('DROP TABLE marques');
        $this->addSql('DROP TABLE marques_categories');
    }
}
