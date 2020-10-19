<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201004133340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE flash_cards_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flash_records_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flash_decks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_users (id UUID NOT NULL, email VARCHAR(255) NOT NULL, temporary_email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, temporary_password VARCHAR(255) DEFAULT NULL, status VARCHAR(16) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, role VARCHAR(16) NOT NULL, confirm_token_token VARCHAR(255) DEFAULT NULL, confirm_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1F464BC96 ON user_users (confirm_token_token)');
        $this->addSql('COMMENT ON COLUMN user_users.id IS \'(DC2Type:users_user_id)\'');
        $this->addSql('COMMENT ON COLUMN user_users.email IS \'(DC2Type:users_user_email)\'');
        $this->addSql('COMMENT ON COLUMN user_users.temporary_email IS \'(DC2Type:users_user_email)\'');
        $this->addSql('COMMENT ON COLUMN user_users.password IS \'(DC2Type:users_user_password)\'');
        $this->addSql('COMMENT ON COLUMN user_users.temporary_password IS \'(DC2Type:users_user_password)\'');
        $this->addSql('COMMENT ON COLUMN user_users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.role IS \'(DC2Type:users_user_role)\'');
        $this->addSql('COMMENT ON COLUMN user_users.confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flash_cards (id INT NOT NULL, deck_id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, repeat_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, repeat_count INT NOT NULL, repeat_total_time VARCHAR(255) NOT NULL, repeat_success_count INT NOT NULL, repeat_interval VARCHAR(255) NOT NULL, repeat_state VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F69B1DBD111948DC ON flash_cards (deck_id)');
        $this->addSql('COMMENT ON COLUMN flash_cards.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.repeat_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.repeat_total_time IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.repeat_interval IS \'(DC2Type:dateinterval)\'');
        $this->addSql('CREATE TABLE flash_records (id INT NOT NULL, card_id INT DEFAULT NULL, content TEXT DEFAULT NULL, side BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A5250054ACC9A20 ON flash_records (card_id)');
        $this->addSql('CREATE TABLE flash_decks (id INT NOT NULL, learner_id UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, settings_limit_repeat INT NOT NULL, settings_limit_learning INT NOT NULL, settings_difficulty_index DOUBLE PRECISION NOT NULL, settings_start_time_interval VARCHAR(255) NOT NULL, settings_min_time_interval VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51A583726209CB66 ON flash_decks (learner_id)');
        $this->addSql('COMMENT ON COLUMN flash_decks.learner_id IS \'(DC2Type:flash_learner_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_decks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_decks.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_decks.settings_start_time_interval IS \'(DC2Type:dateinterval)\'');
        $this->addSql('COMMENT ON COLUMN flash_decks.settings_min_time_interval IS \'(DC2Type:dateinterval)\'');
        $this->addSql('CREATE TABLE flash_learners (id UUID NOT NULL, name_first VARCHAR(255) DEFAULT NULL, name_last VARCHAR(255) DEFAULT NULL, settings_individual_plan BOOLEAN NOT NULL, session_intervals JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN flash_learners.id IS \'(DC2Type:flash_learner_id)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('ALTER TABLE flash_cards ADD CONSTRAINT FK_F69B1DBD111948DC FOREIGN KEY (deck_id) REFERENCES flash_decks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flash_records ADD CONSTRAINT FK_6A5250054ACC9A20 FOREIGN KEY (card_id) REFERENCES flash_cards (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flash_decks ADD CONSTRAINT FK_51A583726209CB66 FOREIGN KEY (learner_id) REFERENCES flash_learners (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE flash_records DROP CONSTRAINT FK_6A5250054ACC9A20');
        $this->addSql('ALTER TABLE flash_cards DROP CONSTRAINT FK_F69B1DBD111948DC');
        $this->addSql('ALTER TABLE flash_decks DROP CONSTRAINT FK_51A583726209CB66');
        $this->addSql('DROP SEQUENCE flash_cards_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flash_records_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flash_decks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE user_users');
        $this->addSql('DROP TABLE flash_cards');
        $this->addSql('DROP TABLE flash_records');
        $this->addSql('DROP TABLE flash_decks');
        $this->addSql('DROP TABLE flash_learners');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
