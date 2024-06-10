<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606141947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item ADD order_pa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A6F4EF6B FOREIGN KEY (order_pa_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F09A6F4EF6B ON order_item (order_pa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09A6F4EF6B');
        $this->addSql('DROP INDEX IDX_52EA1F09A6F4EF6B ON order_item');
        $this->addSql('ALTER TABLE order_item DROP order_pa_id');
    }
}
