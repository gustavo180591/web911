<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109234236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autoridad CHANGE activo activo TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE denuncia CHANGE estado estado VARCHAR(50) DEFAULT \'pendiente\' NOT NULL, CHANGE prioridad prioridad VARCHAR(20) DEFAULT \'baja\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE notificacion CHANGE fecha_hora fecha_hora DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE leida leida TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE usuario CHANGE rol rol VARCHAR(50) DEFAULT \'usuario\' NOT NULL, CHANGE verificado verificado TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario CHANGE rol rol VARCHAR(50) NOT NULL, CHANGE verificado verificado TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE denuncia CHANGE estado estado VARCHAR(50) NOT NULL, CHANGE prioridad prioridad VARCHAR(20) NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE autoridad CHANGE activo activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE notificacion CHANGE fecha_hora fecha_hora DATETIME NOT NULL, CHANGE leida leida TINYINT(1) NOT NULL');
    }
}
