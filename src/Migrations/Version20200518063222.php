<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518063222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE comment ADD id_news_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C6B39F0D0 FOREIGN KEY (id_news_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526C6B39F0D0 ON comment (id_news_id)');
        $this->addSql('ALTER TABLE news ADD class VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE news ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE news ALTER count_views DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news DROP class');
        $this->addSql('CREATE SEQUENCE news_id_seq');
        $this->addSql('SELECT setval(\'news_id_seq\', (SELECT MAX(id) FROM news))');
        $this->addSql('ALTER TABLE news ALTER id SET DEFAULT nextval(\'news_id_seq\')');
        $this->addSql('ALTER TABLE news ALTER count_views SET DEFAULT 0');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C6B39F0D0');
        $this->addSql('DROP INDEX IDX_9474526C6B39F0D0');
        $this->addSql('ALTER TABLE comment DROP id_news_id');
        $this->addSql('CREATE SEQUENCE comment_id_seq');
        $this->addSql('SELECT setval(\'comment_id_seq\', (SELECT MAX(id) FROM comment))');
        $this->addSql('ALTER TABLE comment ALTER id SET DEFAULT nextval(\'comment_id_seq\')');
    }
}
