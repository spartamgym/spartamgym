<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220030000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea tabla de rutinas generadas por IA por usuario con soporte de PDF.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('CREATE TABLE rutina_generada (
            id INT AUTO_INCREMENT NOT NULL,
            usuario_id INT NOT NULL,
            usuario_nombre VARCHAR(255) DEFAULT NULL,
            usuario_cedula VARCHAR(64) NOT NULL,
            payload_json LONGTEXT NOT NULL,
            contenido_texto LONGTEXT NOT NULL,
            contenido_html LONGTEXT NOT NULL,
            response_hash VARCHAR(64) DEFAULT NULL,
            pdf_path VARCHAR(500) DEFAULT NULL,
            pdf_filename VARCHAR(255) DEFAULT NULL,
            created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
            INDEX IDX_RUTINA_CEDULA (usuario_cedula),
            INDEX IDX_RUTINA_CREATED (created_at),
            INDEX IDX_RUTINA_USUARIO (usuario_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rutina_generada ADD CONSTRAINT FK_RUTINA_USUARIO FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('DROP TABLE IF EXISTS rutina_generada');
    }
}
