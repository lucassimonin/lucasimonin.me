<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180826184033 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, path_media VARCHAR(255) DEFAULT NULL, mimetype VARCHAR(255) DEFAULT NULL, extension VARCHAR(255) DEFAULT NULL, size VARCHAR(255) DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url_page (path LONGTEXT NOT NULL, locale VARCHAR(3) NOT NULL, path_string LONGTEXT NOT NULL, INDEX path_idx (path), INDEX locale_idx (locale), PRIMARY KEY(path, locale)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, type_id INT DEFAULT NULL, position INT NOT NULL, config JSON NOT NULL, INDEX IDX_49FEA157C4663E4 (page_id), INDEX IDX_49FEA157C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component_skeleton (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, object VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, icon VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, path VARCHAR(1000) DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_140AB620B548B0F (path), INDEX IDX_140AB620A977936C (tree_root), INDEX IDX_140AB620727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page_version (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, modifier_id INT DEFAULT NULL, translator_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, page_id INT DEFAULT NULL, og_cover_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, version INT NOT NULL, state JSON DEFAULT NULL COMMENT \'(DC2Type:json_array)\', locale VARCHAR(3) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, modify VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, seo_meta_title VARCHAR(255) DEFAULT NULL, seo_meta_description LONGTEXT DEFAULT NULL, og_title VARCHAR(255) DEFAULT NULL, og_description LONGTEXT DEFAULT NULL, INDEX IDX_457C385686383B10 (avatar_id), INDEX IDX_457C3856D079F553 (modifier_id), INDEX IDX_457C38565370E40B (translator_id), INDEX IDX_457C3856642B8210 (admin_id), INDEX IDX_457C3856C4663E4 (page_id), INDEX IDX_457C3856C17EBB3C (og_cover_id), INDEX locale_idx (locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157C4663E4 FOREIGN KEY (page_id) REFERENCES page_version (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157C54C8C93 FOREIGN KEY (type_id) REFERENCES component_skeleton (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620A977936C FOREIGN KEY (tree_root) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB620727ACA70 FOREIGN KEY (parent_id) REFERENCES page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE page_version ADD CONSTRAINT FK_457C385686383B10 FOREIGN KEY (avatar_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE page_version ADD CONSTRAINT FK_457C3856D079F553 FOREIGN KEY (modifier_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE page_version ADD CONSTRAINT FK_457C38565370E40B FOREIGN KEY (translator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE page_version ADD CONSTRAINT FK_457C3856642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE page_version ADD CONSTRAINT FK_457C3856C4663E4 FOREIGN KEY (page_id) REFERENCES page (id)');
        $this->addSql('ALTER TABLE page_version ADD CONSTRAINT FK_457C3856C17EBB3C FOREIGN KEY (og_cover_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE dk_component');
        $this->addSql('DROP TABLE dk_example');
        $this->addSql('DROP TABLE dk_fail2ban');
        $this->addSql('DROP TABLE dk_localisation');
        $this->addSql('DROP TABLE dk_media');
        $this->addSql('DROP TABLE dk_newsletter');
        $this->addSql('DROP TABLE dk_page');
        $this->addSql('DROP TABLE dk_user');
        $this->addSql('ALTER TABLE user ADD googleAuthenticatorSecret VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES media (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE page_version DROP FOREIGN KEY FK_457C385686383B10');
        $this->addSql('ALTER TABLE page_version DROP FOREIGN KEY FK_457C3856C17EBB3C');
        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA157C54C8C93');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620A977936C');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB620727ACA70');
        $this->addSql('ALTER TABLE page_version DROP FOREIGN KEY FK_457C3856C4663E4');
        $this->addSql('ALTER TABLE component DROP FOREIGN KEY FK_49FEA157C4663E4');
        $this->addSql('CREATE TABLE dk_component (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, config LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, created DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, deletedAt DATETIME DEFAULT NULL, page_id INT DEFAULT NULL, position INT DEFAULT NULL, type VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, updated DATETIME NOT NULL, updated_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_C49CE858C4663E4 (page_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_example (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, category VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, cover_id INT DEFAULT NULL, created DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, crop_id INT DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, description_ui LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, file_id INT DEFAULT NULL, multiples LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, publish_date DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, updated DATETIME NOT NULL, updated_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_9A36768E888579EE (crop_id), INDEX IDX_9A36768E922726E9 (cover_id), INDEX IDX_9A36768E93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_fail2ban (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, ip VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, route VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, success TINYINT(1) NOT NULL, updated DATETIME NOT NULL, username VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_localisation (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, address_more VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, city VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, code VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, country VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, phone VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, user_id INT DEFAULT NULL, INDEX IDX_36887D07A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_media (id INT AUTO_INCREMENT NOT NULL, alt VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, deletedAt DATETIME DEFAULT NULL, description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, extension VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, legend VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, mimetype VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, path_media VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, size VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, updated DATETIME NOT NULL, updated_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_newsletter (id INT AUTO_INCREMENT NOT NULL, created DATETIME NOT NULL, deletedAt DATETIME DEFAULT NULL, email VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ip VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_page (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, created DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, deletedAt DATETIME DEFAULT NULL, locale VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, modify VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, modify_author VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, og_cover_id INT DEFAULT NULL, og_description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, og_title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, path VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, seo_meta_description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, seo_meta_title VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, updated DATETIME NOT NULL, updated_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_FAB7937C17EBB3C (og_cover_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dk_user (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, birthday DATETIME DEFAULT NULL, civility INT NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, created DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(60) NOT NULL COLLATE utf8mb4_unicode_ci, enabled TINYINT(1) NOT NULL, first_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, last_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, news TINYINT(1) NOT NULL, password VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, roles LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:array)\', slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, updated DATETIME NOT NULL, updated_by VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, username VARCHAR(25) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_9632195E86383B10 (avatar_id), UNIQUE INDEX UNIQ_9632195E989D9B62 (slug), UNIQUE INDEX UNIQ_9632195EE7927C74 (email), UNIQUE INDEX UNIQ_9632195EF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE url_page');
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE component_skeleton');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE page_version');
        $this->addSql('ALTER TABLE user DROP googleAuthenticatorSecret');
    }
}
