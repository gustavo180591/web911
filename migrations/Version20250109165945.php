<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109165945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evidencia (id INT AUTO_INCREMENT NOT NULL, denuncia_id INT NOT NULL, tipo VARCHAR(50) NOT NULL, archivo VARCHAR(255) NOT NULL, INDEX IDX_59B9807C17048D94 (denuncia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evidencia ADD CONSTRAINT FK_59B9807C17048D94 FOREIGN KEY (denuncia_id) REFERENCES denuncia (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D7F8F253B ON usuario (dni)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evidencia DROP FOREIGN KEY FK_59B9807C17048D94');
        $this->addSql('DROP TABLE evidencia');
        $this->addSql('DROP INDEX UNIQ_2265B05DE7927C74 ON usuario');
        $this->addSql('DROP INDEX UNIQ_2265B05D7F8F253B ON usuario');
    }
}
