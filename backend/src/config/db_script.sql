CREATE DATABASE IF NOT EXISTS gymbro;
USE gymbro;

CREATE TABLE muscle_groups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE exercises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    muscle_group_id INT NOT NULL,
    FOREIGN KEY (muscle_group_id) REFERENCES muscle_groups(id)
);

INSERT INTO muscle_groups (name) VALUES
('Pecho'),
('Espalda'),
('Piernas'),
('Brazos'),
('Hombros'),
('Abdominales');

INSERT INTO exercises (name, image_url, description, muscle_group_id) VALUES
('Press de banca', 'http://localhost:5173/images/press_banca.png', 'Ejercicio compuesto fundamental para pectoral mayor, deltoides anterior y tríceps. Acostado en el banco, pies firmes en el suelo y escápulas retraídas; baja la barra con control hasta el pecho y empuja hacia arriba manteniendo muñecas rectas, core estable y contacto glúteo–espalda con el banco.', 1);