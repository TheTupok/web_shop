<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408082430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product_variants ADD CONSTRAINT FK_782839764584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AFE49D60A');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5AFE49D60A ON products');
        $this->addSql('ALTER TABLE products CHANGE preview_picture_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A3DA5256D FOREIGN KEY (image_id) REFERENCES product_image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A3DA5256D ON products (image_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A3DA5256D');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A3DA5256D ON products');
        $this->addSql('ALTER TABLE products CHANGE image_id preview_picture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AFE49D60A FOREIGN KEY (preview_picture_id) REFERENCES file (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5AFE49D60A ON products (preview_picture_id)');
        $this->addSql('ALTER TABLE product_variants DROP FOREIGN KEY FK_782839764584665A');
    }
}
