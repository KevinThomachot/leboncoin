<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200710132141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, category_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_on DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, photos VARCHAR(255) DEFAULT NULL, INDEX IDX_CB988C6FF675F31B (author_id), INDEX IDX_CB988C6F12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, update_on DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F12469DE2');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FF675F31B');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE user');
    }
}
