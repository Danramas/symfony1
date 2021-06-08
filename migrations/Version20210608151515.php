<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210608151515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_products (category_id INT NOT NULL, product_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_4C0DE2112469DE2 (category_id), INDEX IDX_4C0DE215C977207 (product_uuid), PRIMARY KEY(category_id, product_uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_products ADD CONSTRAINT FK_4C0DE2112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_products ADD CONSTRAINT FK_4C0DE215C977207 FOREIGN KEY (product_uuid) REFERENCES product (uuid)');
        $this->addSql('DROP TABLE users_groups');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_groups (category_id INT NOT NULL, product_uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_FF8AB7E012469DE2 (category_id), INDEX IDX_FF8AB7E05C977207 (product_uuid), PRIMARY KEY(category_id, product_uuid)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE users_groups ADD CONSTRAINT FK_FF8AB7E05C977207 FOREIGN KEY (product_uuid) REFERENCES product (uuid) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE category_products');
    }
}
