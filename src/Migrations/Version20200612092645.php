<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200612092645 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE recipe_total (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_total_ingredient (recipe_total_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_DBAA4C7B9A479C22 (recipe_total_id), INDEX IDX_DBAA4C7B933FE08C (ingredient_id), PRIMARY KEY(recipe_total_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_total_recipe (recipe_total_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_5897C52E9A479C22 (recipe_total_id), INDEX IDX_5897C52E59D8A214 (recipe_id), PRIMARY KEY(recipe_total_id, recipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_total_ingredient ADD CONSTRAINT FK_DBAA4C7B9A479C22 FOREIGN KEY (recipe_total_id) REFERENCES recipe_total (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_total_ingredient ADD CONSTRAINT FK_DBAA4C7B933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_total_recipe ADD CONSTRAINT FK_5897C52E9A479C22 FOREIGN KEY (recipe_total_id) REFERENCES recipe_total (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_total_recipe ADD CONSTRAINT FK_5897C52E59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe_total_ingredient DROP FOREIGN KEY FK_DBAA4C7B9A479C22');
        $this->addSql('ALTER TABLE recipe_total_recipe DROP FOREIGN KEY FK_5897C52E9A479C22');
        $this->addSql('DROP TABLE recipe_total');
        $this->addSql('DROP TABLE recipe_total_ingredient');
        $this->addSql('DROP TABLE recipe_total_recipe');
    }
}
