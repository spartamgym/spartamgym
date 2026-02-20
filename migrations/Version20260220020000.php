<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220020000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea bitacora de solicitudes IA para auditoria operativa.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('CREATE TABLE ia_solicitud_log (
            id INT AUTO_INCREMENT NOT NULL,
            usuario_nombre VARCHAR(255) DEFAULT NULL,
            usuario_cedula VARCHAR(255) DEFAULT NULL,
            estado VARCHAR(40) NOT NULL,
            payload_json LONGTEXT DEFAULT NULL,
            response_hash VARCHAR(64) DEFAULT NULL,
            error_mensaje LONGTEXT DEFAULT NULL,
            ip VARCHAR(45) DEFAULT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id),
            INDEX IDX_IA_LOG_CREATED (created_at),
            INDEX IDX_IA_LOG_ESTADO (estado)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('DROP TABLE IF EXISTS ia_solicitud_log');
    }
}
