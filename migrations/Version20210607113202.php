<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210607113202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP id, DROP PRIMARY KEY, ADD PRIMARY KEY (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product MODIFY uuid BINARY(16) NOT NULL');
        $this->addSql('ALTER TABLE product DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE product ADD id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP uuid');
        $this->addSql('ALTER TABLE product ADD PRIMARY KEY (id)');
    }
}
