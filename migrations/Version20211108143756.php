<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211108143756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conge ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE conge ADD CONSTRAINT FK_2ED89348A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_2ED89348A76ED395 ON conge (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conge DROP FOREIGN KEY FK_2ED89348A76ED395');
        $this->addSql('DROP INDEX IDX_2ED89348A76ED395 ON conge');
        $this->addSql('ALTER TABLE conge DROP user_id');
    }
}
