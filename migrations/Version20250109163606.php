<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109163606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE denuncia (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, categoria VARCHAR(100) NOT NULL, descripcion LONGTEXT NOT NULL, ubicacion VARCHAR(255) NOT NULL, fecha_hora DATETIME NOT NULL, numero_caso VARCHAR(50) NOT NULL, estado VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_F4236796CDED01B3 (numero_caso), INDEX IDX_F4236796DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE denuncia ADD CONSTRAINT FK_F4236796DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE denuncia DROP FOREIGN KEY FK_F4236796DB38439E');
        $this->addSql('DROP TABLE denuncia');
    }
}
