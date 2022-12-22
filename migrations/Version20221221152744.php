<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221152744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C39A5E05F');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1439A5E05F');
        $this->addSql('CREATE TABLE recette (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, ingredients VARCHAR(255) NOT NULL, etapes VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_49BB6390A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cooking_blog DROP FOREIGN KEY FK_43213F62A76ED395');
        $this->addSql('DROP TABLE cooking_blog');
        $this->addSql('DROP INDEX IDX_9474526C39A5E05F ON comment');
        $this->addSql('ALTER TABLE comment ADD recette_id INT NOT NULL, CHANGE cooking_blog_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C89312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('CREATE INDEX IDX_9474526CA76ED395 ON comment (user_id)');
        $this->addSql('CREATE INDEX IDX_9474526C89312FE9 ON comment (recette_id)');
        $this->addSql('DROP INDEX IDX_CFBDFA1439A5E05F ON note');
        $this->addSql('ALTER TABLE note ADD user_id INT NOT NULL, ADD value INT NOT NULL, DROP note, CHANGE cooking_blog_id recette_id INT NOT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1489312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA1489312FE9 ON note (recette_id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14A76ED395 ON note (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C89312FE9');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1489312FE9');
        $this->addSql('CREATE TABLE cooking_blog (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ingredients VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, steps VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_43213F62A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cooking_blog ADD CONSTRAINT FK_43213F62A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('DROP TABLE recette');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP INDEX IDX_9474526CA76ED395 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C89312FE9 ON comment');
        $this->addSql('ALTER TABLE comment ADD cooking_blog_id INT NOT NULL, DROP user_id, DROP recette_id');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C39A5E05F FOREIGN KEY (cooking_blog_id) REFERENCES cooking_blog (id)');
        $this->addSql('CREATE INDEX IDX_9474526C39A5E05F ON comment (cooking_blog_id)');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A76ED395');
        $this->addSql('DROP INDEX IDX_CFBDFA1489312FE9 ON note');
        $this->addSql('DROP INDEX IDX_CFBDFA14A76ED395 ON note');
        $this->addSql('ALTER TABLE note ADD cooking_blog_id INT NOT NULL, ADD note VARCHAR(255) NOT NULL, DROP recette_id, DROP user_id, DROP value');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1439A5E05F FOREIGN KEY (cooking_blog_id) REFERENCES cooking_blog (id)');
        $this->addSql('CREATE INDEX IDX_CFBDFA1439A5E05F ON note (cooking_blog_id)');
    }
}
