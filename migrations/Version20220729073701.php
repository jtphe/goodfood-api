<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729073701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE menu (id INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE menu_product (menu_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(menu_id, product_id))');
        $this->addSql('CREATE INDEX IDX_5B911913CCD7E912 ON menu_product (menu_id)');
        $this->addSql('CREATE INDEX IDX_5B9119134584665A ON menu_product (product_id)');
        $this->addSql('CREATE TABLE menu_order (menu_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(menu_id, order_id))');
        $this->addSql('CREATE INDEX IDX_6485B0FFCCD7E912 ON menu_order (menu_id)');
        $this->addSql('CREATE INDEX IDX_6485B0FF8D9F6D38 ON menu_order (order_id)');
        $this->addSql('CREATE TABLE order_product (order_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(order_id, product_id))');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B911913CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B9119134584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_order ADD CONSTRAINT FK_6485B0FFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_order ADD CONSTRAINT FK_6485B0FF8D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE menu_product DROP CONSTRAINT FK_5B911913CCD7E912');
        $this->addSql('ALTER TABLE menu_order DROP CONSTRAINT FK_6485B0FFCCD7E912');
        $this->addSql('DROP SEQUENCE menu_id_seq CASCADE');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_product');
        $this->addSql('DROP TABLE menu_order');
        $this->addSql('DROP TABLE order_product');
    }
}
