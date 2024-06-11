<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240611124632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, isbn, bok_namn, forfattare, bild FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, isbn INTEGER DEFAULT NULL, bok_namn VARCHAR(255) DEFAULT NULL, forfattare VARCHAR(255) NOT NULL, bild BLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO books (id, isbn, bok_namn, forfattare, bild) SELECT id, isbn, bok_namn, forfattare, bild FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, bok_namn, isbn, forfattare, bild FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bok_namn VARCHAR(255) DEFAULT NULL, isbn INTEGER DEFAULT NULL, forfattare VARCHAR(255) DEFAULT NULL, bild VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO books (id, bok_namn, isbn, forfattare, bild) SELECT id, bok_namn, isbn, forfattare, bild FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
    }
}
