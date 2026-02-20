<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220022000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Alinea indices de ia_solicitud_log con mapeo actual.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('DROP INDEX IDX_IA_LOG_CREATED ON ia_solicitud_log');
        $this->addSql('DROP INDEX IDX_IA_LOG_ESTADO ON ia_solicitud_log');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('CREATE INDEX IDX_IA_LOG_CREATED ON ia_solicitud_log (created_at)');
        $this->addSql('CREATE INDEX IDX_IA_LOG_ESTADO ON ia_solicitud_log (estado)');
    }
}
