<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250107215649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD is_active TINYINT(1) DEFAULT 1 NOT NULL, DROP set_created_at, DROP set_is_active, CHANGE dni_front_photo dni_front_photo VARCHAR(255) DEFAULT NULL, CHANGE dni_back_photo dni_back_photo VARCHAR(255) DEFAULT NULL, CHANGE location_details location_details VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497F8F253B ON user (dni)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6497F8F253B ON user');
        $this->addSql('ALTER TABLE user ADD set_created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD set_is_active TINYINT(1) NOT NULL, DROP is_active, CHANGE dni_front_photo dni_front_photo VARCHAR(255) NOT NULL, CHANGE dni_back_photo dni_back_photo VARCHAR(255) NOT NULL, CHANGE location_details location_details VARCHAR(255) NOT NULL');
    }
}
