<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901175931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "menu_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE restaurant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supplier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supply_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, user_id INT NOT NULL, restaurant_id INT NOT NULL, description VARCHAR(255) NOT NULL, rating INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526CB1E7706E ON comment (restaurant_id)');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, name VARCHAR(100) NOT NULL, tax DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "menu" (id INT NOT NULL, order_menu_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D053A933AAD4059 ON "menu" (order_menu_id)');
        $this->addSql('CREATE TABLE menu_product (menu_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(menu_id, product_id))');
        $this->addSql('CREATE INDEX IDX_5B911913CCD7E912 ON menu_product (menu_id)');
        $this->addSql('CREATE INDEX IDX_5B9119134584665A ON menu_product (product_id)');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, user_id INT NOT NULL, restaurant_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, archive BOOLEAN NOT NULL, price DOUBLE PRECISION NOT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(15) DEFAULT NULL, city VARCHAR(55) DEFAULT NULL, payment VARCHAR(255) DEFAULT NULL, type INT NOT NULL, statut INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)');
        $this->addSql('CREATE INDEX IDX_F5299398B1E7706E ON "order" (restaurant_id)');
        $this->addSql('CREATE TABLE order_product (order_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(order_id, product_id))');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, restaurant_id INT DEFAULT NULL, name VARCHAR(55) NOT NULL, description VARCHAR(200) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, stock INT NOT NULL, product_type INT NOT NULL, price DOUBLE PRECISION NOT NULL, discount INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADB1E7706E ON product (restaurant_id)');
        $this->addSql('CREATE TABLE restaurant (id INT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, phone VARCHAR(15) DEFAULT NULL, address VARCHAR(75) NOT NULL, postal_code VARCHAR(15) NOT NULL, city VARCHAR(55) NOT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB95123FF92F3E70 ON restaurant (country_id)');
        $this->addSql('CREATE TABLE supplier (id INT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(55) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, contact VARCHAR(55) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9B2A6C7EB1E7706E ON supplier (restaurant_id)');
        $this->addSql('CREATE TABLE supply (id INT NOT NULL, restaurant_id INT NOT NULL, supplier_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D219948CB1E7706E ON supply (restaurant_id)');
        $this->addSql('CREATE INDEX IDX_D219948C2ADD6D8C ON supply (supplier_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, restaurant_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, address VARCHAR(100) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, city VARCHAR(30) DEFAULT NULL, password_token VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649B1E7706E ON "user" (restaurant_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "menu" ADD CONSTRAINT FK_7D053A933AAD4059 FOREIGN KEY (order_menu_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B911913CCD7E912 FOREIGN KEY (menu_id) REFERENCES "menu" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B9119134584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT FK_EB95123FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supplier ADD CONSTRAINT FK_9B2A6C7EB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supply ADD CONSTRAINT FK_D219948CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE supply ADD CONSTRAINT FK_D219948C2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE restaurant DROP CONSTRAINT FK_EB95123FF92F3E70');
        $this->addSql('ALTER TABLE menu_product DROP CONSTRAINT FK_5B911913CCD7E912');
        $this->addSql('ALTER TABLE "menu" DROP CONSTRAINT FK_7D053A933AAD4059');
        $this->addSql('ALTER TABLE order_product DROP CONSTRAINT FK_2530ADE68D9F6D38');
        $this->addSql('ALTER TABLE menu_product DROP CONSTRAINT FK_5B9119134584665A');
        $this->addSql('ALTER TABLE order_product DROP CONSTRAINT FK_2530ADE64584665A');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CB1E7706E');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398B1E7706E');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADB1E7706E');
        $this->addSql('ALTER TABLE supplier DROP CONSTRAINT FK_9B2A6C7EB1E7706E');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948CB1E7706E');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B1E7706E');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948C2ADD6D8C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE country_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "menu_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE restaurant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supplier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supply_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE "menu"');
        $this->addSql('DROP TABLE menu_product');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE supply');
        $this->addSql('DROP TABLE "user"');
    }
}
