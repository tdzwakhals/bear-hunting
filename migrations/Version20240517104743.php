<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517104743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed migration for Bears and Users';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE bear (
                id INT AUTO_INCREMENT NOT NULL,
                created DATETIME NOT NULL,
                updated DATETIME NOT NULL,
                name VARCHAR(255) NOT NULL,
                location VARCHAR(255) NOT NULL,
                province VARCHAR(255) NOT NULL,
                latitude DOUBLE PRECISION NOT NULL,
                longitude DOUBLE PRECISION NOT NULL,

                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $bears = array_map('str_getcsv', file('migrations/seed/beren_locaties.csv'));
        foreach ($bears as $bear) {
            $name = str_replace('\'', '\\\'', $bear[0]);
            $location = str_replace('\'', '\\\'', $bear[1]);
            $latitude = (float)$bear[3];
            $longitude = (float)$bear[4];
            $this->addSql(
                "INSERT INTO bear (`name`, `location`, `province`, `latitude`, `longitude`, `created`, `updated`)
                    VALUES ('$name', '$location', '$bear[2]', $latitude, $longitude, NOW(), NOW());"
            );
        }


        $this->addSql(
            'CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL,
                created DATETIME NOT NULL,
                updated DATETIME NOT NULL,
                email VARCHAR(180) NOT NULL,
                roles JSON NOT NULL COMMENT \'(DC2Type:json)\',
                password VARCHAR(255) NOT NULL,
                last_login DATETIME DEFAULT NULL,
                
                UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $password = '$2y$13$EAfPDT4o8SWem1ptXsfc2epR0y8qlXtyo4w77g7zTT82RlS1CmWfq';
        foreach (['hunter@app.dev', 'gameskeeper@app.dev'] as $email) {
            $this->addSql("INSERT INTO user (`email`, `password`, `roles`, `created`, `updated`)
                VALUES ('$email', '$password', '[]', NOW(), NOW());");
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bear');
        $this->addSql('DROP TABLE user');
    }
}
