<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109115031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS bookshelf_books (
            `id` int NOT NULL AUTO_INCREMENT,
            `type_id` int DEFAULT NULL,
            `note_id` int DEFAULT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `author` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
            `summary` longtext COLLATE utf8mb4_unicode_ci,
            `finished_at` datetime DEFAULT NULL,
            `abandonned_at` datetime DEFAULT NULL,
            `deleted_at` datetime DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            `user_id` int DEFAULT NULL,
            `is_private` tinyint(1) NOT NULL DEFAULT \'0\',
            `has_private_summary` tinyint(1) NOT NULL DEFAULT \'0\',
            INDEX IDX_4A1B2A92C54C8C93 (type_id),
            INDEX IDX_4A1B2A9226ED0855 (note_id),
            INDEX FK_4A1B2A92A76ED395 (user_id),
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

                $this->addSql('CREATE TABLE IF NOT EXISTS bookshelf_users (
            id INT AUTO_INCREMENT NOT NULL,
            
            username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            last_connected_at DATETIME NOT NULL,
            fav_color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`,
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE IF NOT EXISTS bookshelf_types (
            id INT AUTO_INCREMENT NOT NULL,
            
            name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql('CREATE TABLE bookshelf_notes (
            id INT AUTO_INCREMENT NOT NULL,
            
            name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            legend VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`,
            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');

        $this->addSql("INSERT INTO `bookshelf_notes` VALUES (1,'★','mhh'),(2,'★ ★','ok…'),(3,'★ ★ ★','bien')");
        $this->addSql("INSERT INTO `bookshelf_types` VALUES (1,'novel'),(2,'essay')");
        $this->addSql("INSERT INTO `bookshelf_users` VALUES 
                        (1,'user1','$2y$13\$ggQEOYIP0GTeBrNaeg5ijeSlRnpx3MCLBlCd4zN4OpPQL0gL4D5mS','2023-11-08 15:54:49','#00a171'),
                        (2,'user2','$2y$13\$ggQEOYIP0GTeBrNaeg5ijeSlRnpx3MCLBlCd4zN4OpPQL0gL4D5mS','2023-11-08 15:54:49',null) ");
        $this->addSql("INSERT INTO `bookshelf_books` VALUES 
                        (1,1,3,'My Title 1','Author 1','user1_author1_title1','summary for book 1',NULL,NULL,NULL,NULL,NULL,1,0,0),
                        (2,1,1,'My Title 2','Author 2','user2_author2_title2',NULL,'2022-10-03 00:00:00',NULL,NULL,'2023-11-04 08:21:15','2023-11-04 08:21:15',2,0,0),
                        (3,1,1,'My Title 3','Author 1','user1_author1_title3',NULL,NULL,NULL,NULL,NULL,NULL,1,0,0),
                        (4,1,1,'My Title 4','Author 1','user1_author1_title4',NULL,NULL,NULL,NULL,NULL,NULL,1,0,0)
                        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bookshelf_books');
        $this->addSql('DROP TABLE bookshelf_users');
        $this->addSql('DROP TABLE bookshelf_types');
        $this->addSql('DROP TABLE bookshelf_notes');
    }
}
