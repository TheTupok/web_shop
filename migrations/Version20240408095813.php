<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408095813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A3DA5256D');
        $this->addSql('DROP INDEX UNIQ_B3BA5A5A3DA5256D ON products');
        $this->addSql('ALTER TABLE products DROP image_id');
        $this->addSql('ALTER TABLE products_image DROP INDEX IDX_2564FA8F4584665A, ADD UNIQUE INDEX UNIQ_2564FA8F4584665A (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A3DA5256D FOREIGN KEY (image_id) REFERENCES product_variants_image (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A3DA5256D ON products (image_id)');
        $this->addSql('ALTER TABLE products_image DROP INDEX UNIQ_2564FA8F4584665A, ADD INDEX IDX_2564FA8F4584665A (product_id)');
    }
}
