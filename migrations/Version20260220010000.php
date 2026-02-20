<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220010000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea catalogo de medidas estandar, asignacion snapshot por usuario y migra peso/altura a double.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('CREATE TABLE medida_estandar (
            id INT AUTO_INCREMENT NOT NULL,
            nombre VARCHAR(255) NOT NULL,
            descripcion VARCHAR(255) DEFAULT NULL,
            peso DOUBLE PRECISION DEFAULT NULL,
            cintura DOUBLE PRECISION DEFAULT NULL,
            gluteos DOUBLE PRECISION DEFAULT NULL,
            brazo DOUBLE PRECISION DEFAULT NULL,
            pecho DOUBLE PRECISION DEFAULT NULL,
            pierna DOUBLE PRECISION DEFAULT NULL,
            pantorrilla DOUBLE PRECISION DEFAULT NULL,
            altura DOUBLE PRECISION DEFAULT NULL,
            imc DOUBLE PRECISION DEFAULT NULL,
            create_at DATETIME DEFAULT NULL,
            update_at DATETIME DEFAULT NULL,
            active TINYINT(1) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE usuario_medida_estandar (
            id INT AUTO_INCREMENT NOT NULL,
            usuario_id INT NOT NULL,
            medida_estandar_id INT DEFAULT NULL,
            nombre_referencia VARCHAR(255) NOT NULL,
            peso DOUBLE PRECISION DEFAULT NULL,
            cintura DOUBLE PRECISION DEFAULT NULL,
            gluteos DOUBLE PRECISION DEFAULT NULL,
            brazo DOUBLE PRECISION DEFAULT NULL,
            pecho DOUBLE PRECISION DEFAULT NULL,
            pierna DOUBLE PRECISION DEFAULT NULL,
            pantorrilla DOUBLE PRECISION DEFAULT NULL,
            altura DOUBLE PRECISION DEFAULT NULL,
            imc DOUBLE PRECISION DEFAULT NULL,
            create_at DATETIME DEFAULT NULL,
            update_at DATETIME DEFAULT NULL,
            active TINYINT(1) DEFAULT NULL,
            INDEX IDX_UME_USUARIO (usuario_id),
            INDEX IDX_UME_ESTANDAR (medida_estandar_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE usuario_medida_estandar ADD CONSTRAINT FK_UME_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE usuario_medida_estandar ADD CONSTRAINT FK_UME_ESTANDAR FOREIGN KEY (medida_estandar_id) REFERENCES medida_estandar (id) ON DELETE SET NULL');

        $this->addSql('ALTER TABLE dato_fisico MODIFY peso DOUBLE PRECISION DEFAULT NULL, MODIFY altura DOUBLE PRECISION DEFAULT NULL');

        $this->addSql("INSERT INTO medida_estandar
            (nombre, descripcion, peso, cintura, gluteos, brazo, pecho, pierna, pantorrilla, altura, imc, create_at, update_at, active)
            VALUES
            ('Referencia Inicial General', 'Perfil de referencia general para nuevos usuarios', 65, 80, 95, 30, 90, 55, 35, 1.70, 22.5, NOW(), NOW(), 1),
            ('Referencia Mujer Fitness', 'Referencia inicial orientativa para mujeres', 58, 74, 96, 28, 86, 54, 34, 1.62, 22.1, NOW(), NOW(), 1),
            ('Referencia Hombre Fitness', 'Referencia inicial orientativa para hombres', 72, 84, 98, 33, 96, 58, 37, 1.75, 23.5, NOW(), NOW(), 1)");

        // Asigna referencia general a usuarios existentes que aun no tengan una activa.
        $this->addSql('INSERT INTO usuario_medida_estandar
            (usuario_id, medida_estandar_id, nombre_referencia, peso, cintura, gluteos, brazo, pecho, pierna, pantorrilla, altura, imc, create_at, update_at, active)
            SELECT
                u.id,
                ms.id,
                ms.nombre,
                ms.peso,
                ms.cintura,
                ms.gluteos,
                ms.brazo,
                ms.pecho,
                ms.pierna,
                ms.pantorrilla,
                ms.altura,
                ms.imc,
                NOW(),
                NOW(),
                1
            FROM usuario u
            INNER JOIN (
                SELECT id, nombre, peso, cintura, gluteos, brazo, pecho, pierna, pantorrilla, altura, imc
                FROM medida_estandar
                ORDER BY id ASC
                LIMIT 1
            ) ms
            LEFT JOIN usuario_medida_estandar ume ON ume.usuario_id = u.id AND ume.active = 1
            WHERE ume.id IS NULL');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('ALTER TABLE dato_fisico MODIFY peso INT DEFAULT NULL, MODIFY altura INT DEFAULT NULL');
        $this->addSql('DROP TABLE IF EXISTS usuario_medida_estandar');
        $this->addSql('DROP TABLE IF EXISTS medida_estandar');
    }
}
