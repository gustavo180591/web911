<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108003531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD gender VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD phone VARCHAR(255) NOT NULL, ADD dni VARCHAR(255) NOT NULL, ADD dni_back_photo VARCHAR(255) DEFAULT NULL, ADD street VARCHAR(255) NOT NULL, ADD street_number VARCHAR(255) NOT NULL, ADD location_details VARCHAR(255) DEFAULT NULL, ADD location_validated TINYINT(1) DEFAULT 0 NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD is_active TINYINT(1) DEFAULT 1 NOT NULL, CHANGE details dni_front_photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497F8F253B ON user (dni)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6497F8F253B ON user');
        $this->addSql('ALTER TABLE user ADD details VARCHAR(255) DEFAULT NULL, DROP gender, DROP email, DROP password, DROP phone, DROP dni, DROP dni_front_photo, DROP dni_back_photo, DROP street, DROP street_number, DROP location_details, DROP location_validated, DROP created_at, DROP is_active');
    }
}
