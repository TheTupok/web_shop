<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240405124048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP preview_picture_id');
        $this->addSql('ALTER TABLE product ADD preview_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFE49D60A FOREIGN KEY (preview_picture_id) REFERENCES file (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADFE49D60A ON product (preview_picture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFE49D60A');
        $this->addSql('DROP INDEX UNIQ_D34A04ADFE49D60A ON product');
        $this->addSql('ALTER TABLE product DROP preview_picture_id');
        $this->addSql('ALTER TABLE product ADD preview_picture_id INT NOT NULL');
    }
}
