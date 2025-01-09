<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108151801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario ADD denuncias_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D1004EEF5 FOREIGN KEY (denuncias_id) REFERENCES denuncia (id)');
        $this->addSql('CREATE INDEX IDX_2265B05D1004EEF5 ON usuario (denuncias_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D1004EEF5');
        $this->addSql('DROP INDEX IDX_2265B05D1004EEF5 ON usuario');
        $this->addSql('ALTER TABLE usuario DROP denuncias_id');
    }
}
