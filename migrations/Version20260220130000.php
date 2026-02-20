<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Elimina tabla manual medida_estandar y deja referencias solo por algoritmo en usuario_medida_estandar.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('ALTER TABLE usuario_medida_estandar DROP FOREIGN KEY FK_UME_ESTANDAR');
        $this->addSql('ALTER TABLE usuario_medida_estandar DROP INDEX IDX_ABFBC8761C8C404D');
        $this->addSql('ALTER TABLE usuario_medida_estandar DROP COLUMN medida_estandar_id');
        $this->addSql('DROP TABLE medida_estandar');
    }

    public function down(Schema $schema): void
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

        $this->addSql('ALTER TABLE usuario_medida_estandar ADD medida_estandar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario_medida_estandar ADD INDEX IDX_ABFBC8761C8C404D (medida_estandar_id)');
        $this->addSql('ALTER TABLE usuario_medida_estandar ADD CONSTRAINT FK_UME_ESTANDAR FOREIGN KEY (medida_estandar_id) REFERENCES medida_estandar (id) ON DELETE SET NULL');
    }
}
