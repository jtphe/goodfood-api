<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629111043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE countain_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE country_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_product_and_menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE propose_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE restaurant_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supplier_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE supply_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, users_id INT NOT NULL, restaurant_id INT NOT NULL, description VARCHAR(255) NOT NULL, rating INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526C67B3B43D ON comment (users_id)');
        $this->addSql('CREATE INDEX IDX_9474526CB1E7706E ON comment (restaurant_id)');
        $this->addSql('CREATE TABLE countain (id INT NOT NULL, product_id INT NOT NULL, orders_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_88281B0E4584665A ON countain (product_id)');
        $this->addSql('CREATE INDEX IDX_88281B0ECFFE9AD6 ON countain (orders_id)');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, name VARCHAR(100) NOT NULL, tax DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, users_id INT NOT NULL, restaurant_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, archive BOOLEAN NOT NULL, price DOUBLE PRECISION NOT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(15) DEFAULT NULL, city VARCHAR(55) DEFAULT NULL, payment VARCHAR(255) DEFAULT NULL, type VARCHAR(30) NOT NULL, statut VARCHAR(55) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F529939867B3B43D ON "order" (users_id)');
        $this->addSql('CREATE INDEX IDX_F5299398B1E7706E ON "order" (restaurant_id)');
        $this->addSql('CREATE TABLE order_product (order_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(order_id, product_id))');
        $this->addSql('CREATE INDEX IDX_2530ADE68D9F6D38 ON order_product (order_id)');
        $this->addSql('CREATE INDEX IDX_2530ADE64584665A ON order_product (product_id)');
        $this->addSql('CREATE TABLE order_product_and_menu (id INT NOT NULL, order_id_id INT NOT NULL, product_id_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E2BC6A6FCDAEAAA ON order_product_and_menu (order_id_id)');
        $this->addSql('CREATE INDEX IDX_7E2BC6A6DE18E50B ON order_product_and_menu (product_id_id)');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE propose (id INT NOT NULL, restaurant_id INT NOT NULL, product_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, avaible BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3DF2D060B1E7706E ON propose (restaurant_id)');
        $this->addSql('CREATE INDEX IDX_3DF2D0604584665A ON propose (product_id)');
        $this->addSql('CREATE TABLE restaurant (id INT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, phone VARCHAR(15) DEFAULT NULL, address VARCHAR(75) NOT NULL, postal_code VARCHAR(15) NOT NULL, city VARCHAR(55) NOT NULL, photo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EB95123FF92F3E70 ON restaurant (country_id)');
        $this->addSql('CREATE TABLE supplier (id INT NOT NULL, restaurant_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9B2A6C7EB1E7706E ON supplier (restaurant_id)');
        $this->addSql('CREATE TABLE supply (id INT NOT NULL, restaurant_id INT NOT NULL, supplier_id INT NOT NULL, name VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D219948CB1E7706E ON supply (restaurant_id)');
        $this->addSql('CREATE INDEX IDX_D219948C2ADD6D8C ON supply (supplier_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, restaurant_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, address VARCHAR(100) DEFAULT NULL, postal_code VARCHAR(10) DEFAULT NULL, city VARCHAR(30) DEFAULT NULL, password_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE INDEX IDX_8D93D649B1E7706E ON "user" (restaurant_id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C67B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE countain ADD CONSTRAINT FK_88281B0E4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE countain ADD CONSTRAINT FK_88281B0ECFFE9AD6 FOREIGN KEY (orders_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939867B3B43D FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE68D9F6D38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product ADD CONSTRAINT FK_2530ADE64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product_and_menu ADD CONSTRAINT FK_7E2BC6A6FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product_and_menu ADD CONSTRAINT FK_7E2BC6A6DE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE propose ADD CONSTRAINT FK_3DF2D060B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE propose ADD CONSTRAINT FK_3DF2D0604584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
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
        $this->addSql('ALTER TABLE countain DROP CONSTRAINT FK_88281B0ECFFE9AD6');
        $this->addSql('ALTER TABLE order_product DROP CONSTRAINT FK_2530ADE68D9F6D38');
        $this->addSql('ALTER TABLE order_product_and_menu DROP CONSTRAINT FK_7E2BC6A6FCDAEAAA');
        $this->addSql('ALTER TABLE countain DROP CONSTRAINT FK_88281B0E4584665A');
        $this->addSql('ALTER TABLE order_product DROP CONSTRAINT FK_2530ADE64584665A');
        $this->addSql('ALTER TABLE order_product_and_menu DROP CONSTRAINT FK_7E2BC6A6DE18E50B');
        $this->addSql('ALTER TABLE propose DROP CONSTRAINT FK_3DF2D0604584665A');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CB1E7706E');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398B1E7706E');
        $this->addSql('ALTER TABLE propose DROP CONSTRAINT FK_3DF2D060B1E7706E');
        $this->addSql('ALTER TABLE supplier DROP CONSTRAINT FK_9B2A6C7EB1E7706E');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948CB1E7706E');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B1E7706E');
        $this->addSql('ALTER TABLE supply DROP CONSTRAINT FK_D219948C2ADD6D8C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C67B3B43D');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939867B3B43D');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE countain_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE country_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE order_product_and_menu_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE propose_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE restaurant_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supplier_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE supply_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE countain');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE order_product');
        $this->addSql('DROP TABLE order_product_and_menu');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE propose');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE supply');
        $this->addSql('DROP TABLE "user"');
    }
}