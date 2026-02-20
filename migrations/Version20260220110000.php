<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220110000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Elimina tabla legacy card que ya no se usa en la operacion.';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('DROP TABLE IF EXISTS card');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on mysql.'
        );

        $this->addSql('CREATE TABLE card (
            id INT AUTO_INCREMENT NOT NULL,
            code VARCHAR(255) NOT NULL,
            usuario VARCHAR(255) NOT NULL,
            create_at DATETIME DEFAULT NULL,
            update_at DATETIME DEFAULT NULL,
            active TINYINT(1) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }
}
