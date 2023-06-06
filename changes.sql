--24/03/2023 21:50
ALTER TABLE `inf1005_project`.`account_details` 
ADD COLUMN `newsletter` INT(1) UNSIGNED NOT NULL AFTER `last_login`,
ADD COLUMN `promo` INT(1) UNSIGNED NOT NULL AFTER `newsletter`;

-- 25/03/2023 10:21
ALTER TABLE `inf1005_project`.`account_details` 
ADD COLUMN `picture` VARCHAR(60) NOT NULL DEFAULT 'default.png' AFTER `promo`;

-- 25/03/2023 23:06
ALTER TABLE `inf1005_project`.`game` 
ADD COLUMN `release_date` DATE NOT NULL AFTER `description`,
ADD COLUMN `thumbnail` VARCHAR(45) NOT NULL DEFAULT 'default.jpg' AFTER `release_date`,
ADD COLUMN `video` VARCHAR(45) NOT NULL DEFAULT 'default.webm' AFTER `thumbnail`;

-- 26/03/2023 22:50
ALTER TABLE `inf1005_project`.`game` 
ADD COLUMN `gameplay` VARCHAR(1500) NOT NULL AFTER `video`,
ADD COLUMN `shop_thumbnail` VARCHAR(45) NOT NULL AFTER `gameplay`,
ADD COLUMN `youtube` VARCHAR(60) NOT NULL AFTER `shop_thumbnail`;

-- 27/03/2023 09:15
CREATE TABLE `inf1005_project`.`carousel` (
  `carousel_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(45) NOT NULL,
  `game_id` INT NULL,
  PRIMARY KEY (`carousel_id`),
  UNIQUE INDEX `carousel_id_UNIQUE` (`carousel_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;