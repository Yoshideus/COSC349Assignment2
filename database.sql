CREATE DATABASE tictactoe;

USE tictactoe;

CREATE TABLE users (username varchar(200) NOT NULL, password varchar(60) NOT NULL, PRIMARY KEY (username));

CREATE TABLE games (
  gameid int NOT NULL AUTO_INCREMENT,
  user1 varchar(200),
  user2 varchar(200),
  turnnum int,
  whoseturn varchar(200),
  p1 varchar(1),
  p2 varchar(1),
  p3 varchar(1),
  p4 varchar(1),
  p5 varchar(1),
  p6 varchar(1),
  p7 varchar(1),
  p8 varchar(1),
  p9 varchar(1),
  PRIMARY KEY (gameid),
  FOREIGN KEY (user1) REFERENCES users(username),
  FOREIGN KEY (user2) REFERENCES users(username)
);

CREATE TABLE stats (
  username varchar(200) NOT NULL,
  gamesplayed int,
  wins int,
  draws int,
  loses int,
  score int,
  winrate float,
  PRIMARY KEY (username),
  FOREIGN KEY (username) REFERENCES users(username)
);

INSERT INTO users (username, password) VALUES ("test", "$2y$10$NGS0xQent7N4Up.lYx3Ziuki6MHgmJeSgNRCvC3wJnPHMmS0sDWCK");
INSERT INTO users (username, password) VALUES ("test1", "$2y$10$msnWTFSUEcb.BU.vXZoYtuksgRaPT60lnYVrHoydCL0pEWH1YdQYC");
INSERT INTO users (username, password) VALUES ("test2", "$2y$10$16DldbKweqtHbQTQ0LB7ReTmm5MUJbr8LPI01n8m0Fa2GoXsQd8Mq");

INSERT INTO stats (username, gamesplayed, wins, draws, loses, score, winrate) VALUES ("test", 80, 40, 30, 10, 150, 0.50);
INSERT INTO stats (username, gamesplayed, wins, draws, loses, score, winrate) VALUES ("test1", 72, 40, 30, 2, 150, 0.556);

<?php

define('DB_SERVER', 'tictactoedata.ckyvtmldqfco.us-east-1.rds.amazonaws.com');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'yoshi738');
define('DB_DATABASE', 'tictactoe');

?>
