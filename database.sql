CREATE DATABASE pokemondatabase
    DEFAULT CHARACTER SET utf8
    COLLATE utf8_unicode_ci;

USE pokemondatabase;

CREATE TABLE pokemon_types (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE pokemon (
    id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    weight DECIMAL(5,2) NOT NULL,
    height DECIMAL(3,2) NOT NULL,
    type_id INT NOT NULL,
    evolution_count INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES pokemon_types(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO pokemon_types (type) VALUES
('Normal'), ('Fire'), ('Water'), ('Electric'), ('Grass'),
('Ice'), ('Fighting'), ('Poison'), ('Ground'), ('Flying'),
('Psychic'), ('Bug'), ('Rock'), ('Ghost'), ('Dragon'),
('Dark'), ('Steel'), ('Fairy');

CREATE USER 'pokemonuser'@'localhost' IDENTIFIED BY 'pokemonpassword';

GRANT ALL PRIVILEGES ON pokemondatabase.* TO 'pokemonuser'@'localhost';

FLUSH PRIVILEGES;
