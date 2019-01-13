-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 08 août 2018 à 20:09
-- Version du serveur :  5.7.21
-- Version de PHP :  5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `kidoikoi`
--

-- --------------------------------------------------------

--
-- Structure de la table `depense`
--

DROP TABLE IF EXISTS `depense`;
CREATE TABLE IF NOT EXISTS `depense` (
  `idDepense` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `montant` decimal(6,2) NOT NULL,
  `description` text NOT NULL,
  `idEvenement` int(11) NOT NULL,
  PRIMARY KEY (`idDepense`),
  KEY `fk_evenement_idEvenement` (`idEvenement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `idEvenement` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idEvenement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `participe`
--

DROP TABLE IF EXISTS `participe`;
CREATE TABLE IF NOT EXISTS `participe` (
  `idParticipe` int(11) NOT NULL AUTO_INCREMENT,
  `idEvenement` int(11) NOT NULL,
  `pseudoUtilisateur` varchar(20) NOT NULL,
  `organise` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idParticipe`),
  KEY `fk_evenement_idEvenement_participe` (`idEvenement`),
  KEY `fk_utilisateur_pseudoUtilisateur_participe` (`pseudoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `repartition`
--

DROP TABLE IF EXISTS `repartition`;
CREATE TABLE IF NOT EXISTS `repartition` (
  `idRepartition` int(11) NOT NULL AUTO_INCREMENT,
  `idDepense` int(11) NOT NULL,
  `pseudoUtilisateur` varchar(20) NOT NULL,
  `nbPart` int(2) NOT NULL DEFAULT '1',
  `avance` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idRepartition`),
  KEY `fk_depense_idDepense_repartition` (`idDepense`),
  KEY `fk_utilisateurinscrit_pseudoUtilisateur_repartition` (`pseudoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `pseudoUtilisateur` varchar(20) NOT NULL,
  PRIMARY KEY (`pseudoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurinscrit`
--

DROP TABLE IF EXISTS `utilisateurinscrit`;
CREATE TABLE IF NOT EXISTS `utilisateurinscrit` (
  `pseudoUtilisateur` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL UNIQUE,
  `password` varchar(10) NOT NULL,
  `sel` int(5) NOT NULL,
  `img` varchar(20) NOT NULL,
  PRIMARY KEY (`pseudoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurtemporaire`
--

DROP TABLE IF EXISTS `utilisateurtemporaire`;
CREATE TABLE IF NOT EXISTS `utilisateurtemporaire` (
  `pseudoUtilisateur` varchar(20) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  PRIMARY KEY (`pseudoUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `depense`
--
ALTER TABLE `depense`
  ADD CONSTRAINT `fk_evenement_idEvenement` FOREIGN KEY (`idEvenement`) REFERENCES `evenement` (`idEvenement`) ON DELETE CASCADE;

--
-- Contraintes pour la table `participe`
--
ALTER TABLE `participe`
  ADD CONSTRAINT `fk_evenement_idEvenement_participe` FOREIGN KEY (`idEvenement`) REFERENCES `evenement` (`idEvenement`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_pseudoUtilisateur_participe` FOREIGN KEY (`pseudoUtilisateur`) REFERENCES `utilisateur` (`pseudoUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `repartition`
--
ALTER TABLE `repartition`
  ADD CONSTRAINT `fk_depense_idDepense_repartition` FOREIGN KEY (`idDepense`) REFERENCES `depense` (`idDepense`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_utilisateur_pseudoUtilisateur_repartition` FOREIGN KEY (`pseudoUtilisateur`) REFERENCES `utilisateur` (`pseudoUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateurinscrit`
--
ALTER TABLE `utilisateurinscrit`
  ADD CONSTRAINT `fk_utilisateur_pseudoUtilisateur1` FOREIGN KEY (`pseudoUtilisateur`) REFERENCES `utilisateur` (`pseudoUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateurtemporaire`
--
ALTER TABLE `utilisateurtemporaire`
  ADD CONSTRAINT `fk_utilisateur_pseudoUtilisateur2` FOREIGN KEY (`pseudoUtilisateur`) REFERENCES `utilisateur` (`pseudoUtilisateur`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
