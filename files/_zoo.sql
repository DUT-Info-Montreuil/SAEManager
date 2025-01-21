/* 
******************************
** BD ZOO (2024-2025)        *
** *************************** 
*/

drop schema if exists zoo cascade ;
create schema zoo;

SET search_path TO zoo, public;

drop table if exists gardiencage cascade;
drop table if exists maladiesanimaux cascade; 
drop table if exists animal cascade;
drop table if exists maladie cascade;
drop table if exists cage cascade;
drop table if exists gardien cascade;

create table cage (numCage int, lieu varchar(32), categorie varchar(32), nbMaxAnimal int,
constraint pk_cage primary key (numCage) );

create table gardien (numGardien int, nom varchar(32), adresse varchar(64), datArriv date,
constraint pk_gardien primary key (numGardien) );

create table maladie (nomMaladie varchar(32), description text, 
	symptome1 varchar(32), symptome2 varchar(32), symptome3 varchar(32),
constraint pk_maladie primary key (nomMaladie) );

-- sexe => 't' pour femelle et 'f' pour male
create table animal (numAnimal int, nomAnimal varchar(32), espece varchar(32), 
	sexe boolean, dateNaissance date, pays varchar(32), numCage int,
constraint pk_animal primary key (numAnimal),
constraint fk_animal_cage foreign key (numCage) references cage (numCage) );	 

create table maladiesanimaux (numAnimal int, nomMaladie varchar(32), 
	datedebut date, description text,
constraint pk_maladiesAnimaux primary key (numAnimal, nomMaladie),
constraint fk_maladiesAnimaux_animal foreign key (numAnimal) references animal (numAnimal),
constraint fk_maladiesAnimaux_maladie foreign key (nomMaladie) references maladie (nomMaladie) );	 

create table gardercage (numGardien int, numCage int, 
constraint pk_garderCage primary key (numGardien, numCage), 
constraint fk_garderCage_animal foreign key (numGardien) references gardien (numGardien), 
constraint fk_garderCage_maladie foreign key (numCage) references cage (numCage)); 

/* ******** INSERTION ******** */
-- INSERT CAGE
insert into cage values (1, 'Sud1', null, 10),  (10, 'Sud2', null, 10), 
(2, 'Est', null, null), (3, 'Nord', 'Pôle', 50), (4, 'Ouest', 'volière', 50);

-- INSERT GARDIEN
insert into gardien values (85, 'Martin', 'Paris', '2018-01-01');

-- INSERT ANIMAL
insert into animal values (10, 'Kalanag', 'Eléphant', 't', '2010-10-25', 'Kenya', 1), 
(11, 'Toomai', 'Eléphant', 'f', '2017-12-25', 'Kenya', 1),
(12, 'Baber', 'Eléphant', 'f', '2000-10-25', 'Kenya', 1), 
(13, 'Dumbo', 'Eléphant', 't', '2010-10-15', 'Kenya', 1), 
(14, 'Tantor', 'Eléphant', 'f', '2000-10-25', 'Tanzanie', 1), 
(15, 'Colonel Hathy', 'Eléphant', 'f', '2000-10-25', 'Inde', 2), 
(20, 'Archimède', 'Chouette', 'f', '2016-12-01', 'France', 4), 
(21, 'Titi', 'Canari', 'f', '2016-10-01', 'USA', 4), 
(22, 'Eurêka', 'Mouette', 't', '2016-11-01', 'GB', 4), 
(23, 'Torondor', 'Aigle', 'f', '2016-09-01', 'Terre du Milieu', 4);

-- INSERT MALADIE
insert into maladie values ('Rage', 
'Gravissime maladie virale transmissible à l''homme par morsure, griffure ou léchage d''une plaie.
Incubation très variable. Le plus souvent sous forme nerveuse : modification du comportement, paralysie ... 
mort en une semaine le plus souvent après le début d''apparition des symptômes nerveux.', null, null, null);

-- INSERT GARDERCAGE
insert into gardercage values (85, 1);

-- FIN SCRIPT


