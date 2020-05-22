CREATE DATABASE YetiCave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE YetiCave;

CREATE TABLE Categories (
    PRIMARY KEY (cat_code),
    cat_code      VARCHAR(30) NOT NULL,
    category_name VARCHAR(60) NOT NULL UNIQUE
);

CREATE TABLE Lots (
	PRIMARY KEY (lot_id),
    lot_id      INTEGER       NOT NULL AUTO_INCREMENT,
    lot_name    VARCHAR(100)  NOT NULL,
    create_date DATETIME      DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255),
    image_link  VARCHAR(255),
    start_price INTEGER       DEFAULT 0,
    final_date 	DATETIME      NOT NULL,
    step_rate   INTEGER       DEFAULT 1,
    author      INTEGER       NOT NULL,
    winner      INTEGER,
    code        VARCHAR(30)   NOT NULL,

    FOREIGN KEY (author) REFERENCES Users(user_id),
    FOREIGN KEY (winner) REFERENCES Users(user_id),
    FOREIGN KEY (code)   REFERENCES Categories(cat_code),

    INDEX ix_lot_name (lot_name),
    INDEX ix_create_date (create_date),
    INDEX ix_start_price (start_price),
    INDEX ix_final_date (final_date),
    INDEX ix_author (author),
    INDEX ix_winner (winner)
);

CREATE TABLE Rates (
    PRIMARY KEY (rate_id),
	rate_id   INTEGER  NOT NULL AUTO_INCREMENT,
    lot_id    INTEGER  NOT NULL,
	user_id   INTEGER  NOT NULL,
    rate      INTEGER  NOT NULL,
    date_rate DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (lot_id)  REFERENCES Lots(lot_id),

    INDEX ix_rate (rate),
    INDEX ix_date_rate (date_rate)
);

CREATE TABLE Users (
    PRIMARY KEY (user_id),
    user_id           INTEGER      NOT NULL AUTO_INCREMENT,
    registration_date DATETIME     DEFAULT CURRENT_TIMESTAMP,
    user_email        VARCHAR(255) NOT NULL UNIQUE,
    user_name         VARCHAR(100) NOT NULL,
    user_password     CHAR(60)     NOT NULL,
    user_contacts     VARCHAR(255) NOT NULL,

    INDEX ix_reg_date (registration_date),
    INDEX ix_user_email (user_email),
    INDEX ix_user_name (user_name)
);