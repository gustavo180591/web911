<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109225223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autoridad ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE denuncia ADD prioridad VARCHAR(20) NOT NULL, ADD motivo_anulacion LONGTEXT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE notificacion ADD tipo VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE usuario ADD fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autoridad DROP activo');
        $this->addSql('ALTER TABLE usuario DROP fecha_registro');
        $this->addSql('ALTER TABLE denuncia DROP prioridad, DROP motivo_anulacion, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE notificacion DROP tipo');
    }
}
