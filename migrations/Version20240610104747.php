<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240610104747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D79572D9A76ED395 ON model (user_id)');
        $this->addSql('ALTER TABLE `order` ADD payement_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398868C0609 FOREIGN KEY (payement_id) REFERENCES payement (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398868C0609 ON `order` (payement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9A76ED395');
        $this->addSql('DROP INDEX IDX_D79572D9A76ED395 ON model');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398868C0609');
        $this->addSql('DROP INDEX UNIQ_F5299398868C0609 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP payement_id');
    }
}
