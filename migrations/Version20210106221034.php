<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106221034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, entree_id INT NOT NULL, user_id INT NOT NULL, commentaire LONGTEXT NOT NULL, dateheure DATETIME NOT NULL, INDEX IDX_67F068BCAF7BD910 (entree_id), INDEX IDX_67F068BCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entree (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, assigne_id INT NOT NULL, createur_id INT NOT NULL, reference VARCHAR(80) DEFAULT NULL, titre VARCHAR(80) DEFAULT NULL, description LONGTEXT NOT NULL, type ENUM(\'INCIDENT\', \'INFORMATION\', \'PROCESS\', \'SUGGESTION\') NOT NULL COMMENT \'(DC2Type:TypeEntreeType)\', severite ENUM(\'NA\', \'BASSE\', \'MOYENNE\', \'HAUTE\', \'BLOQUANTE\') NOT NULL COMMENT \'(DC2Type:SeveriteType)\', statut ENUM(\'ANNULE\', \'FERME\', \'OUVERT\') NOT NULL COMMENT \'(DC2Type:StatutType)\', testable TINYINT(1) DEFAULT NULL, duree NUMERIC(5, 2) DEFAULT NULL, dateheure DATETIME NOT NULL, maj DATETIME NOT NULL, INDEX IDX_598377A6AFC2B591 (module_id), INDEX IDX_598377A68E7B8AB0 (assigne_id), INDEX IDX_598377A673A201E5 (createur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_fonctionnalite_type (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, libelle VARCHAR(80) NOT NULL, INDEX IDX_C4A9A9C9727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece_jointe (id INT AUTO_INCREMENT NOT NULL, entree_id INT NOT NULL, path VARCHAR(255) DEFAULT NULL, INDEX IDX_AB5111D4AF7BD910 (entree_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recherche (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, parametre LONGTEXT NOT NULL, INDEX IDX_B4271B46A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, civilite VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCAF7BD910 FOREIGN KEY (entree_id) REFERENCES entree (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A6AFC2B591 FOREIGN KEY (module_id) REFERENCES module_fonctionnalite_type (id)');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A68E7B8AB0 FOREIGN KEY (assigne_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE entree ADD CONSTRAINT FK_598377A673A201E5 FOREIGN KEY (createur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE module_fonctionnalite_type ADD CONSTRAINT FK_C4A9A9C9727ACA70 FOREIGN KEY (parent_id) REFERENCES module_fonctionnalite_type (id)');
        $this->addSql('ALTER TABLE piece_jointe ADD CONSTRAINT FK_AB5111D4AF7BD910 FOREIGN KEY (entree_id) REFERENCES entree (id)');
        $this->addSql('ALTER TABLE recherche ADD CONSTRAINT FK_B4271B46A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCAF7BD910');
        $this->addSql('ALTER TABLE piece_jointe DROP FOREIGN KEY FK_AB5111D4AF7BD910');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A6AFC2B591');
        $this->addSql('ALTER TABLE module_fonctionnalite_type DROP FOREIGN KEY FK_C4A9A9C9727ACA70');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A68E7B8AB0');
        $this->addSql('ALTER TABLE entree DROP FOREIGN KEY FK_598377A673A201E5');
        $this->addSql('ALTER TABLE recherche DROP FOREIGN KEY FK_B4271B46A76ED395');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE entree');
        $this->addSql('DROP TABLE module_fonctionnalite_type');
        $this->addSql('DROP TABLE piece_jointe');
        $this->addSql('DROP TABLE recherche');
        $this->addSql('DROP TABLE `user`');
    }
}
