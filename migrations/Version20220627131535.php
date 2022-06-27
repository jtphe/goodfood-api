<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220627131535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE order_product_and_menu_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE restaurant RENAME COLUMN postcal_code TO postal_code');
        $this->addSql('ALTER TABLE "user" DROP password_token');
        $this->addSql('ALTER TABLE "user" ALTER first_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER address SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER postal_code SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE order_product_and_menu_id_seq CASCADE');
        $this->addSql('ALTER TABLE restaurant RENAME COLUMN postal_code TO postcal_code');
        $this->addSql('ALTER TABLE "user" ADD password_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER address DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER postal_code DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city DROP NOT NULL');
    }
}
