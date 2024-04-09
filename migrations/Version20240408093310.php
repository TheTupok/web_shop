<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408093310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A3DA5256D');
        $this->addSql('CREATE TABLE product_variants_image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_12B62D14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_variants_image ADD CONSTRAINT FK_12B62D14584665A FOREIGN KEY (product_id) REFERENCES product_variants (id)');
        $this->addSql('ALTER TABLE product_image DROP FOREIGN KEY FK_64617F034584665A');
        $this->addSql('DROP TABLE product_image');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A3DA5256D');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A3DA5256D FOREIGN KEY (image_id) REFERENCES product_variants_image (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A3DA5256D');
        $this->addSql('CREATE TABLE product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_64617F034584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_image ADD CONSTRAINT FK_64617F034584665A FOREIGN KEY (product_id) REFERENCES product_variants (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product_variants_image DROP FOREIGN KEY FK_12B62D14584665A');
        $this->addSql('DROP TABLE product_variants_image');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A3DA5256D');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A3DA5256D FOREIGN KEY (image_id) REFERENCES product_image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
