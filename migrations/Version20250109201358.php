<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109201358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reporte_estadistico_denuncia (reporte_estadistico_id INT NOT NULL, denuncia_id INT NOT NULL, INDEX IDX_E434146E86C30111 (reporte_estadistico_id), INDEX IDX_E434146E17048D94 (denuncia_id), PRIMARY KEY(reporte_estadistico_id, denuncia_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reporte_estadistico_denuncia ADD CONSTRAINT FK_E434146E86C30111 FOREIGN KEY (reporte_estadistico_id) REFERENCES reporte_estadistico (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reporte_estadistico_denuncia ADD CONSTRAINT FK_E434146E17048D94 FOREIGN KEY (denuncia_id) REFERENCES denuncia (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reporte_estadistico ADD autoridad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reporte_estadistico ADD CONSTRAINT FK_5573012DD1078332 FOREIGN KEY (autoridad_id) REFERENCES autoridad (id)');
        $this->addSql('CREATE INDEX IDX_5573012DD1078332 ON reporte_estadistico (autoridad_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reporte_estadistico_denuncia DROP FOREIGN KEY FK_E434146E86C30111');
        $this->addSql('ALTER TABLE reporte_estadistico_denuncia DROP FOREIGN KEY FK_E434146E17048D94');
        $this->addSql('DROP TABLE reporte_estadistico_denuncia');
        $this->addSql('ALTER TABLE reporte_estadistico DROP FOREIGN KEY FK_5573012DD1078332');
        $this->addSql('DROP INDEX IDX_5573012DD1078332 ON reporte_estadistico');
        $this->addSql('ALTER TABLE reporte_estadistico DROP autoridad_id');
    }
}
