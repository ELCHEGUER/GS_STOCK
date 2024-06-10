<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240606154636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A788538F46F');
        $this->addSql('DROP INDEX UNIQ_B20A788538F46F ON payement');
        $this->addSql('ALTER TABLE payement CHANGE order_pro_id order_id INT NOT NULL');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A78858D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B20A78858D9F6D38 ON payement (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A78858D9F6D38');
        $this->addSql('DROP INDEX UNIQ_B20A78858D9F6D38 ON payement');
        $this->addSql('ALTER TABLE payement CHANGE order_id order_pro_id INT NOT NULL');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A788538F46F FOREIGN KEY (order_pro_id) REFERENCES `order` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B20A788538F46F ON payement (order_pro_id)');
    }
}
