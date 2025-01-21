
-- Fabrice CANNAN / TP APP
-- TP1 06/09/2024

-- Fonction

-- 1.1)

CREATE OR REPLACE FUNCTION pjeune() RETURNS int AS $$
DECLARE
    ageMin int;
BEGIN
    SELECT min(age) INTO ageMin
    FROM vac.Pers;

    return ageMin;
END; $$language plpgsql;

-- 1.2)

CREATE OR REPLACE FUNCTION pjeune2(OUT minAge int) AS $$
BEGIN
    SELECT min(age) INTO minAge
    FROM vac.Pers;
END; $$language plpgsql;


-- 2.1)

CREATE OR REPLACE FUNCTION netoile(id int) RETURNS int AS $$
DECLARE
    nbEtoile int;
BEGIN
    SELECT etoile INTO nbEtoile
    FROM vac.Club
    WHERE idClub = id;

    return nbEtoile;
END; $$language plpgsql;

-- 2.2)

CREATE OR REPLACE FUNCTION netoile2(id int, OUT nbEtoile int) AS $$
BEGIN
    SELECT etoile INTO nbEtoile
    FROM vac.Club
    WHERE idClub = id;
END; $$language plpgsql;

-- 3.1)

CREATE OR REPLACE FUNCTION captotale(nomVille VARCHAR) RETURNS int AS $$
DECLARE
    maxCapacite int;
BEGIN
    SELECT sum(capacite) INTO maxCapacite
    FROM vac.Club
    WHERE ville = nomVille;

    return maxCapacite;
END; $$language plpgsql;

-- 3.2)

CREATE OR REPLACE FUNCTION captotale2(nomVille VARCHAR, OUT maxCapacite int) AS $$
BEGIN
    SELECT sum(capacite) INTO maxCapacite
    FROM vac.Club
    WHERE ville = nomVille;
END; $$language plpgsql;

-- 4.1)

CREATE OR REPLACE FUNCTION areserve(id int) RETURNS BOOL AS $$
DECLARE
    reserv BOOL;
	nb int;
BEGIN
	SELECT idGrp INTO nb
	FROM vac.Resa
	WHERE idGrp = id;
	 
    IF (nb > 0) THEN
        reserv = 1;
    ELSE 
        reserv = 0;
    
    END IF;

    return reserv;

END; $$language plpgsql;

-- 4.2)


CREATE OR REPLACE FUNCTION areserve2(id int, OUT reserv bool) AS $$
DECLARE
	nb int;
BEGIN
    SELECT idGrp INTO nb
    FROM vac.Resa
    WHERE idGrp = id;

    IF (nb > 0) THEN
        reserv = 1;
    ELSE 
        reserv = 0;
    
    END IF;

END; $$language plpgsql;

-- 5.1)

CREATE OR REPLACE FUNCTION actpratiquee(id int, month VARCHAR) RETURNS VARCHAR AS $$
DECLARE
    act VARCHAR;
BEGIN
    SELECT activite INTO act
    FROM vac.Groupe
    NATURAL JOIN vac.Resa
    WHERE idClub = id AND mois = month;

    IF (NOT FOUND) THEN
        return 'Pas dactivite';
    ELSE
        return act;
    END IF;

    
END; $$language plpgsql;

-- 5.2)

CREATE OR REPLACE FUNCTION actpratiquee2(id int, month VARCHAR, OUT act VARCHAR) AS $$
BEGIN
    SELECT activite INTO act
    FROM vac.Groupe
    NATURAL JOIN vac.Resa
    WHERE idClub = id AND mois = month;

    IF (NOT FOUND) THEN
        act = 'Pas dactivite';
    END IF;

END; $$language plpgsql;



-- 6.1)

CREATE OR REPLACE FUNCTION idplussun() RETURNS int AS $$
DECLARE
    idPlus int;
BEGIN
    SELECT max(idPers) INTO idPlus
    FROM vac.Pers;

    idPlus = idPlus + 1;
    return idPlus;
END; $$language plpgsql;

-- 6.2)

CREATE OR REPLACE FUNCTION idplussun2(OUT idPlus int) AS $$
BEGIN
    SELECT max(idPers) INTO idPlus
    FROM vac.Pers;

    idPlus = idPlus + 1;
END; $$language plpgsql;


-- Procédure

-- 1)

CREATE OR REPLACE PROCEDURE inserpers(nom VARCHAR, age int, ville VARCHAR DEFAULT 'Paris') AS $$
DECLARE
    id int;
BEGIN
    SELECT idplussun() INTO id;
    INSERT INTO vac.Pers VALUES (id, nom, age, ville);
END; $$language plpgsql;

-- 2)

CREATE OR REPLACE PROCEDURE supclub(id int) AS $$
BEGIN
    
    DELETE FROM vac.Resa
    WHERE idClub = id;
	
	DELETE FROM vac.Club
    WHERE idClub = id;

END; $$language plpgsql

-- 3)

CREATE OR REPLACE PROCEDURE evalclub(id int) AS $$
DECLARE
    nb int;
    nbEtoile int;
BEGIN
    SELECT count(*) INTO nb
    FROM vac.Resa
    WHERE idClub = id;

    IF (nb < 1) THEN
        SELECT etoile INTO nbEtoile
        FROM vac.Club
        WHERE idClub = id;

        UPDATE vac.Club
        SET etoile = (nbEtoile - 1)
        WHERE idClub = id;
    END IF;

END; $$language plpgsql;

-- 4)

CREATE OR REPLACE PROCEDURE verifage(id int) AS $$
DECLARE
    agePers int;
    grp int;
    nb int;
BEGIN

    SELECT age INTO agePers
    FROM vac.Pers
    WHERE idPers = id;

    SELECT idGrp INTO grp
    FROM vac.Pers
    NATURAL JOIN vac.GroupePers
    WHERE idPers = id;

    IF (agePers < 18) THEN 

        DELETE FROM vac.GroupePers
        WHERE idPers = id;
    END IF;

    SELECT count(*) INTO nb
    FROM vac.GroupePers
    WHERE idGrp = grp;

    IF (nb < 1) THEN

        DELETE FROM vac.Resa
        WHERE idGrp = grp;
    END IF;

END; $$language plpgsql;