SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `uguilds` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `uguilds` ;

-- -----------------------------------------------------
-- Table `uguilds`.`ug_Guilds`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_Guilds` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_Guilds` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `region` VARCHAR(2) NOT NULL DEFAULT 'EU',
  `realm` VARCHAR(32) NOT NULL,
  `guild_name` VARCHAR(24) NOT NULL,
  `domain_name` VARCHAR(255) NOT NULL,
  `theme` VARCHAR(255) NULL DEFAULT 'a6e284d6c07328787bb817c6a0000b29',
  `locale` VARCHAR(5) NULL DEFAULT 'en_GB',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `domainName_UNIQUE` ON `uguilds`.`ug_Guilds` (`domain_name` ASC);

CREATE UNIQUE INDEX `region_realm_name_UNIQUE` ON `uguilds`.`ug_Guilds` (`region` ASC, `realm` ASC, `guild_name` ASC);


-- -----------------------------------------------------
-- Table `uguilds`.`ug_GuildRanks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_GuildRanks` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_GuildRanks` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `guild_id` INT(11) UNSIGNED NOT NULL,
  `position` INT UNSIGNED NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `ug_guild_ranks_ug_guilds`
    FOREIGN KEY (`guild_id`)
    REFERENCES `uguilds`.`ug_Guilds` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `ug_guild_ranks_ug_guilds_idx` ON `uguilds`.`ug_GuildRanks` (`guild_id` ASC);

CREATE UNIQUE INDEX `guild_id_position_UNIQUE` ON `uguilds`.`ug_GuildRanks` (`guild_id` ASC, `position` ASC);


-- -----------------------------------------------------
-- Table `uguilds`.`ug_Accounts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_Accounts` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_Accounts` (
  `id` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NULL,
  `activation_code` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `is_suspended` TINYINT(1) NOT NULL DEFAULT 0,
  `active_character` INT UNSIGNED NULL DEFAULT NULL,
  `battletag` VARCHAR(45) NULL DEFAULT NULL,
  `ug_Accountscol` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `email_UNIQUE` ON `uguilds`.`ug_Accounts` (`email` ASC);

CREATE UNIQUE INDEX `battletag_UNIQUE` ON `uguilds`.`ug_Accounts` (`battletag` ASC);

CREATE UNIQUE INDEX `_id_UNIQUE` ON `uguilds`.`ug_Accounts` (`id` ASC);


-- -----------------------------------------------------
-- Table `uguilds`.`ug_Characters`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_Characters` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_Characters` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `region` VARCHAR(2) NULL DEFAULT 'EU',
  `realm` VARCHAR(128) NOT NULL,
  `name` VARCHAR(16) NOT NULL,
  `guild_id` INT(11) UNSIGNED NULL,
  `account_id` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `ug_characters_ug_guilds`
    FOREIGN KEY (`guild_id`)
    REFERENCES `uguilds`.`ug_Guilds` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `ug_characters_ug_accounts`
    FOREIGN KEY (`account_id`)
    REFERENCES `uguilds`.`ug_Accounts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `ug_characters_ug_guilds_idx` ON `uguilds`.`ug_Characters` (`guild_id` ASC);

CREATE INDEX `ug_characters_ug_accounts_idx` ON `uguilds`.`ug_Characters` (`account_id` ASC);

CREATE UNIQUE INDEX `ug_characters_uq` ON `uguilds`.`ug_Characters` (`region` ASC, `realm` ASC, `name` ASC);


-- -----------------------------------------------------
-- Table `uguilds`.`ug_Features`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_Features` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_Features` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `controller` VARCHAR(45) NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `title_UNIQUE` ON `uguilds`.`ug_Features` (`title` ASC);

CREATE UNIQUE INDEX `controller_UNIQUE` ON `uguilds`.`ug_Features` (`controller` ASC);


-- -----------------------------------------------------
-- Table `uguilds`.`ug_GuildFeatures`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_GuildFeatures` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_GuildFeatures` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `guild_id` INT UNSIGNED NOT NULL,
  `feature_id` INT UNSIGNED NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `ug_guild_features_ug_features`
    FOREIGN KEY (`feature_id`)
    REFERENCES `uguilds`.`ug_Features` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `ug_guild_features_ug_guilds`
    FOREIGN KEY (`guild_id`)
    REFERENCES `uguilds`.`ug_Guilds` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `ug_guild_feature` ON `uguilds`.`ug_GuildFeatures` (`guild_id` ASC, `feature_id` ASC);

CREATE INDEX `ug_guild_features_ug_features_idx` ON `uguilds`.`ug_GuildFeatures` (`feature_id` ASC);


-- -----------------------------------------------------
-- Table `uguilds`.`ug_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_sessions` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0',
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '0',
  `user_agent` VARCHAR(120) NOT NULL,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` TEXT NOT NULL,
  PRIMARY KEY (`session_id`));

CREATE INDEX `last_activity_idx` ON `uguilds`.`ug_sessions` (`last_activity` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
USE `uguilds`;

DELIMITER $$

USE `uguilds`$$
DROP TRIGGER IF EXISTS `uguilds`.`ug_GuildRanks_POSITION_CHECK` $$
USE `uguilds`$$
CREATE TRIGGER `ug_GuildRanks_POSITION_CHECK` BEFORE INSERT ON `ug_GuildRanks` FOR EACH ROW
BEGIN
DECLARE errmsg varchar(255);
IF(NEW.position>10)
THEN
	SET errmsg = concat('Guild Rank cannot be greater than 9.');
	SIGNAL sqlstate '45000' SET message_text = msg;
END IF;
END$$


DELIMITER ;
