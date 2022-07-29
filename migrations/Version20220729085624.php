<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729085624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE menu_order');
        $this->addSql('ALTER TABLE menu ADD order_menu_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A933AAD4059 FOREIGN KEY (order_menu_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7D053A933AAD4059 ON menu (order_menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE menu_order (menu_id INT NOT NULL, order_id INT NOT NULL, PRIMARY KEY(menu_id, order_id))');
        $this->addSql('CREATE INDEX idx_6485b0ff8d9f6d38 ON menu_order (order_id)');
        $this->addSql('CREATE INDEX idx_6485b0ffccd7e912 ON menu_order (menu_id)');
        $this->addSql('ALTER TABLE menu_order ADD CONSTRAINT fk_6485b0ffccd7e912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu_order ADD CONSTRAINT fk_6485b0ff8d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE menu DROP CONSTRAINT FK_7D053A933AAD4059');
        $this->addSql('DROP INDEX IDX_7D053A933AAD4059');
        $this->addSql('ALTER TABLE menu DROP order_menu_id');
    }
}
