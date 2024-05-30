<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530085406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration adding admin role to groundskeeper.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE user SET `roles` = '[\"ROLE_ADMIN\"]' WHERE `id` = 2;");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("UPDATE user SET `roles` = '[]' WHERE `id` = 2;");
    }
}
