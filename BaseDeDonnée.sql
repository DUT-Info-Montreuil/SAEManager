--//Tout droit réservée
--//All right reserved
--//Créer par Vincent MATIAS, Thomas GOMES, Arthur HUGUET et Fabrice CANNAN


-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+focal2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 28 jan. 2025 à 19:44
-- Version du serveur : 8.0.40-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3-4ubuntu2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dutinfopw201628`
--

-- --------------------------------------------------------

--
-- Structure de la table `Champs`
--

CREATE TABLE `Champs` (
  `idChamps` int NOT NULL,
  `nomchamp` varchar(100) NOT NULL,
  `idSAE` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Champs`
--

INSERT INTO `Champs` (`idChamps`, `nomchamp`, `idSAE`) VALUES
(28, 'remplir github', 33),
(29, 'github', 35),
(30, 'github', 36);

-- --------------------------------------------------------

--
-- Structure de la table `Document`
--

CREATE TABLE `Document` (
  `idDoc` int NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `fichier` varchar(100) NOT NULL,
  `dateDepot` datetime NOT NULL,
  `couleur` varchar(45) NOT NULL,
  `idAuteur` int NOT NULL,
  `idGroupe` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Document`
--

INSERT INTO `Document` (`idDoc`, `Nom`, `fichier`, `dateDepot`, `couleur`, `idAuteur`, `idGroupe`) VALUES
(22, 'dutinfopw201628 (6).sql', '67938b871aeda-dutinfopw201628 (6).sql', '2025-01-24 12:45:58', 'none', 38, 71);

-- --------------------------------------------------------

--
-- Structure de la table `EleveInscritSae`
--

CREATE TABLE `EleveInscritSae` (
  `idSAE` int NOT NULL,
  `idEleve` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `EleveInscritSae`
--

INSERT INTO `EleveInscritSae` (`idSAE`, `idEleve`) VALUES
(22, 1),
(22, 37),
(22, 38),
(22, 39),
(22, 40),
(22, 41),
(22, 42),
(33, 1),
(33, 37),
(33, 38),
(33, 39),
(33, 40),
(33, 41),
(33, 42),
(34, 1),
(34, 37),
(34, 38),
(34, 39),
(34, 40),
(34, 41),
(34, 42),
(35, 1),
(35, 37),
(35, 41),
(35, 42),
(36, 1),
(36, 37),
(36, 39),
(36, 40),
(36, 41),
(36, 42);

-- --------------------------------------------------------

--
-- Structure de la table `EtudiantGroupe`
--

CREATE TABLE `EtudiantGroupe` (
  `idGroupe` int NOT NULL,
  `idEtudiant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `EtudiantGroupe`
--

INSERT INTO `EtudiantGroupe` (`idGroupe`, `idEtudiant`) VALUES
(75, 1),
(78, 1),
(81, 1),
(75, 37),
(78, 37),
(81, 37),
(71, 38),
(73, 38),
(76, 38),
(71, 39),
(73, 39),
(76, 39),
(82, 39),
(72, 40),
(76, 40),
(80, 40),
(77, 41),
(80, 41),
(72, 42),
(77, 42),
(79, 42);

-- --------------------------------------------------------

--
-- Structure de la table `Evaluation`
--

CREATE TABLE `Evaluation` (
  `idEval` int NOT NULL,
  `nom` varchar(255) NOT NULL,
  `coef` decimal(5,2) NOT NULL,
  `IntervenantEvaluateur` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Evaluation`
--

INSERT INTO `Evaluation` (`idEval`, `nom`, `coef`, `IntervenantEvaluateur`) VALUES
(71, 'tp1', 1.00, NULL),
(72, 'tp2', 1.00, NULL),
(73, 'soutenance', 1.00, NULL),
(74, 'Anglais', 1.00, NULL),
(75, 'Code', 1.00, NULL),
(76, 'Code', 1.00, NULL),
(77, 'Rendu 1', 1.00, NULL),
(78, 'rendu 2', 1.00, NULL),
(79, 'Archi', 6.00, NULL),
(80, 'Soutenance sae', 1.00, NULL),
(81, 'tp1', 3.00, NULL),
(82, 'Soutenance sae', 1.00, NULL),
(83, 'tp2', 2.00, NULL),
(84, 'nom à définir3', 1.00, NULL),
(85, 'nom à définir2', 3.00, NULL),
(86, 'nom à définir', 1.00, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Groupe`
--

CREATE TABLE `Groupe` (
  `idgroupe` int NOT NULL,
  `nom` varchar(45) NOT NULL,
  `imageTitre` varchar(100) DEFAULT NULL,
  `idSAE` int DEFAULT NULL,
  `estModifiableParEleve` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Groupe`
--

INSERT INTO `Groupe` (`idgroupe`, `nom`, `imageTitre`, `idSAE`, `estModifiableParEleve`) VALUES
(71, 'modif groupe1', '67938b7b806bb-LOGO500_2024.png', 33, 1),
(72, 'groupe2', '67926371864af-groupeImage.png', 33, 1),
(73, 'Groupe 3', '67926371864af-groupeImage.png', 34, 0),
(75, 'grp 1', '67926371864af-groupeImage.png', 22, 0),
(76, 'grp 2', '67926371864af-groupeImage.png', 22, 0),
(77, 'grp 3', '67926371864af-groupeImage.png', 22, 0),
(78, 'LesMeilleurs', '67926371864af-groupeImage.png', 35, 1),
(79, 'groupe1', '67926371864af-groupeImage.png', 36, 1),
(80, 'groupe2', '67926371864af-groupeImage.png', 36, 0),
(81, 'grope3', '67926371864af-groupeImage.png', 36, 1),
(82, 'thomasGroupe', '67926371864af-groupeImage.png', 36, 1);

-- --------------------------------------------------------

--
-- Structure de la table `IntervenantSAE`
--

CREATE TABLE `IntervenantSAE` (
  `idSAE` int NOT NULL,
  `idIntervenant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `JurySoutenance`
--

CREATE TABLE `JurySoutenance` (
  `idSoutenance` int NOT NULL,
  `idPersonne` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `JurySoutenance`
--

INSERT INTO `JurySoutenance` (`idSoutenance`, `idPersonne`) VALUES
(38, 2),
(39, 2),
(37, 43),
(38, 43),
(39, 43),
(37, 44),
(38, 44);

-- --------------------------------------------------------

--
-- Structure de la table `Notes`
--

CREATE TABLE `Notes` (
  `idEval` int NOT NULL,
  `idEleve` int NOT NULL,
  `idRendu` int NOT NULL,
  `note` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Notes`
--

INSERT INTO `Notes` (`idEval`, `idEleve`, `idRendu`, `note`) VALUES
(74, 1, 87, NULL),
(74, 37, 87, NULL),
(74, 38, 87, 15.00),
(74, 39, 87, 15.00),
(74, 40, 87, 14.00),
(74, 41, 87, NULL),
(74, 42, 87, 16.00),
(75, 1, 87, NULL),
(75, 37, 87, NULL),
(75, 38, 87, NULL),
(75, 39, 87, NULL),
(75, 40, 87, NULL),
(75, 41, 87, NULL),
(75, 42, 87, NULL),
(76, 1, 88, NULL),
(76, 37, 88, NULL),
(76, 38, 88, 4.00),
(76, 39, 88, 4.00),
(76, 40, 88, 14.00),
(76, 41, 88, NULL),
(76, 42, 88, 8.00),
(77, 1, 89, NULL),
(77, 37, 89, NULL),
(77, 38, 89, NULL),
(77, 39, 89, NULL),
(77, 40, 89, NULL),
(77, 41, 89, NULL),
(77, 42, 89, NULL),
(78, 41, 90, NULL),
(78, 42, 90, NULL),
(79, 1, 91, 5.00),
(79, 37, 91, 5.00),
(81, 1, 92, 20.00),
(81, 37, 92, 20.00),
(81, 39, 92, NULL),
(81, 40, 92, 6.00),
(81, 41, 92, 6.00),
(83, 37, 92, 15.00),
(83, 40, 92, 10.00),
(83, 41, 92, 10.00),
(83, 42, 92, 10.00);

-- --------------------------------------------------------

--
-- Structure de la table `NotesSoutenance`
--

CREATE TABLE `NotesSoutenance` (
  `idEval` int NOT NULL,
  `idEleve` int NOT NULL,
  `idSoutenance` int NOT NULL,
  `note` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `NotesSoutenance`
--

INSERT INTO `NotesSoutenance` (`idEval`, `idEleve`, `idSoutenance`, `note`) VALUES
(84, 1, 39, 15.00),
(84, 37, 39, 15.00),
(84, 40, 39, 15.00),
(84, 41, 39, 15.00),
(84, 42, 39, 15.00),
(85, 1, 39, NULL),
(85, 37, 39, NULL),
(85, 39, 39, NULL),
(85, 40, 39, NULL),
(85, 41, 39, NULL),
(85, 42, 39, NULL),
(86, 1, 39, NULL),
(86, 37, 39, NULL),
(86, 39, 39, NULL),
(86, 40, 39, NULL),
(86, 41, 39, NULL),
(86, 42, 39, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `Notifications`
--

CREATE TABLE `Notifications` (
  `idNotification` bigint UNSIGNED NOT NULL,
  `idPersonne` int NOT NULL,
  `message` varchar(200) NOT NULL,
  `idSaeProvenance` int DEFAULT NULL,
  `lienForm` varchar(200) NOT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Notifications`
--

INSERT INTO `Notifications` (`idNotification`, `idPersonne`, `message`, `idSaeProvenance`, `lienForm`, `date`) VALUES
(297, 44, 'Vous avez été assigné co-responsable à une SAE qui vient d\'être créée.', NULL, 'index.php?module=sae&action=home', '2025-01-24 12:41:33'),
(299, 39, 'Votre proposition de groupe pour la sae sae POO a été validé !', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:42:06'),
(300, 40, 'Votre proposition de groupe pour la sae sae POO a été validé !', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:42:25'),
(301, 42, 'Votre proposition de groupe pour la sae sae POO a été validé !', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:42:25'),
(303, 39, 'Un nouveau rendu à été crée dans la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:42:48'),
(304, 40, 'Un nouveau rendu à été crée dans la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:42:48'),
(305, 42, 'Un nouveau rendu à été crée dans la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:42:48'),
(307, 39, 'Un nouveau rendu à été crée dans la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:07'),
(308, 40, 'Un nouveau rendu à été crée dans la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:07'),
(309, 42, 'Un nouveau rendu à été crée dans la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:07'),
(310, 43, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:43'),
(311, 44, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:43'),
(313, 39, 'Une nouvelle soutenance a été ajoutée à la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:43'),
(314, 40, 'Une nouvelle soutenance a été ajoutée à la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:43'),
(315, 42, 'Une nouvelle soutenance a été ajoutée à la sae sae POO!', 33, 'index.php?module=sae&action=details&id=33', '2025-01-24 12:43:44'),
(316, 44, 'Vous avez été assigné co-responsable à une SAE qui vient d\'être créée.', NULL, 'index.php?module=sae&action=home', '2025-01-24 12:44:11'),
(318, 39, 'Votre proposition de groupe pour la sae sae dev web a été validé !', 34, 'index.php?module=sae&action=details&id=34', '2025-01-24 12:48:03'),
(3278, 45, 'Bonjour monsieur, le site fonctionne bien ?', 24, 'index.php', NULL),
(32181, 43, 'Vous avez été assigné co-responsable à une SAE qui vient d\'être créée.', NULL, 'index.php?module=sae&action=home', '2025-01-28 16:19:02'),
(32182, 44, 'Vous avez été assigné co-responsable à une SAE qui vient d\'être créée.', NULL, 'index.php?module=sae&action=home', '2025-01-28 16:19:02'),
(32183, 1, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:29:32'),
(32184, 37, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:29:32'),
(32185, 1, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:32:08'),
(32186, 37, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:32:08'),
(32187, 38, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:32:34'),
(32188, 39, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:32:34'),
(32189, 40, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:32:34'),
(32190, 1, 'Un nouveau rendu à été crée dans la sae Développement d\'une application web pour la gestion d\'événements!', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:36'),
(32191, 37, 'Un nouveau rendu à été crée dans la sae Développement d\'une application web pour la gestion d\'événements!', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:36'),
(32192, 38, 'Un nouveau rendu à été crée dans la sae Développement d\'une application web pour la gestion d\'événements!', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:36'),
(32193, 39, 'Un nouveau rendu à été crée dans la sae Développement d\'une application web pour la gestion d\'événements!', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:36'),
(32194, 40, 'Un nouveau rendu à été crée dans la sae Développement d\'une application web pour la gestion d\'événements!', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:36'),
(32195, 41, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:54'),
(32196, 42, 'Votre proposition de groupe pour la sae Développement d\'une application web pour la gestion d\'événements a été validé !', 22, 'index.php?module=sae&action=details&id=22', '2025-01-28 17:33:55'),
(32197, 2, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE Sae DROIT RGPD!', 35, 'index.php?module=sae&action=details&id=35', '2025-01-28 17:33:53'),
(32198, 43, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE Sae DROIT RGPD!', 35, 'index.php?module=sae&action=details&id=35', '2025-01-28 17:33:53'),
(32199, 44, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE Sae DROIT RGPD!', 35, 'index.php?module=sae&action=details&id=35', '2025-01-28 17:33:53'),
(32200, 1, 'Votre proposition de groupe pour la sae Sae DROIT RGPD a été validé !', 35, 'index.php?module=sae&action=details&id=35', '2025-01-28 17:34:51'),
(32201, 37, 'Votre proposition de groupe pour la sae Sae DROIT RGPD a été validé !', 35, 'index.php?module=sae&action=details&id=35', '2025-01-28 17:34:51'),
(32202, 43, 'Vous avez été assigné co-responsable à une SAE qui vient d\'être créée.', NULL, 'index.php?module=sae&action=home', '2025-01-28 17:38:53'),
(32203, 44, 'Vous avez été assigné co-responsable à une SAE qui vient d\'être créée.', NULL, 'index.php?module=sae&action=home', '2025-01-28 17:38:53'),
(32204, 42, 'Votre proposition de groupe pour la sae SAE refactoring code dev web des s3 a été validé !', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:17:14'),
(32205, 42, 'Un nouveau rendu à été crée dans la sae SAE refactoring code dev web des s3!', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:17:41'),
(32206, 2, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE SAE refactoring code dev web des s3!', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:18:04'),
(32207, 43, 'Vous avez été ajouté en tant que Jury sur une soutenance de la SAE SAE refactoring code dev web des s3!', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:18:04'),
(32208, 42, 'Une nouvelle soutenance a été ajoutée à la sae SAE refactoring code dev web des s3!', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:18:04'),
(32209, 40, 'Votre proposition de groupe pour la sae SAE refactoring code dev web des s3 a été validé !', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:18:33'),
(32210, 41, 'Votre proposition de groupe pour la sae SAE refactoring code dev web des s3 a été validé !', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:18:33'),
(32211, 1, 'Votre proposition de groupe pour la sae SAE refactoring code dev web des s3 a été validé !', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:23:15'),
(32212, 37, 'Votre proposition de groupe pour la sae SAE refactoring code dev web des s3 a été validé !', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:23:15'),
(32213, 39, 'Votre proposition de groupe pour la sae SAE refactoring code dev web des s3 a été validé !', 36, 'index.php?module=sae&action=details&id=36', '2025-01-28 18:27:51');

-- --------------------------------------------------------

--
-- Structure de la table `PassageSoutenance`
--

CREATE TABLE `PassageSoutenance` (
  `idSoutenance` int NOT NULL,
  `idGroupe` int NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `PassageSoutenance`
--

INSERT INTO `PassageSoutenance` (`idSoutenance`, `idGroupe`, `date`) VALUES
(37, 71, '2025-01-25 11:20:00'),
(37, 72, '2025-01-25 11:40:00');

-- --------------------------------------------------------

--
-- Structure de la table `Personne`
--

CREATE TABLE `Personne` (
  `idPersonne` int NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `photoDeProfil` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `login` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `estProf` tinyint(1) NOT NULL,
  `estAdmin` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Personne`
--

INSERT INTO `Personne` (`idPersonne`, `nom`, `prenom`, `photoDeProfil`, `password`, `login`, `email`, `estProf`, `estAdmin`) VALUES
(1, 'Dupont', 'Jean', '6793576b248bd-One-Piece-logo.jpg', '$2y$10$kRwAR7YJCxhARNWlg5Vx2eDnKVnyE3T4TkiWwwZQYR9/e1FpYkJCu', 'jean.dupont', '', 0, NULL),
(2, 'Martin', 'Sophie', '67979f6d0aab7-w_the-legend-of-zelda-breath-of-the-wild-wallpaper-by-de-monvarela-dav8gp2.jpg', '$2y$10$s1YwMslOxOqTbUJSXhU2AOGMooMs0znYi.ui8crZKbauPTer1ktU6', 'sophie.martin', '', 1, 1),
(37, 'Matias', 'Vincent', '67924ce223934-default-profile.png', '$2y$10$gEtjrvqQvyTClCe8BRpmH.An5zK6Kov/n1eiyhSq.LYdpme.unOdC', 'vincent.matias', 'vincent.matias@iut.univ-paris8.fr', 0, NULL),
(38, 'Cannan', 'Fabrice', '67938d1c2cc24-LOGO500_2024.png', '$2y$10$R9VT4znooc64KyYsB0yvFOfrRTIdRTNdMTmXHSwNC8sHzCVOPfJ0W', 'fabrice.cannan', 'fabrice.cannan@iut.univ-paris8.fr', 0, NULL),
(39, 'Gomes', 'Thomas', '67924ce223934-default-profile.png', '$2y$10$81.FLJAUS14dBZHQj0e8s.7u6Io6PBgAwqNVcqVSniSvAfMk714XS', 'thomas.gomes', 'thomas.gomes@iut.univ-paris8.fr', 0, NULL),
(40, 'Huguet', 'Arthur', '67924ce223934-default-profile.png', '$2y$10$y5HsiHIp3Ticqa8J3ePqCeHyI5d0WwbPtde.IFyxbkW6pev9j8Tum', 'arthur.huguet', 'arthur.huguet@iut.univ-paris8.fr', 0, NULL),
(41, 'Petricevic', 'Nikola', '67924ce223934-default-profile.png', '$2y$10$ppnhNYvJ95ERQn.3ZCofiOknj8trIcbOgvr1coPfdldro1pwaHmOe', 'nikola.petricevic', 'nikola.petricevic@iut.univ-paris8.fr', 0, NULL),
(42, 'Delacroix', 'Lucas', '67924ce223934-default-profile.png', '$2y$10$kbkxDuAmC3NElUoEE6kTKO.cxvmJNaQq3vWtr7rnHWl5LOztm1MU.', 'lucas.delacroix', 'lucas.delacroix@iut.univ-paris8.fr', 0, NULL),
(43, 'Simono', 'Mariane', '6793ea8198cfc-6792644f1fe32-w_the-legend-of-zelda-breath-of-the-wild-wallpaper-by-de-monvarela-dav8gp2.jpg', '$2y$10$ekFl8F64gPaN3uF/ILFh5.MlSMMB2zgURAsxvUZvNc.ZI7BIPfUfS', 'mariane.simonot', 'mariane.simono@iut.univ-paris8.fr', 1, NULL),
(44, 'Comparot', 'Véronique', '67924ce223934-default-profile.png', '$2y$10$Zw2wJnkOyWyXRmupPeCrDufsBb8pXVjMpbNHr15OvuVarRQKhK1n6', 'veronique.comparot', 'veronique.comparot@iut.univ-paris8.fr', 1, NULL),
(45, 'tat', 'tot', '67924ce223934-default-profile.png', '$2y$10$6sxNooSOSJV9RpuQu8LWiO4GvW3abAJr/oSk/qfEgLSNduppUdWky', 'tot.tat', 'tatot@iut.univ-paris8.fr', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `PropositionsEleve`
--

CREATE TABLE `PropositionsEleve` (
  `idProposition` int NOT NULL,
  `idEleve` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `PropositionsGroupe`
--

CREATE TABLE `PropositionsGroupe` (
  `idProposition` int NOT NULL,
  `idSAE` int NOT NULL,
  `nomGroupe` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `edit` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `Rendu`
--

CREATE TABLE `Rendu` (
  `idRendu` int NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `dateLimite` datetime NOT NULL,
  `idSAE` int NOT NULL,
  `idEvaluation` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Rendu`
--

INSERT INTO `Rendu` (`idRendu`, `nom`, `dateLimite`, `idSAE`, `idEvaluation`) VALUES
(87, 'tp1', '2025-01-24 23:42:00', 33, 71),
(88, 'tp2', '2025-01-25 23:42:00', 33, 72),
(89, 'Rendu 1', '2025-01-29 15:05:00', 22, 77),
(90, 'rendu 2', '2025-01-31 04:05:00', 22, 78),
(91, 'Archi', '2025-01-29 18:33:00', 35, 79),
(92, 'tp1', '2025-01-31 19:19:00', 36, 81);

-- --------------------------------------------------------

--
-- Structure de la table `RenduGroupe`
--

CREATE TABLE `RenduGroupe` (
  `idRendu` int NOT NULL,
  `idGroupe` int NOT NULL,
  `fichier` varchar(100) DEFAULT NULL,
  `dateDepot` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `RenduGroupe`
--

INSERT INTO `RenduGroupe` (`idRendu`, `idGroupe`, `fichier`, `dateDepot`) VALUES
(87, 71, '67938b4fee0e7-dutinfopw201628 (6).sql', '2025-01-24 12:45:03');

-- --------------------------------------------------------

--
-- Structure de la table `reponsesChamp`
--

CREATE TABLE `reponsesChamp` (
  `idChamp` int NOT NULL,
  `idEleve` int NOT NULL,
  `reponse` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `reponsesChamp`
--

INSERT INTO `reponsesChamp` (`idChamp`, `idEleve`, `reponse`) VALUES
(28, 38, 'FaabriceC'),
(28, 39, 'Github');

-- --------------------------------------------------------

--
-- Structure de la table `ResponsablesSAE`
--

CREATE TABLE `ResponsablesSAE` (
  `idSAE` int NOT NULL,
  `idResp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `ResponsablesSAE`
--

INSERT INTO `ResponsablesSAE` (`idSAE`, `idResp`) VALUES
(33, 44),
(34, 44),
(35, 43),
(35, 44),
(36, 43),
(36, 44);

-- --------------------------------------------------------

--
-- Structure de la table `Ressource`
--

CREATE TABLE `Ressource` (
  `idRessource` int NOT NULL,
  `contenu` varchar(100) NOT NULL,
  `couleur` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Ressource`
--

INSERT INTO `Ressource` (`idRessource`, `contenu`, `couleur`, `nom`) VALUES
(36, '67938c12e76d7-dutinfopw201628 (6).sql', 'none', 'bdd');

-- --------------------------------------------------------

--
-- Structure de la table `RessourcesSAE`
--

CREATE TABLE `RessourcesSAE` (
  `idSAE` int NOT NULL,
  `idRessource` int NOT NULL,
  `misEnAvant` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `RessourcesSAE`
--

INSERT INTO `RessourcesSAE` (`idSAE`, `idRessource`, `misEnAvant`) VALUES
(22, 36, NULL),
(33, 36, 1),
(34, 36, NULL),
(35, 36, 1),
(36, 36, 1);

-- --------------------------------------------------------

--
-- Structure de la table `SAE`
--

CREATE TABLE `SAE` (
  `idSAE` int NOT NULL,
  `nomSae` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `anneeUniversitaire` year NOT NULL,
  `semestreUniversitaire` int NOT NULL,
  `sujet` varchar(2000) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `dateModificationSujet` datetime NOT NULL,
  `idResponsable` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `SAE`
--

INSERT INTO `SAE` (`idSAE`, `nomSae`, `anneeUniversitaire`, `semestreUniversitaire`, `sujet`, `dateModificationSujet`, `idResponsable`) VALUES
(22, 'Développement d\'une application web pour la gestion d\'événements', '2025', 3, 'Contexte : Les associations et entreprises ont besoin d’un outil simple pour organiser leurs événements (conférences, ateliers, soirées, etc.).\r\nCe projet vise à concevoir une application web intuitive permettant de gérer les événements, d\'inscrire des participants, et de fournir des informations en temps réel.  Objectifs :      Développer une application web responsive en utilisant des technologies modernes comme HTML5, CSS3, JavaScript (ou un framework comme React ou Vue.js).     Intégrer un backend pour la gestion des données (Node.js, PHP, ou Python avec Flask/Django).     Permettre la gestion des utilisateurs (admins, participants) avec des droits différenciés.     Implémenter des fonctionnalités essentielles comme :         Création, modification, et suppression d\'événements.         Gestion des inscriptions et listes de participants.         Notifications (par email ou via l\'application).     Proposer une interface utilisateur agréable et intuitive (design UX/UI).', '2025-01-24 09:55:00', 2),
(23, 'Développement d\'une application de gestion d\'inventaire en C (console)', '2025', 4, 'Contexte : Les petites entreprises ont souvent besoin d\'un outil simple et efficace pour gérer leur inventaire. Ce projet consiste à développer une application console en C permettant de gérer les stocks, les ventes, et les approvisionnements d\'une petite structure.  Objectifs :      Concevoir une application en mode console.     Utiliser des structures, des pointeurs, et des fichiers pour manipuler les données.     Implémenter un menu interactif pour naviguer entre les différentes fonctionnalités.', '2025-01-24 10:02:00', 2),
(24, 'Création d\'un jeu vidéo 2D en Java avec JavaFX', '2025', 4, 'Contexte : Le jeu vidéo est un domaine passionnant pour apprendre la programmation. Ce projet consiste à développer un jeu en 2D avec des interactions simples, des animations, et une boucle de jeu (game loop). Le but est de mettre en pratique des concepts fondamentaux comme les événements, la gestion des graphismes, et la programmation orientée objet.  Objectifs :      Créer un jeu vidéo simple, avec des personnages, des ennemis, et des obstacles.     Utiliser JavaFX pour gérer l’affichage graphique et les interactions avec l’utilisateur.     Implémenter une logique de jeu avec des conditions de victoire ou de défaite.', '2025-01-24 10:14:00', 2),
(25, 'Développement d’un logiciel de gestion de planning en Python (console)', '2025', 4, 'Contexte : Les petites équipes ou indépendants ont souvent besoin d\'un outil simple pour organiser leur planning de tâches ou d\'événements. Ce projet vise à concevoir une application en ligne de commande qui permet de créer, consulter, modifier et supprimer des tâches tout en offrant une vue claire du planning.  Objectifs :      Concevoir un logiciel simple mais fonctionnel pour la gestion des plannings.     Utiliser Python pour mettre en œuvre des concepts tels que la gestion des fichiers, les structures de données, et la programmation orientée objet.     Proposer une interface en ligne de commande intuitive et efficace.', '2025-01-24 10:15:00', 2),
(26, 'Simulation d’un système de gestion de banque en C++ (console)', '2025', 6, 'Contexte : Ce projet consiste à créer un logiciel de simulation pour la gestion des comptes bancaires d’une banque. L’objectif est de permettre l’ajout de clients, la création de comptes, la gestion des transactions (dépôts et retraits), ainsi que la consultation des soldes.  Objectifs :      Mettre en œuvre un système orienté objet en C++ pour gérer des données complexes comme des clients et leurs comptes.     Manipuler les concepts de classes, héritage, polymorphisme, et fichiers pour la persistance des données.     Simuler un système fonctionnel avec une interface console interactive.', '2025-01-24 10:16:00', 2),
(33, 'sae POO', '2025', 3, 'sujet', '2025-01-24 12:41:00', 43),
(34, 'sae dev web', '2025', 3, 'sujet', '2025-01-24 12:44:00', 43),
(35, 'Sae DROIT RGPD', '2025', 1, 'Force aux S6', '2025-01-28 16:19:00', 2),
(36, 'SAE refactoring code dev web des s3', '2025', 6, 'Force à vous je crois en vous (ou pas) (Heureseusement on est la)', '2025-01-28 17:38:00', 2);

-- --------------------------------------------------------

--
-- Structure de la table `Soutenance`
--

CREATE TABLE `Soutenance` (
  `idSoutenance` int NOT NULL,
  `dureeMinutes` int NOT NULL,
  `titre` varchar(45) NOT NULL,
  `salle` varchar(80) NOT NULL,
  `date` date NOT NULL,
  `idSAE` int NOT NULL,
  `idEvaluation` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `Soutenance`
--

INSERT INTO `Soutenance` (`idSoutenance`, `dureeMinutes`, `titre`, `salle`, `date`, `idSAE`, `idEvaluation`) VALUES
(37, 20, 'soutenance', 'B1-13', '2025-01-31', 33, 73),
(38, 5, 'Soutenance sae', 'B1-13', '2025-02-22', 35, 80),
(39, 5, 'Soutenance sae', 'B1-13', '2025-01-31', 36, 82);

-- --------------------------------------------------------

--
-- Structure de la table `SupportSoutenance`
--

CREATE TABLE `SupportSoutenance` (
  `idSoutenance` int NOT NULL,
  `idGroupe` int NOT NULL,
  `support` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `SupportSoutenance`
--

INSERT INTO `SupportSoutenance` (`idSoutenance`, `idGroupe`, `support`) VALUES
(37, 71, '67938b5d313ef-dutinfopw201628 (6).sql');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Champs`
--
ALTER TABLE `Champs`
  ADD PRIMARY KEY (`idChamps`),
  ADD KEY `idSAE_idx` (`idSAE`);

--
-- Index pour la table `Document`
--
ALTER TABLE `Document`
  ADD PRIMARY KEY (`idDoc`),
  ADD KEY `idAuteur_idx` (`idAuteur`),
  ADD KEY `idGroupe_idx` (`idGroupe`);

--
-- Index pour la table `EleveInscritSae`
--
ALTER TABLE `EleveInscritSae`
  ADD PRIMARY KEY (`idSAE`,`idEleve`);

--
-- Index pour la table `EtudiantGroupe`
--
ALTER TABLE `EtudiantGroupe`
  ADD PRIMARY KEY (`idGroupe`,`idEtudiant`),
  ADD KEY `idEtudiant_idx` (`idEtudiant`);

--
-- Index pour la table `Evaluation`
--
ALTER TABLE `Evaluation`
  ADD PRIMARY KEY (`idEval`),
  ADD KEY `IntervenantEvaluateur` (`IntervenantEvaluateur`);

--
-- Index pour la table `Groupe`
--
ALTER TABLE `Groupe`
  ADD PRIMARY KEY (`idgroupe`),
  ADD KEY `idSAE_idx` (`idSAE`);

--
-- Index pour la table `IntervenantSAE`
--
ALTER TABLE `IntervenantSAE`
  ADD PRIMARY KEY (`idSAE`,`idIntervenant`);

--
-- Index pour la table `JurySoutenance`
--
ALTER TABLE `JurySoutenance`
  ADD PRIMARY KEY (`idSoutenance`,`idPersonne`),
  ADD KEY `fk_membrejury` (`idPersonne`);

--
-- Index pour la table `Notes`
--
ALTER TABLE `Notes`
  ADD PRIMARY KEY (`idEval`,`idEleve`,`idRendu`),
  ADD KEY `idEleve` (`idEleve`),
  ADD KEY `idRendu` (`idRendu`);

--
-- Index pour la table `NotesSoutenance`
--
ALTER TABLE `NotesSoutenance`
  ADD PRIMARY KEY (`idEval`,`idEleve`,`idSoutenance`),
  ADD KEY `idEleve` (`idEleve`),
  ADD KEY `idSoutenance` (`idSoutenance`);

--
-- Index pour la table `Notifications`
--
ALTER TABLE `Notifications`
  ADD PRIMARY KEY (`idNotification`),
  ADD UNIQUE KEY `idNotification` (`idNotification`),
  ADD KEY `idSaeProvenance` (`idSaeProvenance`),
  ADD KEY `idPersonne` (`idPersonne`);

--
-- Index pour la table `PassageSoutenance`
--
ALTER TABLE `PassageSoutenance`
  ADD PRIMARY KEY (`idGroupe`,`idSoutenance`),
  ADD KEY `FK_idSoutenance` (`idSoutenance`);

--
-- Index pour la table `Personne`
--
ALTER TABLE `Personne`
  ADD PRIMARY KEY (`idPersonne`);

--
-- Index pour la table `PropositionsEleve`
--
ALTER TABLE `PropositionsEleve`
  ADD PRIMARY KEY (`idProposition`,`idEleve`),
  ADD KEY `idEleve` (`idEleve`);

--
-- Index pour la table `PropositionsGroupe`
--
ALTER TABLE `PropositionsGroupe`
  ADD PRIMARY KEY (`idProposition`,`idSAE`) USING BTREE,
  ADD KEY `idSAE` (`idSAE`);

--
-- Index pour la table `Rendu`
--
ALTER TABLE `Rendu`
  ADD PRIMARY KEY (`idRendu`),
  ADD KEY `idSAE_idx` (`idSAE`),
  ADD KEY `idEvaluation` (`idEvaluation`);

--
-- Index pour la table `RenduGroupe`
--
ALTER TABLE `RenduGroupe`
  ADD PRIMARY KEY (`idRendu`,`idGroupe`),
  ADD KEY `idGroupe_idx` (`idGroupe`);

--
-- Index pour la table `reponsesChamp`
--
ALTER TABLE `reponsesChamp`
  ADD PRIMARY KEY (`idChamp`,`idEleve`),
  ADD KEY `idEleve` (`idEleve`),
  ADD KEY `idChamp` (`idChamp`);

--
-- Index pour la table `ResponsablesSAE`
--
ALTER TABLE `ResponsablesSAE`
  ADD PRIMARY KEY (`idSAE`,`idResp`);

--
-- Index pour la table `Ressource`
--
ALTER TABLE `Ressource`
  ADD PRIMARY KEY (`idRessource`);

--
-- Index pour la table `RessourcesSAE`
--
ALTER TABLE `RessourcesSAE`
  ADD PRIMARY KEY (`idSAE`,`idRessource`),
  ADD KEY `fk_idRessource` (`idRessource`);

--
-- Index pour la table `SAE`
--
ALTER TABLE `SAE`
  ADD PRIMARY KEY (`idSAE`),
  ADD KEY `idResponsable_idx` (`idResponsable`);

--
-- Index pour la table `Soutenance`
--
ALTER TABLE `Soutenance`
  ADD PRIMARY KEY (`idSoutenance`),
  ADD KEY `idSAE_idx` (`idSAE`);

--
-- Index pour la table `SupportSoutenance`
--
ALTER TABLE `SupportSoutenance`
  ADD PRIMARY KEY (`idSoutenance`,`idGroupe`),
  ADD KEY `idGroupe_idx` (`idGroupe`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Champs`
--
ALTER TABLE `Champs`
  MODIFY `idChamps` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `Document`
--
ALTER TABLE `Document`
  MODIFY `idDoc` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `Evaluation`
--
ALTER TABLE `Evaluation`
  MODIFY `idEval` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `Groupe`
--
ALTER TABLE `Groupe`
  MODIFY `idgroupe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT pour la table `Notifications`
--
ALTER TABLE `Notifications`
  MODIFY `idNotification` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32214;

--
-- AUTO_INCREMENT pour la table `Personne`
--
ALTER TABLE `Personne`
  MODIFY `idPersonne` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `PropositionsGroupe`
--
ALTER TABLE `PropositionsGroupe`
  MODIFY `idProposition` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT pour la table `Rendu`
--
ALTER TABLE `Rendu`
  MODIFY `idRendu` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT pour la table `Ressource`
--
ALTER TABLE `Ressource`
  MODIFY `idRessource` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `SAE`
--
ALTER TABLE `SAE`
  MODIFY `idSAE` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `Soutenance`
--
ALTER TABLE `Soutenance`
  MODIFY `idSoutenance` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Champs`
--
ALTER TABLE `Champs`
  ADD CONSTRAINT `idSAEChamps` FOREIGN KEY (`idSAE`) REFERENCES `SAE` (`idSAE`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Evaluation`
--
ALTER TABLE `Evaluation`
  ADD CONSTRAINT `Evaluation_ibfk_1` FOREIGN KEY (`IntervenantEvaluateur`) REFERENCES `Personne` (`idPersonne`) ON DELETE SET NULL;

--
-- Contraintes pour la table `Groupe`
--
ALTER TABLE `Groupe`
  ADD CONSTRAINT `idSAEGroupe` FOREIGN KEY (`idSAE`) REFERENCES `SAE` (`idSAE`) ON DELETE CASCADE;

--
-- Contraintes pour la table `JurySoutenance`
--
ALTER TABLE `JurySoutenance`
  ADD CONSTRAINT `fk_idSoutenanceJury` FOREIGN KEY (`idSoutenance`) REFERENCES `Soutenance` (`idSoutenance`),
  ADD CONSTRAINT `fk_membrejury` FOREIGN KEY (`idPersonne`) REFERENCES `Personne` (`idPersonne`);

--
-- Contraintes pour la table `Notes`
--
ALTER TABLE `Notes`
  ADD CONSTRAINT `Notes_ibfk_1` FOREIGN KEY (`idEval`) REFERENCES `Evaluation` (`idEval`) ON DELETE CASCADE,
  ADD CONSTRAINT `Notes_ibfk_2` FOREIGN KEY (`idEleve`) REFERENCES `Personne` (`idPersonne`) ON DELETE CASCADE,
  ADD CONSTRAINT `Notes_ibfk_3` FOREIGN KEY (`idRendu`) REFERENCES `Rendu` (`idRendu`) ON DELETE CASCADE;

--
-- Contraintes pour la table `NotesSoutenance`
--
ALTER TABLE `NotesSoutenance`
  ADD CONSTRAINT `NotesSoutenance_ibfk_1` FOREIGN KEY (`idEval`) REFERENCES `Evaluation` (`idEval`) ON DELETE CASCADE,
  ADD CONSTRAINT `NotesSoutenance_ibfk_2` FOREIGN KEY (`idEleve`) REFERENCES `Personne` (`idPersonne`) ON DELETE CASCADE,
  ADD CONSTRAINT `NotesSoutenance_ibfk_3` FOREIGN KEY (`idSoutenance`) REFERENCES `Soutenance` (`idSoutenance`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Notifications`
--
ALTER TABLE `Notifications`
  ADD CONSTRAINT `Notifications_ibfk_1` FOREIGN KEY (`idSaeProvenance`) REFERENCES `SAE` (`idSAE`),
  ADD CONSTRAINT `Notifications_ibfk_2` FOREIGN KEY (`idPersonne`) REFERENCES `Personne` (`idPersonne`);

--
-- Contraintes pour la table `PassageSoutenance`
--
ALTER TABLE `PassageSoutenance`
  ADD CONSTRAINT `FK_idGroupe` FOREIGN KEY (`idGroupe`) REFERENCES `Groupe` (`idgroupe`),
  ADD CONSTRAINT `FK_idSoutenance` FOREIGN KEY (`idSoutenance`) REFERENCES `Soutenance` (`idSoutenance`);

--
-- Contraintes pour la table `PropositionsEleve`
--
ALTER TABLE `PropositionsEleve`
  ADD CONSTRAINT `PropositionsEleve_ibfk_1` FOREIGN KEY (`idProposition`) REFERENCES `PropositionsGroupe` (`idProposition`),
  ADD CONSTRAINT `PropositionsEleve_ibfk_2` FOREIGN KEY (`idEleve`) REFERENCES `Personne` (`idPersonne`);

--
-- Contraintes pour la table `PropositionsGroupe`
--
ALTER TABLE `PropositionsGroupe`
  ADD CONSTRAINT `PropositionsGroupe_ibfk_1` FOREIGN KEY (`idSAE`) REFERENCES `SAE` (`idSAE`);

--
-- Contraintes pour la table `Rendu`
--
ALTER TABLE `Rendu`
  ADD CONSTRAINT `idSAERendu` FOREIGN KEY (`idSAE`) REFERENCES `SAE` (`idSAE`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponsesChamp`
--
ALTER TABLE `reponsesChamp`
  ADD CONSTRAINT `reponsesChamp_ibfk_1` FOREIGN KEY (`idEleve`) REFERENCES `Personne` (`idPersonne`),
  ADD CONSTRAINT `reponsesChamp_ibfk_2` FOREIGN KEY (`idEleve`) REFERENCES `Personne` (`idPersonne`);

--
-- Contraintes pour la table `RessourcesSAE`
--
ALTER TABLE `RessourcesSAE`
  ADD CONSTRAINT `fk_idRessource` FOREIGN KEY (`idRessource`) REFERENCES `Ressource` (`idRessource`),
  ADD CONSTRAINT `idSAE` FOREIGN KEY (`idSAE`) REFERENCES `SAE` (`idSAE`) ON DELETE CASCADE;

--
-- Contraintes pour la table `SAE`
--
ALTER TABLE `SAE`
  ADD CONSTRAINT `idResponsable` FOREIGN KEY (`idResponsable`) REFERENCES `Personne` (`idPersonne`);

--
-- Contraintes pour la table `Soutenance`
--
ALTER TABLE `Soutenance`
  ADD CONSTRAINT `idSAESoutenance` FOREIGN KEY (`idSAE`) REFERENCES `SAE` (`idSAE`) ON DELETE CASCADE;

--
-- Contraintes pour la table `SupportSoutenance`
--
ALTER TABLE `SupportSoutenance`
  ADD CONSTRAINT `idGroupeSupportSoutenance` FOREIGN KEY (`idGroupe`) REFERENCES `Groupe` (`idgroupe`),
  ADD CONSTRAINT `idSoutenanceSupport` FOREIGN KEY (`idSoutenance`) REFERENCES `Soutenance` (`idSoutenance`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
