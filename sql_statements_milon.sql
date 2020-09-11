DROP DATABASE IF EXISTS project1;  /* Verwijdert de database als hij al bestaat. Maak vervolgens een nieuwe database aan. */
CREATE DATABASE project1;

USE project1; /* Stelt aan om de volgende statements uit te voeren op database project1 */ 

CREATE TABLE Account( /* Maak tabel account aan, voegt daar de nodige kolommen toe en stelt als primary key ID in, die we later gaan gebruiken bij tabel Persoon. */ 

    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    PRIMARY KEY(id)

);

CREATE TABLE Persoon( /* Maak tabel persoon aan, voegt dar de nodige kolommen toe en stelt als foreign key de ID uit tabel account aan, zodat data gekoppeld wordt. */ 

    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255),
    voornaam VARCHAR(255),
    tussenvoegsel VARCHAR(255),
    achternaam VARCHAR(255),
	FOREIGN KEY(id) REFERENCES Account(id)
);