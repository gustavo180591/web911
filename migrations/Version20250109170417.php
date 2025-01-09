<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109170417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notificacion (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, denuncia_id INT NOT NULL, mensaje LONGTEXT NOT NULL, fecha_hora DATETIME NOT NULL, leida TINYINT(1) NOT NULL, INDEX IDX_729A19ECDB38439E (usuario_id), INDEX IDX_729A19EC17048D94 (denuncia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notificacion ADD CONSTRAINT FK_729A19ECDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE notificacion ADD CONSTRAINT FK_729A19EC17048D94 FOREIGN KEY (denuncia_id) REFERENCES denuncia (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notificacion DROP FOREIGN KEY FK_729A19ECDB38439E');
        $this->addSql('ALTER TABLE notificacion DROP FOREIGN KEY FK_729A19EC17048D94');
        $this->addSql('DROP TABLE notificacion');
    }
}
