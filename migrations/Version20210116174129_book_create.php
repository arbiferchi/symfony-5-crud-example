<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210116174129_book_create extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Book create';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE book (id SERIAL NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, pub_year SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE book');
    }
}
