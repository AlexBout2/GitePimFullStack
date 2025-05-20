USE gitePim;
-- Création de la table bungalow
CREATE TABLE bungalow (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeBungalow VARCHAR(4) UNIQUE NOT NULL COMMENT 'Identifiant unique métier (ex: ME01, JA01)',
    typeBungalow ENUM('Mer', 'Jardin') NOT NULL COMMENT 'Type de bungalow (Mer ou Jardin)',
    capacite INT NOT NULL COMMENT 'Capacité du bungalow (2 ou 4 personnes)'
);
-- Insertion des bungalows Mer (tous avec 2 places, de ME01 à ME05)
INSERT INTO bungalow (codeBungalow, typeBungalow, capacite) VALUES 
('ME01', 'Mer', 2),
('ME02', 'Mer', 2),
('ME03', 'Mer', 2),
('ME04', 'Mer', 2),
('ME05', 'Mer', 2);

-- Insertion des bungalows Jardin (tous avec 4 places, de JA01 à JA10)
INSERT INTO bungalow (codeBungalow, typeBungalow, capacite) VALUES 
('JA01', 'Jardin', 4),
('JA02', 'Jardin', 4),
('JA03', 'Jardin', 4),
('JA04', 'Jardin', 4),
('JA05', 'Jardin', 4),
('JA06', 'Jardin', 4),
('JA07', 'Jardin', 4),
('JA08', 'Jardin', 4),
('JA09', 'Jardin', 4),
('JA10', 'Jardin', 4);


-- Création de la table sejour
CREATE TABLE sejour (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeResaSejour VARCHAR(10) UNIQUE NOT NULL COMMENT 'Format: CHYYMM000x - identifiant unique métier',
    bungalowId INT NOT NULL COMMENT 'Référence au bungalow associé',
    startDate DATE NOT NULL COMMENT 'Date de début du séjour',
    endDate DATE NOT NULL COMMENT 'Date de fin du séjour',
    nbrPersonnes INT NOT NULL COMMENT 'Nombre de personnes pour ce séjour',
    FOREIGN KEY (bungalowId) REFERENCES bungalow(id) ON DELETE RESTRICT,
    CONSTRAINT chk_dates CHECK (endDate > startDate),
    CONSTRAINT chk_nbrPersonnes CHECK (nbrPersonnes > 0)
);

-- Création de la table restaurant
CREATE TABLE restaurant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeResaRestaurant VARCHAR(10) UNIQUE,
    dateReservation DATE,
    typeService ENUM('Déjeuner', 'Dîner'),
    heureService ENUM('12:00', '12:30', '13:00', '19:00', '19:30', '20:00'),
    nbPersonnes INT,
    sejourId INT,
    FOREIGN KEY (sejourId) REFERENCES sejour(id)
);

-- Création de la table randocheval
CREATE TABLE randocheval (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeResaCheval VARCHAR(10) UNIQUE NOT NULL,
    nomCheval VARCHAR(50) NOT NULL,
    dateCheval DATE NOT NULL,
    periodeCheval ENUM('Matin', 'Après-midi') NOT NULL,
    sejourId INT NOT NULL,
    FOREIGN KEY (sejourId) REFERENCES sejour(id),
    CONSTRAINT unique_cheval_dispo UNIQUE(nomCheval, dateCheval, periodeCheval)
);

-- Création de la table kayak
CREATE TABLE kayak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeResaKayak VARCHAR(11) UNIQUE NOT NULL COMMENT 'Format spécifique - identifiant unique métier',
    dateReservation DATE NOT NULL,
    creneauKayak TINYINT NOT NULL CHECK (creneauKayak >= 9 AND creneauKayak <= 16) COMMENT 'Heure de début du créneau (entre 9h et 16h)',
    nbKayakSimple TINYINT NOT NULL CHECK (nbKayakSimple >= 0 AND nbKayakSimple <= 2),
    nbKayakDouble TINYINT NOT NULL CHECK (nbKayakDouble >= 0 AND nbKayakDouble <= 3),
    nbPersonnesTotales TINYINT NOT NULL,
    sejourId INT NOT NULL,
    FOREIGN KEY (sejourId) REFERENCES sejour(id),
    CONSTRAINT check_coherence_personnes CHECK (nbPersonnesTotales = nbKayakSimple + (nbKayakDouble * 2))
);

-- Création de la table bagne
CREATE TABLE bagne (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeResaBagne VARCHAR(10) UNIQUE NOT NULL,
    dateBagne DATE NOT NULL,
    periodeBagne ENUM('Matin', 'Apres-midi') NOT NULL,
    nombreParticipants TINYINT NOT NULL CHECK (nombreParticipants >= 0 AND nombreParticipants <= 10),
    sejourId INT NOT NULL,
    FOREIGN KEY (sejourId) REFERENCES sejour(id)
);

-- Création de la table garderie
CREATE TABLE garderie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codeResaGarderie VARCHAR(20) UNIQUE NOT NULL,
    dateGarderie DATE NOT NULL,
    startGarderie TIME NOT NULL,
    endGarderie TIME NOT NULL,
    nombreEnfants TINYINT NOT NULL CHECK (nombreEnfants > 0 AND nombreEnfants <= 15),
    sejourId INT NOT NULL,
    FOREIGN KEY (sejourId) REFERENCES sejour(id),
    CONSTRAINT check_duree_max CHECK (
        TIMESTAMPDIFF(MINUTE, 
                      TIMESTAMP(dateGarderie, startGarderie), 
                      TIMESTAMP(dateGarderie, endGarderie)) <= 240
    ),
    CONSTRAINT check_heures_valides CHECK (endGarderie > startGarderie)
);

-- Création de la table capaciteactivites
CREATE TABLE capaciteactivites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    typeActivite VARCHAR(50) NOT NULL UNIQUE,
    capaciteUnitaire INT NOT NULL CHECK (capaciteUnitaire > 0),
    nombreUnites INT NOT NULL CHECK (nombreUnites > 0),
    capaciteTotale INT GENERATED ALWAYS AS (capaciteUnitaire * nombreUnites) STORED,
    description TEXT
);

-- Insertion des capacités d'activités
INSERT INTO capaciteactivites (typeActivite, capaciteUnitaire, nombreUnites, description) VALUES
('KayakSimple', 2, 5, 'Capacité du kayak simple (5 kayaks disponibles)'),
('KayakDouble', 3, 2, 'Capacité du kayak double (2 kayaks disponibles)'),
('Cheval', 1, 16, 'Nombre de chevaux disponibles'),
('Bagne', 10, 1, 'Taille maximale d''un groupe pour la visite du bagne'),
('Garderie', 15, 1, 'Nombre maximal d''enfants acceptés en même temps'),
('Restaurant', 30, 1, 'Nombre de couverts disponibles'),
('BungalowMer', 2, 5, 'Bungalow côté mer - 5 unités de 2 places max'),
('BungalowJardin', 4, 10, 'Bungalow côté jardin - 10 unités de 4 places max');
