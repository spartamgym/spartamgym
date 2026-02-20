<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220013000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Normaliza nombres de indices de usuario_medida_estandar segun naming strategy de Doctrine.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('ALTER TABLE usuario_medida_estandar RENAME INDEX idx_ume_usuario TO IDX_ABFBC876DB38439E');
        $this->addSql('ALTER TABLE usuario_medida_estandar RENAME INDEX idx_ume_estandar TO IDX_ABFBC8761C8C404D');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('ALTER TABLE usuario_medida_estandar RENAME INDEX IDX_ABFBC876DB38439E TO idx_ume_usuario');
        $this->addSql('ALTER TABLE usuario_medida_estandar RENAME INDEX IDX_ABFBC8761C8C404D TO idx_ume_estandar');
    }
}
