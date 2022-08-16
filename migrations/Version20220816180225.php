<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220816180225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE propose_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_product_and_menu_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE menu (id INT NOT NULL, order_menu_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7D053A933AAD4059 ON menu (order_menu_id)');
        $this->addSql('CREATE TABLE menu_product (menu_id INT NOT NULL, product_id INT NOT NULL, PRIMARY KEY(menu_id, product_id))');
        $this->addSql('CREATE INDEX IDX_5B911913CCD7E912 ON menu_product (menu_id)');
        $this->addSql('CREATE INDEX IDX_5B9119134584665A ON menu_product (product_id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A933AAD4059 FOREIGN KEY (order_menu_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B911913CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_product ADD CONSTRAINT FK_5B9119134584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE propose');
        $this->addSql('DROP TABLE order_product_and_menu');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT fk_9474526c67b3b43d');
        $this->addSql('DROP INDEX idx_9474526c67b3b43d');
        $this->addSql('ALTER TABLE comment ALTER rating DROP NOT NULL');
        $this->addSql('ALTER TABLE comment RENAME COLUMN users_id TO user_id');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('ALTER TABLE countain DROP CONSTRAINT fk_88281b0e4584665a');
        $this->addSql('DROP INDEX idx_88281b0e4584665a');
        $this->addSql('ALTER TABLE countain DROP product_id');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f529939867b3b43d');
        $this->addSql('DROP INDEX idx_f529939867b3b43d');
        $this->addSql('ALTER TABLE "order" ALTER type TYPE INT');
        $this->addSql('ALTER TABLE "order" ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" ALTER type TYPE INT');
        $this->addSql('ALTER TABLE "order" ALTER statut TYPE INT');
        $this->addSql('ALTER TABLE "order" ALTER statut DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" ALTER statut TYPE INT');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN users_id TO user_id');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON "order" (user_id)');
        $this->addSql('ALTER TABLE product ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD stock INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD product_type INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD discount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ALTER name TYPE VARCHAR(55)');
        $this->addSql('ALTER TABLE product ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE product ALTER description TYPE VARCHAR(200)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04ADB1E7706E ON product (restaurant_id)');
        $this->addSql('ALTER TABLE restaurant DROP CONSTRAINT fk_eb95123f67b3b43d');
        $this->addSql('DROP INDEX idx_eb95123f67b3b43d');
        $this->addSql('ALTER TABLE restaurant DROP users_id');
        $this->addSql('ALTER TABLE restaurant ALTER country_id DROP NOT NULL');
        $this->addSql('ALTER TABLE restaurant ALTER phone DROP NOT NULL');
        $this->addSql('ALTER TABLE supplier ADD type VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE supplier ADD address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE supplier ADD phone VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE supplier ADD contact VARCHAR(55) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD restaurant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD password_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER address DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER postal_code DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649B1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8D93D649B1E7706E ON "user" (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE menu_product DROP CONSTRAINT FK_5B911913CCD7E912');
        $this->addSql('DROP SEQUENCE menu_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE propose_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_product_and_menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE propose (id INT NOT NULL, restaurant_id INT NOT NULL, product_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, avaible BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3df2d060b1e7706e ON propose (restaurant_id)');
        $this->addSql('CREATE INDEX idx_3df2d0604584665a ON propose (product_id)');
        $this->addSql('CREATE TABLE order_product_and_menu (id INT NOT NULL, order_id_id INT NOT NULL, product_id_id INT DEFAULT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7e2bc6a6de18e50b ON order_product_and_menu (product_id_id)');
        $this->addSql('CREATE INDEX idx_7e2bc6a6fcdaeaaa ON order_product_and_menu (order_id_id)');
        $this->addSql('ALTER TABLE propose ADD CONSTRAINT fk_3df2d060b1e7706e FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE propose ADD CONSTRAINT fk_3df2d0604584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product_and_menu ADD CONSTRAINT fk_7e2bc6a6fcdaeaaa FOREIGN KEY (order_id_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_product_and_menu ADD CONSTRAINT fk_7e2bc6a6de18e50b FOREIGN KEY (product_id_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_product');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649B1E7706E');
        $this->addSql('DROP INDEX IDX_8D93D649B1E7706E');
        $this->addSql('ALTER TABLE "user" DROP restaurant_id');
        $this->addSql('ALTER TABLE "user" DROP password_token');
        $this->addSql('ALTER TABLE "user" ALTER first_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER address SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER postal_code SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city SET NOT NULL');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment ALTER rating SET NOT NULL');
        $this->addSql('ALTER TABLE comment RENAME COLUMN user_id TO users_id');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT fk_9474526c67b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9474526c67b3b43d ON comment (users_id)');
        $this->addSql('ALTER TABLE restaurant ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ALTER country_id SET NOT NULL');
        $this->addSql('ALTER TABLE restaurant ALTER phone SET NOT NULL');
        $this->addSql('ALTER TABLE restaurant ADD CONSTRAINT fk_eb95123f67b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_eb95123f67b3b43d ON restaurant (users_id)');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADB1E7706E');
        $this->addSql('DROP INDEX IDX_D34A04ADB1E7706E');
        $this->addSql('ALTER TABLE product DROP restaurant_id');
        $this->addSql('ALTER TABLE product DROP stock');
        $this->addSql('ALTER TABLE product DROP product_type');
        $this->addSql('ALTER TABLE product DROP discount');
        $this->addSql('ALTER TABLE product ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE product ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE product ALTER description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE countain ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE countain ADD CONSTRAINT fk_88281b0e4584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_88281b0e4584665a ON countain (product_id)');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398A76ED395');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395');
        $this->addSql('ALTER TABLE "order" ALTER type TYPE VARCHAR(30)');
        $this->addSql('ALTER TABLE "order" ALTER type DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" ALTER statut TYPE VARCHAR(55)');
        $this->addSql('ALTER TABLE "order" ALTER statut DROP DEFAULT');
        $this->addSql('ALTER TABLE "order" RENAME COLUMN user_id TO users_id');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f529939867b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f529939867b3b43d ON "order" (users_id)');
        $this->addSql('ALTER TABLE supplier DROP type');
        $this->addSql('ALTER TABLE supplier DROP address');
        $this->addSql('ALTER TABLE supplier DROP phone');
        $this->addSql('ALTER TABLE supplier DROP contact');
    }
}
