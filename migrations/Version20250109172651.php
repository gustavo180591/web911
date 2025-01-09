<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109172651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE denuncia_autoridad (denuncia_id INT NOT NULL, autoridad_id INT NOT NULL, INDEX IDX_4A1C0ED617048D94 (denuncia_id), INDEX IDX_4A1C0ED6D1078332 (autoridad_id), PRIMARY KEY(denuncia_id, autoridad_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE denuncia_autoridad ADD CONSTRAINT FK_4A1C0ED617048D94 FOREIGN KEY (denuncia_id) REFERENCES denuncia (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE denuncia_autoridad ADD CONSTRAINT FK_4A1C0ED6D1078332 FOREIGN KEY (autoridad_id) REFERENCES autoridad (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE denuncia_autoridad DROP FOREIGN KEY FK_4A1C0ED617048D94');
        $this->addSql('ALTER TABLE denuncia_autoridad DROP FOREIGN KEY FK_4A1C0ED6D1078332');
        $this->addSql('DROP TABLE denuncia_autoridad');
    }
}
