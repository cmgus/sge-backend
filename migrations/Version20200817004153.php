<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200817004153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apoderado (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, INDEX IDX_EAB782CEF5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apoderado_estudiante (apoderado_id INT NOT NULL, estudiante_id INT NOT NULL, INDEX IDX_774E8562A1C0A276 (apoderado_id), INDEX IDX_774E856259590C39 (estudiante_id), PRIMARY KEY(apoderado_id, estudiante_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curso (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE docente (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, incio DATE NOT NULL, fin DATE NOT NULL, INDEX IDX_FD9FCFA4F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estudiante (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, docente_id INT DEFAULT NULL, codigo VARCHAR(20) NOT NULL, INDEX IDX_3B3F3FADF5F88DB9 (persona_id), INDEX IDX_3B3F3FAD94E27525 (docente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluacion (id INT AUTO_INCREMENT NOT NULL, sesion_id INT DEFAULT NULL, estudiante_id INT DEFAULT NULL, calificacion VARCHAR(5) NOT NULL, fecha DATE NOT NULL, comentario VARCHAR(255) NOT NULL, INDEX IDX_DEEDCA531CCCADCB (sesion_id), INDEX IDX_DEEDCA5359590C39 (estudiante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, rol_id INT DEFAULT NULL, dni VARCHAR(11) NOT NULL, nombres VARCHAR(45) NOT NULL, apellidos VARCHAR(45) NOT NULL, direccion VARCHAR(255) NOT NULL, genero VARCHAR(1) NOT NULL, nacimiento DATE NOT NULL, INDEX IDX_51E5B69B4BAB96C (rol_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rol (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sesion_aprendizaje (id INT AUTO_INCREMENT NOT NULL, curso_id INT DEFAULT NULL, docente_id INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, descripcion VARCHAR(255) NOT NULL, INDEX IDX_733151FF87CB4A1F (curso_id), INDEX IDX_733151FF94E27525 (docente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE telefono (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, numero VARCHAR(15) NOT NULL, INDEX IDX_C1E70A7FF5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, email VARCHAR(45) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2265B05DF5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apoderado ADD CONSTRAINT FK_EAB782CEF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE apoderado_estudiante ADD CONSTRAINT FK_774E8562A1C0A276 FOREIGN KEY (apoderado_id) REFERENCES apoderado (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE apoderado_estudiante ADD CONSTRAINT FK_774E856259590C39 FOREIGN KEY (estudiante_id) REFERENCES estudiante (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE docente ADD CONSTRAINT FK_FD9FCFA4F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE estudiante ADD CONSTRAINT FK_3B3F3FADF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE estudiante ADD CONSTRAINT FK_3B3F3FAD94E27525 FOREIGN KEY (docente_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE evaluacion ADD CONSTRAINT FK_DEEDCA531CCCADCB FOREIGN KEY (sesion_id) REFERENCES sesion_aprendizaje (id)');
        $this->addSql('ALTER TABLE evaluacion ADD CONSTRAINT FK_DEEDCA5359590C39 FOREIGN KEY (estudiante_id) REFERENCES estudiante (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B4BAB96C FOREIGN KEY (rol_id) REFERENCES rol (id)');
        $this->addSql('ALTER TABLE sesion_aprendizaje ADD CONSTRAINT FK_733151FF87CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE sesion_aprendizaje ADD CONSTRAINT FK_733151FF94E27525 FOREIGN KEY (docente_id) REFERENCES docente (id)');
        $this->addSql('ALTER TABLE telefono ADD CONSTRAINT FK_C1E70A7FF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apoderado_estudiante DROP FOREIGN KEY FK_774E8562A1C0A276');
        $this->addSql('ALTER TABLE sesion_aprendizaje DROP FOREIGN KEY FK_733151FF87CB4A1F');
        $this->addSql('ALTER TABLE estudiante DROP FOREIGN KEY FK_3B3F3FAD94E27525');
        $this->addSql('ALTER TABLE sesion_aprendizaje DROP FOREIGN KEY FK_733151FF94E27525');
        $this->addSql('ALTER TABLE apoderado_estudiante DROP FOREIGN KEY FK_774E856259590C39');
        $this->addSql('ALTER TABLE evaluacion DROP FOREIGN KEY FK_DEEDCA5359590C39');
        $this->addSql('ALTER TABLE apoderado DROP FOREIGN KEY FK_EAB782CEF5F88DB9');
        $this->addSql('ALTER TABLE docente DROP FOREIGN KEY FK_FD9FCFA4F5F88DB9');
        $this->addSql('ALTER TABLE estudiante DROP FOREIGN KEY FK_3B3F3FADF5F88DB9');
        $this->addSql('ALTER TABLE telefono DROP FOREIGN KEY FK_C1E70A7FF5F88DB9');
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DF5F88DB9');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B4BAB96C');
        $this->addSql('ALTER TABLE evaluacion DROP FOREIGN KEY FK_DEEDCA531CCCADCB');
        $this->addSql('DROP TABLE apoderado');
        $this->addSql('DROP TABLE apoderado_estudiante');
        $this->addSql('DROP TABLE curso');
        $this->addSql('DROP TABLE docente');
        $this->addSql('DROP TABLE estudiante');
        $this->addSql('DROP TABLE evaluacion');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE rol');
        $this->addSql('DROP TABLE sesion_aprendizaje');
        $this->addSql('DROP TABLE telefono');
        $this->addSql('DROP TABLE usuario');
    }
}
