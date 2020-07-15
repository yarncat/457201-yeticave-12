CREATE DATABASE YetiCave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE YetiCave;

CREATE TABLE Categories (
    PRIMARY KEY (id),
    id       INTEGER     NOT NULL AUTO_INCREMENT,
    code     VARCHAR(30) NOT NULL UNIQUE,
    category VARCHAR(60) NOT NULL UNIQUE
);

CREATE TABLE Lots (
    PRIMARY KEY (id),
    id          INTEGER       NOT NULL AUTO_INCREMENT,
    lot_name    VARCHAR(100)  NOT NULL,
    create_date DATETIME      DEFAULT CURRENT_TIMESTAMP,
    lot_info    VARCHAR(255),
    image_link  VARCHAR(255),
    start_price INTEGER       DEFAULT 0,
    final_date 	DATETIME      NOT NULL,
    step_rate   INTEGER       DEFAULT 1,
    author      INTEGER       NOT NULL,
    winner      INTEGER,
    cat_code    INTEGER       NOT NULL,

    FOREIGN KEY (author)   REFERENCES Users(id),
    FOREIGN KEY (winner)   REFERENCES Users(id),
    FOREIGN KEY (cat_code) REFERENCES Categories(id),

    FULLTEXT KEY flt_ix_lot_name_info (lot_name, lot_info),

    INDEX ix_lot_name (lot_name),
    INDEX ix_create_date (create_date),
    INDEX ix_start_price (start_price),
    INDEX ix_final_date (final_date),
    INDEX ix_author (author),
    INDEX ix_winner (winner)
);

CREATE TABLE Rates (
    PRIMARY KEY (id),
    id        INTEGER  NOT NULL AUTO_INCREMENT,
    lot_id    INTEGER  NOT NULL,
    user_id   INTEGER  NOT NULL,
    rate      INTEGER  NOT NULL,
    date_rate DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES Users(id),
    FOREIGN KEY (lot_id)  REFERENCES Lots(id),

    INDEX ix_rate (rate),
    INDEX ix_date_rate (date_rate)
);

CREATE TABLE Users (
    PRIMARY KEY (id),
    id                INTEGER      NOT NULL AUTO_INCREMENT,
    registration_date DATETIME     DEFAULT CURRENT_TIMESTAMP,
    user_email        VARCHAR(255) NOT NULL UNIQUE,
    user_name         VARCHAR(100) NOT NULL,
    user_password     CHAR(60)     NOT NULL,
    user_contacts     VARCHAR(255) NOT NULL,

    INDEX ix_reg_date (registration_date),
    INDEX ix_user_email (user_email),
    INDEX ix_user_name (user_name)
);
