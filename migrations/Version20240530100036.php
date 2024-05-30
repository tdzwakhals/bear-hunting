<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240530100036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create pivot table for bear user relation.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE bear_user (
                `bear_id` INT NOT NULL,
                `user_id` INT NOT NULL,
                
                INDEX IDX_7128A98725183693 (`bear_id`),
                INDEX IDX_7128A987A76ED395 (`user_id`),
                PRIMARY KEY(`bear_id`, `user_id`)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;'
        );
        $this->addSql(
            'ALTER TABLE bear_user ADD CONSTRAINT FK_7128A98725183693
                FOREIGN KEY (`bear_id`) REFERENCES bear (`id`) ON DELETE CASCADE;'
        );
        $this->addSql(
            'ALTER TABLE bear_user ADD CONSTRAINT FK_7128A987A76ED395
                FOREIGN KEY (`user_id`) REFERENCES user (`id`) ON DELETE CASCADE;'
        );

        $this->addSql("UPDATE user SET `roles` = '[\"ROLE_HUNTER\"]' WHERE `id` = 1;");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE bear_user DROP FOREIGN KEY FK_7128A98725183693;');
        $this->addSql('ALTER TABLE bear_user DROP FOREIGN KEY FK_7128A987A76ED395;');
        $this->addSql('DROP TABLE bear_user;');

        $this->addSql("UPDATE user SET `roles` = '[]' WHERE `id` = 1;");
    }
}
