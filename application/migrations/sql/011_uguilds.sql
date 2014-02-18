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
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `region` VARCHAR(2) NOT NULL DEFAULT 'EU',
  `realm` VARCHAR(32) NOT NULL,
  `guild_name` VARCHAR(24) NOT NULL,
  `domain_name` VARCHAR(255) NOT NULL,
  `theme` VARCHAR(255) NULL DEFAULT 'a6e284d6c07328787bb817c6a0000b29',
  `locale` VARCHAR(5) NULL DEFAULT 'en_GB',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `domainName_UNIQUE` (`domain_name` ASC),
  UNIQUE INDEX `region_realm_name_UNIQUE` (`region` ASC, `realm` ASC, `guild_name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uguilds`.`ug_GuildRanks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_GuildRanks` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_GuildRanks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `guild_id` INT UNSIGNED NOT NULL,
  `position` INT UNSIGNED NOT NULL,
  `title` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `ug_guild_ranks_ug_guilds_idx` (`guild_id` ASC),
  INDEX (),
  CONSTRAINT `ug_guild_ranks_ug_guilds`
    FOREIGN KEY (`guild_id`)
    REFERENCES `uguilds`.`ug_Guilds` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


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
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `battletag_UNIQUE` (`battletag` ASC),
  UNIQUE INDEX `_id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uguilds`.`ug_Characters`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_Characters` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_Characters` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `region` VARCHAR(2) NULL DEFAULT 'EU',
  `realm` VARCHAR(128) NOT NULL,
  `name` VARCHAR(16) NOT NULL,
  `guild_id` INT UNSIGNED NULL DEFAULT NULL,
  `account_id` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `ug_characters_ug_guilds_idx` (`guild_id` ASC),
  INDEX `ug_characters_ug_accounts_idx` (`account_id` ASC),
  UNIQUE INDEX `ug_characters_uq` (`region` ASC, `realm` ASC, `name` ASC),
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


-- -----------------------------------------------------
-- Table `uguilds`.`ug_Features`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_Features` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_Features` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `controller` VARCHAR(45) NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `title_UNIQUE` (`title` ASC),
  UNIQUE INDEX `controller_UNIQUE` (`controller` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `uguilds`.`ug_GuildFeatures`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ug_GuildFeatures` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ug_GuildFeatures` (
  `id` INT UNSIGNED NOT NULL,
  `guild_id` INT UNSIGNED NOT NULL,
  `feature_id` INT UNSIGNED NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `ug_guild_feature` (`guild_id` ASC, `feature_id` ASC),
  INDEX `ug_guild_features_ug_features_idx` (`feature_id` ASC),
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


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Achievements`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Achievements` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Achievements` (
  `id` INT(11) NOT NULL,
  `ObjectID` VARCHAR(50) NOT NULL,
  `description` VARCHAR(250) NOT NULL,
  `title` VARCHAR(75) NOT NULL,
  `points` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_ArenaTeams`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_ArenaTeams` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_ArenaTeams` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_AuctionHouse`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_AuctionHouse` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_AuctionHouse` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Characters`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Characters` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Characters` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Classes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Classes` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Classes` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Guilds`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Guilds` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Guilds` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Items`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Items` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Items` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Perks`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Perks` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Perks` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Quests`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Quests` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Quests` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_Races`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_Races` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_Races` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`wa_WowHead`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`wa_WowHead` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`wa_WowHead` (
  `ObjectID` VARCHAR(50) NOT NULL,
  `Data` LONGBLOB NOT NULL,
  `Part` INT(11) NOT NULL,
  `Timestamp` VARCHAR(75) NOT NULL,
  PRIMARY KEY (`ObjectID`, `Part`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `uguilds`.`ci_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `uguilds`.`ci_sessions` ;

CREATE TABLE IF NOT EXISTS `uguilds`.`ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0',
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '0',
  `user_agent` VARCHAR(120) NOT NULL,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` TEXT NOT NULL,
  PRIMARY KEY (`session_id`),
  INDEX `last_activity_idx` (`last_activity` ASC));


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
