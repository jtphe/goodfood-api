<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220630083219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE propose_id_seq CASCADE');
        $this->addSql('DROP TABLE propose');
        $this->addSql('ALTER TABLE product ADD restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD stock INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD discount INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB1E7706E FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D34A04ADB1E7706E ON product (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE propose_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE propose (id INT NOT NULL, restaurant_id INT NOT NULL, product_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, avaible BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_3df2d060b1e7706e ON propose (restaurant_id)');
        $this->addSql('CREATE INDEX idx_3df2d0604584665a ON propose (product_id)');
        $this->addSql('ALTER TABLE propose ADD CONSTRAINT fk_3df2d060b1e7706e FOREIGN KEY (restaurant_id) REFERENCES restaurant (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE propose ADD CONSTRAINT fk_3df2d0604584665a FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADB1E7706E');
        $this->addSql('DROP INDEX IDX_D34A04ADB1E7706E');
        $this->addSql('ALTER TABLE product DROP restaurant_id');
        $this->addSql('ALTER TABLE product DROP stock');
        $this->addSql('ALTER TABLE product DROP discount');
    }
}
