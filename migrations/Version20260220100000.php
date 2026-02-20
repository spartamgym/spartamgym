<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Agrega soporte de planes compartidos y control de venta no duplicada en reportes.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('ALTER TABLE plan ADD max_beneficiarios INT NOT NULL DEFAULT 1');
        $this->addSql("UPDATE plan SET max_beneficiarios = 2 WHERE UPPER(nombre) LIKE '%2X1%'");
        $this->addSql("UPDATE plan SET max_beneficiarios = 3 WHERE UPPER(nombre) LIKE '%3X1%'");
        $this->addSql('UPDATE plan SET max_beneficiarios = 1 WHERE max_beneficiarios < 1');

        $this->addSql('ALTER TABLE plan_usuario
            ADD contabiliza_ingreso TINYINT(1) NOT NULL DEFAULT 1,
            ADD grupo_compartido VARCHAR(64) DEFAULT NULL,
            ADD orden_beneficiario INT NOT NULL DEFAULT 1,
            ADD total_beneficiarios_grupo INT NOT NULL DEFAULT 1');
        $this->addSql('UPDATE plan_usuario SET
            contabiliza_ingreso = 1,
            orden_beneficiario = 1,
            total_beneficiarios_grupo = 1');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('ALTER TABLE plan_usuario
            DROP COLUMN contabiliza_ingreso,
            DROP COLUMN grupo_compartido,
            DROP COLUMN orden_beneficiario,
            DROP COLUMN total_beneficiarios_grupo');
        $this->addSql('ALTER TABLE plan DROP COLUMN max_beneficiarios');
    }
}
