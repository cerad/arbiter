DROP DATABASE IF EXISTS arbiter;

CREATE DATABASE arbiter;

USE arbiter;

DROP TABLE IF EXISTS projects;

CREATE TABLE projects
(
  id INT AUTO_INCREMENT NOT NULL,

  role   VARCHAR( 20),
  name   VARCHAR( 40),
  title  VARCHAR(255),

  domain VARCHAR( 20),
  league VARCHAR( 40),
  season VARCHAR( 20),
  sport  VARCHAR( 20),

  start  DATE,
  finish DATE,

  status VARCHAR( 20),

  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_levels
(
  id INT AUTO_INCREMENT NOT NULL,

  level_id   INT,
  project_id INT NOT NULL,

  name   VARCHAR( 40) NOT NULL,
  title  VARCHAR(255),

  age     VARCHAR(20),
  gender  VARCHAR(40),
  program VARCHAR(20),

  status  VARCHAR(20),

  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_fields
(
  id INT AUTO_INCREMENT NOT NULL,

  field_id   INT,
  project_id INT NOT NULL,

  name   VARCHAR( 40) NOT NULL,
  title  VARCHAR(255),

  status  VARCHAR(20),

  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_games
(
  id INT AUTO_INCREMENT NOT NULL,

  project_id INT NOT NULL,
  number     INT NOT NULL,

  project_field_id INT,

  start  DATETIME,
  finish DATETIME,
  length INT,

  note      VARCHAR(255), # Really should be a project_game_notes relation
  note_date DATE,

  status  VARCHAR(20),

  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;



