<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200817135644 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recurso (id INT AUTO_INCREMENT NOT NULL, uri VARCHAR(24) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rol_recurso (rol_id INT NOT NULL, recurso_id INT NOT NULL, INDEX IDX_F4A37EE34BAB96C (rol_id), INDEX IDX_F4A37EE3E52B6C4E (recurso_id), PRIMARY KEY(rol_id, recurso_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rol_recurso ADD CONSTRAINT FK_F4A37EE34BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rol_recurso ADD CONSTRAINT FK_F4A37EE3E52B6C4E FOREIGN KEY (recurso_id) REFERENCES recurso (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rol_recurso DROP FOREIGN KEY FK_F4A37EE3E52B6C4E');
        $this->addSql('DROP TABLE recurso');
        $this->addSql('DROP TABLE rol_recurso');
    }
}
