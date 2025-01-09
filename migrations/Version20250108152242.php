<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108152242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE denuncia ADD estadistica_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE denuncia ADD CONSTRAINT FK_F4236796912A554F FOREIGN KEY (estadistica_id) REFERENCES estadistica (id)');
        $this->addSql('CREATE INDEX IDX_F4236796912A554F ON denuncia (estadistica_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE denuncia DROP FOREIGN KEY FK_F4236796912A554F');
        $this->addSql('DROP INDEX IDX_F4236796912A554F ON denuncia');
        $this->addSql('ALTER TABLE denuncia DROP estadistica_id');
    }
}
