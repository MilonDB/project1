CREATE DATABASE IF NOT EXISTS project1;

/* Stelt aan om de volgende statements uit te voeren op database project1 */
USE project1;

/* Maak tabel account aan, voegt daar de nodige kolommen toe en stelt als primary key ID in, die we later gaan gebruiken bij tabel Persoon. */
CREATE TABLE Account(
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);

/* Maak tabel persoon aan, voegt dar de nodige kolommen toe en stelt als foreign key de ID uit tabel account aan, zodat data gekoppeld wordt. */
CREATE TABLE Persoon(
    id INT NOT NULL AUTO_INCREMENT,
    account_id NOT NULL INT,
    username VARCHAR(255) NOT NULL,
    voornaam VARCHAR(255) NOT NULL,
    tussenvoegsel VARCHAR(255),
    achternaam VARCHAR(255) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(account_id) REFERENCES Account(id)
);

INSERT INTO
    account (`id`, `email`, `password`)
VALUES
    (NULL, 'milondb@outlook.com', 'milon1');

INSERT INTO
    persoon(account_id, username, voornaam, achternaam)
VALUES
    ('3', 'milondb', 'milon', 'den boer');