<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240614131914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_addon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_addon_product (product_addon_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_BBC8D4147D44CEF (product_addon_id), INDEX IDX_BBC8D4144584665A (product_id), PRIMARY KEY(product_addon_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_addon_product ADD CONSTRAINT FK_BBC8D4147D44CEF FOREIGN KEY (product_addon_id) REFERENCES product_addon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_addon_product ADD CONSTRAINT FK_BBC8D4144584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_addon_product DROP FOREIGN KEY FK_BBC8D4147D44CEF');
        $this->addSql('ALTER TABLE product_addon_product DROP FOREIGN KEY FK_BBC8D4144584665A');
        $this->addSql('DROP TABLE product_addon');
        $this->addSql('DROP TABLE product_addon_product');
    }
}
