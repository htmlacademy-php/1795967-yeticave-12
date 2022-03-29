CREATE
DATABASE yeticave_67
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave_67;

CREATE TABLE users
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  create_data DATETIME DEFAULT CURRENT_TIMESTAMP,
  email       VARCHAR(256) NOT NULL UNIQUE,
  name        VARCHAR(128) NOT NULL,
  password    VARCHAR(128) NOT NULL,
  message     TEXT
);

CREATE TABLE categories
(
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL UNIQUE,
  code VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE lots
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  create_data DATETIME DEFAULT CURRENT_TIMESTAMP,
  title       TEXT NOT NULL,
  description TEXT NOT NULL,
  path        TEXT,
  price       INT  NOT NULL,
  finish_date DATETIME,
  step        INT  NOT NULL,
  user_id     INT,
  winner_id   INT,
  category_id INT,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (winner_id) REFERENCES users (id),
  FOREIGN KEY (category_id) REFERENCES categories (id)
);

CREATE TABLE bets
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  create_data DATETIME DEFAULT CURRENT_TIMESTAMP,
  bet         INT NOT NULL,
  user_id     INT,
  lot_id      INT,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (lot_id) REFERENCES lots (id)
);



