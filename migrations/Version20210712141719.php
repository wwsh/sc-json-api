<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210712141719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE song_comment (id INT AUTO_INCREMENT NOT NULL, song_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, created DATETIME NOT NULL, INDEX IDX_991F4343A0BDB2F3 (song_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE song_comment ADD CONSTRAINT FK_991F4343A0BDB2F3 FOREIGN KEY (song_id) REFERENCES song_data (id)');
        $this->addSql('ALTER TABLE song_data DROP comment');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE song_comment');
        $this->addSql('ALTER TABLE song_data ADD comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
