-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 21 déc. 2024 à 16:22
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mydb`
--

-- --------------------------------------------------------

--
-- Structure de la table `champs`
--

DROP TABLE IF EXISTS `champs`;
CREATE TABLE IF NOT EXISTS `champs` (
  `idChamps` int(11) NOT NULL,
  `nomchamp` varchar(100) NOT NULL,
  `idSAE` int(11) NOT NULL,
  PRIMARY KEY (`idChamps`),
  KEY `idSAE_idx` (`idSAE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `champs`
--

INSERT INTO `champs` (`idChamps`, `nomchamp`, `idSAE`) VALUES
(1, 'Champ de recherche', 1),
(2, 'Champ de description', 2);

-- --------------------------------------------------------

--
-- Structure de la table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `idDoc` int(11) NOT NULL,
  `fichier` varchar(100) NOT NULL,
  `dateDepot` datetime NOT NULL,
  `couleur` varchar(45) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  PRIMARY KEY (`idDoc`),
  KEY `idAuteur_idx` (`idAuteur`),
  KEY `idGroupe_idx` (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `EtudiantGroupe`
--

DROP TABLE IF EXISTS `EtudiantGroupe`;
CREATE TABLE IF NOT EXISTS `EtudiantGroupe` (
  `idGroupe` int(11) NOT NULL,
  `idEtudiant` int(11) NOT NULL,
  PRIMARY KEY (`idGroupe`,`idEtudiant`),
  KEY `idEtudiant_idx` (`idEtudiant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `EtudiantGroupe`
--

INSERT INTO `EtudiantGroupe` (`idGroupe`, `idEtudiant`) VALUES
(1, 1),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `evaluation`
--

DROP TABLE IF EXISTS `evaluation`;
CREATE TABLE IF NOT EXISTS `evaluation` (
  `idEvaluation` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `coeff` double DEFAULT NULL,
  `responsableEvaluation` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEvaluation`),
  KEY `responsableEvaluation_idx` (`responsableEvaluation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `evaluation`
--

INSERT INTO `evaluation` (`idEvaluation`, `nom`, `coeff`, `responsableEvaluation`) VALUES
(1, 'Mathématiques', 3.5, 1),
(2, 'Informatique', 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
CREATE TABLE IF NOT EXISTS `groupe` (
  `idgroupe` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `imageTitre` varchar(100) DEFAULT NULL,
  `idSAE` int(11) DEFAULT NULL,
  PRIMARY KEY (`idgroupe`),
  KEY `idSAE_idx` (`idSAE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `groupe`
--

INSERT INTO `groupe` (`idgroupe`, `nom`, `imageTitre`, `idSAE`) VALUES
(1, 'Groupe A', 'groupeA.jpg', 1),
(2, 'Groupe B', 'groupeB.jpg', 2);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `idMessage` int(11) NOT NULL,
  `idAuteur` int(11) NOT NULL,
  `texte` varchar(1000) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  PRIMARY KEY (`idMessage`),
  KEY `idAuteur_idx` (`idAuteur`),
  KEY `idGroupe_idx` (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `idEval` int(11) NOT NULL,
  `idEleve` int(11) NOT NULL,
  `note` double DEFAULT NULL,
  PRIMARY KEY (`idEval`,`idEleve`),
  KEY `idEleve_idx` (`idEleve`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`idEval`, `idEleve`, `note`) VALUES
(1, 1, 15.5),
(1, 2, 18),
(2, 1, 16),
(2, 3, 14);

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

DROP TABLE IF EXISTS `Personne`;
CREATE TABLE IF NOT EXISTS `Personne` (
  `idPersonne` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `photoDeProfil` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idPersonne`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `Personne`
--

INSERT INTO `Personne` (`idPersonne`, `nom`, `prenom`, `photoDeProfil`) VALUES
(1, 'Dupont', 'Jean', 'jean_dupont.jpg'),
(2, 'Martin', 'Sophie', 'sophie_martin.jpg'),
(3, 'Durand', 'Pierre', 'pierre_durand.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `Rendu`
--

DROP TABLE IF EXISTS `Rendu`;
CREATE TABLE IF NOT EXISTS `Rendu` (
  `idRendu` int(11) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `dateLimite` datetime NOT NULL,
  `idSAE` int(11) NOT NULL,
  `idEvaluation` int(11) DEFAULT NULL,
  PRIMARY KEY (`idRendu`),
  KEY `idSAE_idx` (`idSAE`),
  KEY `idEval_idx` (`idEvaluation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `Rendu`
--

INSERT INTO `Rendu` (`idRendu`, `nom`, `dateLimite`, `idSAE`, `idEvaluation`) VALUES
(1, 'Rendu du projet de développement', '2024-12-30 23:59:59', 1, 1),
(2, 'Rendu de la soutenance de projet', '2025-01-15 23:59:59', 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `RenduGroupe`
--

DROP TABLE IF EXISTS `RenduGroupe`;
CREATE TABLE IF NOT EXISTS `RenduGroupe` (
  `idRendu` int(11) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  `fichier` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idRendu`,`idGroupe`),
  KEY `idGroupe_idx` (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `RenduGroupe`
--

INSERT INTO `RenduGroupe` (`idRendu`, `idGroupe`, `fichier`) VALUES
(1, 1, 'groupeA_Rendu.pdf'),
(2, 2, 'groupeB_Rendu.pdf');

-- --------------------------------------------------------

--
-- Structure de la table `reponseschamp`
--

DROP TABLE IF EXISTS `reponseschamp`;
CREATE TABLE IF NOT EXISTS `reponseschamp` (
  `idChamp` int(11) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  `reponsesChamps` varchar(10000) DEFAULT NULL,
  PRIMARY KEY (`idChamp`,`idGroupe`),
  KEY `idGroupe_idx` (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `reponseschamp`
--

INSERT INTO `reponseschamp` (`idChamp`, `idGroupe`, `reponsesChamps`) VALUES
(1, 1, 'Réponse au champ de recherche'),
(2, 2, 'Réponse au champ de description');

-- --------------------------------------------------------

--
-- Structure de la table `ressource`
--

DROP TABLE IF EXISTS `ressource`;
CREATE TABLE IF NOT EXISTS `ressource` (
  `idRessource` int(11) NOT NULL,
  `contenu` varchar(100) NOT NULL,
  `couleur` varchar(45) NOT NULL,
  PRIMARY KEY (`idRessource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ressource`
--

INSERT INTO `ressource` (`idRessource`, `contenu`, `couleur`) VALUES
(1, 'Document sur les algorithmes', 'bleu'),
(2, 'Cours de programmation Java', 'vert');

-- --------------------------------------------------------

--
-- Structure de la table `ressourcessae`
--

DROP TABLE IF EXISTS `ressourcessae`;
CREATE TABLE IF NOT EXISTS `ressourcessae` (
  `idSAE` int(11) NOT NULL,
  `idRessource` int(11) NOT NULL,
  `misEnAvant` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idSAE`,`idRessource`),
  KEY `idRessource_idx` (`idRessource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ressourcessae`
--

INSERT INTO `ressourcessae` (`idSAE`, `idRessource`, `misEnAvant`) VALUES
(1, 1, 1),
(2, 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `ressourcestheme`
--

DROP TABLE IF EXISTS `ressourcestheme`;
CREATE TABLE IF NOT EXISTS `ressourcestheme` (
  `idTheme` int(11) NOT NULL,
  `idRessource` int(11) NOT NULL,
  PRIMARY KEY (`idTheme`,`idRessource`),
  KEY `idRessource_idx` (`idRessource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `ressourcestheme`
--

INSERT INTO `ressourcestheme` (`idTheme`, `idRessource`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `sae`
--

DROP TABLE IF EXISTS `sae`;
CREATE TABLE IF NOT EXISTS `sae` (
  `idSAE` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `anneeUniversitaire` year(4) NOT NULL,
  `semestreUniversitaire` int(11) NOT NULL,
  `sujet` varchar(1000) NOT NULL,
  `dateModificationSujet` datetime DEFAULT NULL,
  `idResponsable` int(11) NOT NULL,
  PRIMARY KEY (`idSAE`),
  KEY `idResponsable_idx` (`idResponsable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `sae`
--

INSERT INTO `sae` (`idSAE`, `nom`, `anneeUniversitaire`, `semestreUniversitaire`, `sujet`, `dateModificationSujet`, `idResponsable`) VALUES
(1, 'Développement d\'une application web', '2024', 1, 'Développement d\'une application web', '2024-12-10 12:30:00', 2),
(2, 'Gestion de projet', '2024', 2, 'Gestion de projet', '2024-12-15 14:00:00', 3);

-- --------------------------------------------------------

--
-- Structure de la table `soutenance`
--

DROP TABLE IF EXISTS `soutenance`;
CREATE TABLE IF NOT EXISTS `soutenance` (
  `idSoutenance` int(11) NOT NULL,
  `duréeMinutes` int(11) NOT NULL,
  `titre` varchar(45) NOT NULL,
  `date` date DEFAULT NULL,
  `salle` varchar(50) DEFAULT NULL,
  `idSAE` int(11) NOT NULL,
  `idEvaluation` int(11) DEFAULT NULL,
  PRIMARY KEY (`idSoutenance`),
  KEY `idSAE_idx` (`idSAE`),
  KEY `idEval_idx` (`idEvaluation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `soutenance`
--

INSERT INTO `soutenance` (`idSoutenance`, `duréeMinutes`, `titre`, `date`, `salle`, `idSAE`, `idEvaluation`) VALUES
(1, 30, 'Soutenance de l\'application web', '2024-12-30', 'A1-01', 1, 1),
(2, 45, 'Soutenance du projet de gestion de projet', '2023-12-15', 'B1-10', 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `supportsoutenance`
--

DROP TABLE IF EXISTS `supportsoutenance`;
CREATE TABLE IF NOT EXISTS `supportsoutenance` (
  `idSoutenance` int(11) NOT NULL,
  `idGroupe` int(11) NOT NULL,
  `support` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idSoutenance`,`idGroupe`),
  KEY `idGroupe_idx` (`idGroupe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `supportsoutenance`
--

INSERT INTO `supportsoutenance` (`idSoutenance`, `idGroupe`, `support`) VALUES
(1, 1, 'soutenanceA.ppt'),
(2, 2, 'soutenanceB.ppt');

-- --------------------------------------------------------

--
-- Structure de la table `theme`
--

DROP TABLE IF EXISTS `theme`;
CREATE TABLE IF NOT EXISTS `theme` (
  `idTheme` int(11) NOT NULL,
  `titre` varchar(45) NOT NULL,
  `couleur` varchar(45) NOT NULL,
  PRIMARY KEY (`idTheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `theme`
--

INSERT INTO `theme` (`idTheme`, `titre`, `couleur`) VALUES
(1, 'Mathématiques', 'rouge'),
(2, 'Informatique', 'jaune');

-- --------------------------------------------------------

--
-- Structure de la table `themessae`
--

DROP TABLE IF EXISTS `themessae`;
CREATE TABLE IF NOT EXISTS `themessae` (
  `idSAE` int(11) NOT NULL,
  `idTheme` int(11) NOT NULL,
  `misEnAvant` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`idSAE`,`idTheme`),
  KEY `idTheme_idx` (`idTheme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `themessae`
--

INSERT INTO `themessae` (`idSAE`, `idTheme`, `misEnAvant`) VALUES
(1, 1, 1),
(2, 2, 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `champs`
--
ALTER TABLE `champs`
  ADD CONSTRAINT `idSAE` FOREIGN KEY (`idSAE`) REFERENCES `sae` (`idSAE`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `fk_Document_Auteur` FOREIGN KEY (`idAuteur`) REFERENCES `Personne` (`idPersonne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Document_Groupe` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `EtudiantGroupe`
--
ALTER TABLE `EtudiantGroupe`
  ADD CONSTRAINT `fk_EtudiantGroupe_Etudiant` FOREIGN KEY (`idEtudiant`) REFERENCES `Personne` (`idPersonne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_EtudiantGroupe_Groupe` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `evaluation`
--
ALTER TABLE `evaluation`
  ADD CONSTRAINT `responsableEvaluation` FOREIGN KEY (`responsableEvaluation`) REFERENCES `Personne` (`idPersonne`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD CONSTRAINT `fk_groupe_SAE` FOREIGN KEY (`idSAE`) REFERENCES `sae` (`idSAE`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_Message_Auteur` FOREIGN KEY (`idAuteur`) REFERENCES `Personne` (`idPersonne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Message_Groupe` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `idEleve` FOREIGN KEY (`idEleve`) REFERENCES `Personne` (`idPersonne`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `idEval` FOREIGN KEY (`idEval`) REFERENCES `evaluation` (`idEvaluation`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `Rendu`
--
ALTER TABLE `Rendu`
  ADD CONSTRAINT `fk_Rendu_Evaluation` FOREIGN KEY (`idEvaluation`) REFERENCES `evaluation` (`idEvaluation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Rendu_SAE` FOREIGN KEY (`idSAE`) REFERENCES `sae` (`idSAE`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `RenduGroupe`
--
ALTER TABLE `RenduGroupe`
  ADD CONSTRAINT `fk_RenduGroupe_Groupe` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RenduGroupe_Rendu` FOREIGN KEY (`idRendu`) REFERENCES `Rendu` (`idRendu`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `reponseschamp`
--
ALTER TABLE `reponseschamp`
  ADD CONSTRAINT `idChamp` FOREIGN KEY (`idChamp`) REFERENCES `champs` (`idChamps`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `idGroupe` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ressourcessae`
--
ALTER TABLE `ressourcessae`
  ADD CONSTRAINT `fk_RessourcesSAE_Ressource` FOREIGN KEY (`idRessource`) REFERENCES `ressource` (`idRessource`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_RessourcesSAE_SAE` FOREIGN KEY (`idSAE`) REFERENCES `sae` (`idSAE`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `ressourcestheme`
--
ALTER TABLE `ressourcestheme`
  ADD CONSTRAINT `idRessource` FOREIGN KEY (`idRessource`) REFERENCES `ressource` (`idRessource`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `idTheme` FOREIGN KEY (`idTheme`) REFERENCES `theme` (`idTheme`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `sae`
--
ALTER TABLE `sae`
  ADD CONSTRAINT `idResponsable` FOREIGN KEY (`idResponsable`) REFERENCES `Personne` (`idPersonne`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `soutenance`
--
ALTER TABLE `soutenance`
  ADD CONSTRAINT `fk_Soutenance_Evaluation` FOREIGN KEY (`idEvaluation`) REFERENCES `evaluation` (`idEvaluation`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Soutenance_SAE` FOREIGN KEY (`idSAE`) REFERENCES `sae` (`idSAE`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `supportsoutenance`
--
ALTER TABLE `supportsoutenance`
  ADD CONSTRAINT `fk_SupportSoutenance_Groupe` FOREIGN KEY (`idGroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_SupportSoutenance_Soutenance` FOREIGN KEY (`idSoutenance`) REFERENCES `soutenance` (`idSoutenance`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `themessae`
--
ALTER TABLE `themessae`
  ADD CONSTRAINT `fk_ThemesSAE_SAE` FOREIGN KEY (`idSAE`) REFERENCES `sae` (`idSAE`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ThemesSAE_Theme` FOREIGN KEY (`idTheme`) REFERENCES `theme` (`idTheme`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
