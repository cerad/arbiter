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

  domain     VARCHAR( 20),
  domain_sub VARCHAR( 40),
  season     VARCHAR( 20),
  sport      VARCHAR( 20),

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
  project_level_id INT,

  start  DATETIME,
  finish DATETIME,
  length INT,

  note      VARCHAR(255), # Really should be a project_game_notes relation
  note_date DATE,

  status  VARCHAR(20),

  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_game_teams
(
  id INT AUTO_INCREMENT NOT NULL,

  project_game_id INT NOT NULL,
  project_team_id INT,

  slot   VARCHAR(20),
  source VARCHAR(99),

  score         INT,
  sportsmanship INT,
  warnings      INT,
  ejections     INT,

  status  VARCHAR(20),

  PRIMARY KEY(id),
  UNIQUE INDEX project_game_teams_game_slot_index(project_game_id,slot)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_teams
(
  id INT AUTO_INCREMENT NOT NULL,

  project_id       INT NOT NULL,
  project_level_id INT NOT NULL,

  team_key VARCHAR(99),

  name  VARCHAR(99),

  status  VARCHAR(20),

  PRIMARY KEY(id),
  UNIQUE INDEX project_teams_project_level_name_index(project_id,project_level_id,name)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_officials
(
  id INT AUTO_INCREMENT NOT NULL,

  project_id INT NOT NULL,

  person_key VARCHAR(99),

  name  VARCHAR(99),
  email VARCHAR(99),
  phone VARCHAR(99),
  badge VARCHAR(20),

  status  VARCHAR(20),

  PRIMARY KEY(id),
  UNIQUE INDEX project_officials_project_name_index(project_id,name)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE project_game_officials
(
  id INT AUTO_INCREMENT NOT NULL,

  project_game_id     INT NOT NULL,
  project_official_id INT,
  project_team_id     INT, # For points

  slot  VARCHAR(20),
  badge VARCHAR(20), # Allow badge changes during a season

  assign_state VARCHAR(20),

  PRIMARY KEY(id),
  UNIQUE INDEX project_game_officials_game_slot_index(project_game_id,slot)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;


