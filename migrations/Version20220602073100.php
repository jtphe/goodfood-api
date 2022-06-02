<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602073100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" ALTER first_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER address DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER postal_code DROP NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ALTER first_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER last_name SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER address SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER postal_code SET NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER city SET NOT NULL');
    }
}
