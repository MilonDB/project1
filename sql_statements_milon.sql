CREATE DATABASE IF NOT EXISTS project1;

/* Stelt aan om de volgende statements uit te voeren op database project1 */
USE project1;

/* Maak tabel account aan, voegt daar de nodige kolommen toe en stelt als primary key ID in, die we later gaan gebruiken bij tabel Persoon. */
CREATE TABLE Account(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    type_id INT,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    v PRIMARY KEY(id),
    FOREIGN KEY(type_id) REFERENCES usertype(id)
);

/* Maak tabel persoon aan, voegt dar de nodige kolommen toe en stelt als foreign key de ID uit tabel account aan, zodat data gekoppeld wordt. */
CREATE TABLE Persoon(
    id INT NOT NULL AUTO_INCREMENT,
    account_id NOT NULL INT,
    username VARCHAR(255) NOT NULL,
    voornaam VARCHAR(255) NOT NULL,
    tussenvoegsel VARCHAR(255),
    achternaam VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id),
    FOREIGN KEY(account_id) REFERENCES Account(id)
);

/* Maak tabel usertype aan. */
CREATE TABLE usertype(
    id INT NOT NULL AUTO_INCREMENT,
    type VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(id)
);

INSERT INTO
    Account('id', 'email', 'username', 'type_id', 'password')
VALUES
    (
        '1',
        'milondb@outlook.com',
        'admin',
        '1',
        'welkom1'
    );