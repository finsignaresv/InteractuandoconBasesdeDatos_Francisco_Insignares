-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema agenda
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema agenda
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `agenda` DEFAULT CHARACTER SET utf8 ;
USE `agenda` ;

-- -----------------------------------------------------
-- Table `agenda`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agenda`.`usuarios` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NULL,
  `nombre_completo` VARCHAR(45) NULL,
  `password` VARCHAR(255) NULL,
  `fecha_nacimiento` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `correo_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `agenda`.`eventos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `agenda`.`eventos` (
  `cod` INT NOT NULL AUTO_INCREMENT,
  `fk_usuario` INT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `fecha_inicio` DATE NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `fecha_fin` DATE NULL,
  `hora_fin` TIME NULL,
  `dia_completo` TINYINT(1) NOT NULL,
  PRIMARY KEY (`cod`),
  INDEX `fk_usuario_idx` (`fk_usuario` ASC),
  CONSTRAINT `fk_usuario`
    FOREIGN KEY (`fk_usuario`)
    REFERENCES `agenda`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
