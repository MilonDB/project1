CREATE DATABASE IF NOT EXISTS project1;

/* Stelt aan om de volgende statements uit te voeren op database project1 */
USE project1;

/* Maak tabel account aan, voegt daar de nodige kolommen toe en stelt als primary key ID in, die we later gaan gebruiken bij tabel Persoon. */
CREATE TABLE Account(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    PRIMARY KEY(id)
);

/* Maak tabel persoon aan, voegt dar de nodige kolommen toe en stelt als foreign key de ID uit tabel account aan, zodat data gekoppeld wordt. */
CREATE TABLE Persoon(
    id INT NOT NULL AUTO_INCREMENT,
    username NOT NULL VARCHAR(255),
    voornaam NOT NULL VARCHAR(255),
    tussenvoegsel VARCHAR(255),
    achternaam NOT NULL VARCHAR(255),
    PRIMARY KEY(id),
    FOREIGN KEY(id) REFERENCES Account(id)
);