-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 01 juin 2021 à 10:19
-- Version du serveur :  5.7.31
-- Version de PHP : 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `belles_histoires`
--

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ordre` tinyint(4) NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL,
  `telephone` varchar(15) CHARACTER SET utf8mb4 DEFAULT NULL,
  `commentaires` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `en_charge` tinyint(1) NOT NULL,
  `communiquer_externe` tinyint(1) NOT NULL,
  `id_type` int(11) DEFAULT NULL,
  `id_histoire` varchar(255) NOT NULL,
  `complement_1` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_type_contact` (`id_type`),
  KEY `fk_histoire` (`id_histoire`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dt`
--

DROP TABLE IF EXISTS `dt`;
CREATE TABLE IF NOT EXISTS `dt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `dt`
--

INSERT INTO `dt` (`id`, `nom`) VALUES
(1, 'Aisne-Somme'),
(2, 'Nord'),
(3, 'Oise'),
(4, 'Pas-de-Calais');

-- --------------------------------------------------------

--
-- Structure de la table `dtd`
--

DROP TABLE IF EXISTS `dtd`;
CREATE TABLE IF NOT EXISTS `dtd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `id_dt` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_dt_dtd` (`id_dt`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `dtd`
--

INSERT INTO `dtd` (`id`, `nom`, `id_dt`) VALUES
(1, 'Aisne', 1),
(2, 'Somme', 1),
(3, 'Flandres', 2),
(4, 'Hainaut', 2),
(5, 'Lille', 2),
(6, 'Versant Nord Est', 2),
(7, 'Oise', 3),
(8, 'Arrageois', 4),
(9, 'Nord-Artois Côte d\'Opale', 4);

-- --------------------------------------------------------

--
-- Structure de la table `histoire`
--

DROP TABLE IF EXISTS `histoire`;
CREATE TABLE IF NOT EXISTS `histoire` (
  `id` varchar(255) NOT NULL,
  `titre` text CHARACTER SET utf8mb4,
  `recit` text CHARACTER SET utf8mb4 NOT NULL,
  `evolutions` text CHARACTER SET utf8mb4,
  `nom_redacteur` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `prenom_redacteur` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `email_redacteur` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `telephone_redacteur` varchar(15) CHARACTER SET utf8mb4 DEFAULT NULL,
  `fonction_redacteur` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `id_agence` int(11) NOT NULL,
  `date_redaction` date NOT NULL,
  `debut_periode` date NOT NULL,
  `fin_periode` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_agence` (`id_agence`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `histoire_mot_cle`
--

DROP TABLE IF EXISTS `histoire_mot_cle`;
CREATE TABLE IF NOT EXISTS `histoire_mot_cle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mot_cle` int(11) NOT NULL,
  `id_histoire` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mot_cle` (`id_mot_cle`),
  KEY `fl_histoire` (`id_histoire`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `histoire_public_cible`
--

DROP TABLE IF EXISTS `histoire_public_cible`;
CREATE TABLE IF NOT EXISTS `histoire_public_cible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_public_cible` int(11) NOT NULL,
  `id_histoire` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_histoire_public` (`id_histoire`),
  KEY `fk_public_cible` (`id_public_cible`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `mot_cle`
--

DROP TABLE IF EXISTS `mot_cle`;
CREATE TABLE IF NOT EXISTS `mot_cle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mot` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `predefini` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `mot_cle`
--

INSERT INTO `mot_cle` (`id`, `mot`, `predefini`) VALUES
(2, 'Formation', 1),
(3, 'Recrutement', 1);

-- --------------------------------------------------------

--
-- Structure de la table `public_cible`
--

DROP TABLE IF EXISTS `public_cible`;
CREATE TABLE IF NOT EXISTS `public_cible` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `public_cible` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `predefini` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `public_cible`
--

INSERT INTO `public_cible` (`id`, `public_cible`, `predefini`) VALUES
(2, 'RSA', 1),
(3, 'Jeune', 1);

-- --------------------------------------------------------

--
-- Structure de la table `site_rattachement`
--

DROP TABLE IF EXISTS `site_rattachement`;
CREATE TABLE IF NOT EXISTS `site_rattachement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `id_dtd` int(11) DEFAULT NULL,
  `id_dt` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_dtd` (`id_dtd`),
  KEY `fk_id_dt` (`id_dt`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `site_rattachement`
--

INSERT INTO `site_rattachement` (`id`, `nom`, `id_dtd`, `id_dt`) VALUES
(3, 'Château-Thierry', 1, NULL),
(4, 'Chauny', 1, NULL),
(5, 'Hirson', 1, NULL),
(6, 'Laon', 1, NULL),
(7, 'Saint Quentin Cordier', 1, NULL),
(8, 'Saint Quentin Peri', 1, NULL),
(9, 'Soissons', 1, NULL),
(10, 'Vervins', 1, NULL),
(11, 'Abbeville', 2, NULL),
(12, 'Amiens Dury', 2, NULL),
(13, 'Amiens Millevoye', 2, NULL),
(14, 'Amiens Tellier', 2, NULL),
(15, 'Doullens', 2, NULL),
(16, 'Friville Escarbotin', 2, NULL),
(17, 'Ham', 2, NULL),
(18, 'Montdidier', 2, NULL),
(19, 'Peronne Albert', 2, NULL),
(20, 'Bailleul', 3, NULL),
(21, 'Dunkerque', 3, NULL),
(22, 'Grande-Synthe', 3, NULL),
(23, 'Gravelines', 3, NULL),
(24, 'Hazebrouck', 3, NULL),
(25, 'Aulnoye-Aymeries', 4, NULL),
(26, 'Avesnelles', 4, NULL),
(27, 'Cambrai', 4, NULL),
(28, 'Caudry', 4, NULL),
(29, 'Le Cateau-Cambrésis', 4, NULL),
(30, 'Le Quesnoy', 4, NULL),
(31, 'Maubeuge Gare', 4, NULL),
(32, 'Maubeuge Pasteur', 4, NULL),
(33, 'Anzin', 4, NULL),
(34, 'Condé-sur-Escaut', 4, NULL),
(35, 'Denain', 4, NULL),
(36, 'Douai', 4, NULL),
(37, 'Saint-Amand', 4, NULL),
(38, 'Sin Le Noble', 4, NULL),
(39, 'Somain', 4, NULL),
(40, 'Valenciennes', 4, NULL),
(41, 'Armentières', 5, NULL),
(42, 'Haubourdin', 5, NULL),
(43, 'La Madeleine', 5, NULL),
(44, 'Lille Grand Sud', 5, NULL),
(45, 'Lille Port Fluvial', 5, NULL),
(46, 'Lille République', 5, NULL),
(47, 'Lille Vaucanson', 5, NULL),
(48, 'Lomme', 5, NULL),
(49, 'Seclin', 5, NULL),
(50, 'Villeneuve-d\'Ascq', 5, NULL),
(51, 'Croix', 6, NULL),
(52, 'Halluin', 6, NULL),
(53, 'Hem', 6, NULL),
(54, 'Roubaix Centre', 6, NULL),
(55, 'Roubaix Les Prés', 6, NULL),
(56, 'Tourcoing', 6, NULL),
(57, 'Wattrelos', 6, NULL),
(58, 'Beauvais Delie', 7, NULL),
(59, 'Beauvais Mykonos', 7, NULL),
(60, 'Clerrmont', 7, NULL),
(61, 'Compiegne De Lesseps', 7, NULL),
(62, 'Compiegne Margny', 7, NULL),
(63, 'Creil Montaire', 7, NULL),
(64, 'Creil Nogent', 7, NULL),
(65, 'Creil Saint Maximin', 7, NULL),
(66, 'Crepy En Valois', 7, NULL),
(67, 'Meru ', 7, NULL),
(68, 'Noyon', 7, NULL),
(69, 'Arras', 8, NULL),
(70, 'Bapaume', 8, NULL),
(71, 'Carvin', 8, NULL),
(72, 'Hénin-Beaumont', 8, NULL),
(73, 'Lens Gare', 8, NULL),
(74, 'Lens Laloux', 8, NULL),
(75, 'Liévin', 8, NULL),
(76, 'St-Pol-sur-Ternoise', 8, NULL),
(77, 'Berck', 9, NULL),
(78, 'Béthune Faïencerie', 9, NULL),
(79, 'Boulogne', 9, NULL),
(80, 'Bruay-la-buissière', 9, NULL),
(81, 'Calais Mollien', 9, NULL),
(82, 'Calais Saint Exupery', 9, NULL),
(83, 'Lilliers', 9, NULL),
(84, 'Longeunesse', 9, NULL),
(85, 'Marconnelle', 9, NULL),
(86, 'Noeux-les-mines', 9, NULL),
(87, 'St Martin-les-Boulogne', 9, NULL),
(88, 'Direction Régionale', NULL, NULL),
(89, 'Aisne-Somme', NULL, 1),
(90, 'Nord', NULL, 2),
(91, 'Oise', NULL, 3),
(92, 'Pas-de-Calais', NULL, 4);

-- --------------------------------------------------------

--
-- Structure de la table `type_contact`
--

DROP TABLE IF EXISTS `type_contact`;
CREATE TABLE IF NOT EXISTS `type_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `intitule` varchar(50) CHARACTER SET utf8mb4 NOT NULL,
  `complement_1` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `type_contact`
--

INSERT INTO `type_contact` (`id`, `intitule`, `complement_1`) VALUES
(1, 'Entreprise', 'Num SIRET'),
(2, 'DE', 'identifiant DE'),
(5, 'Autre', 'Précisez'),
(6, 'Agent Pôle emploi', 'Site de rattachement');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` tinytext NOT NULL,
  `password` tinytext NOT NULL,
  `super_admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `email`, `password`, `super_admin`) VALUES
(1, 'enzo.firmino@pole-emploi.fr', 'ad7455f6068f449f98e7fe7d3c1f5f539505a857d3023f2a339bca8f3a74b4e1', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `histoire`
--
ALTER TABLE `histoire` ADD FULLTEXT KEY `ft_titre` (`titre`);
ALTER TABLE `histoire` ADD FULLTEXT KEY `ft_recit` (`recit`);
ALTER TABLE `histoire` ADD FULLTEXT KEY `ft_evolution` (`evolutions`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `fk_histoire` FOREIGN KEY (`id_histoire`) REFERENCES `histoire` (`id`),
  ADD CONSTRAINT `fk_type_contact` FOREIGN KEY (`id_type`) REFERENCES `type_contact` (`id`);

--
-- Contraintes pour la table `dtd`
--
ALTER TABLE `dtd`
  ADD CONSTRAINT `fk_id_dt_dtd` FOREIGN KEY (`id_dt`) REFERENCES `dt` (`id`);

--
-- Contraintes pour la table `histoire`
--
ALTER TABLE `histoire`
  ADD CONSTRAINT `fk_agence` FOREIGN KEY (`id_agence`) REFERENCES `site_rattachement` (`id`);

--
-- Contraintes pour la table `histoire_mot_cle`
--
ALTER TABLE `histoire_mot_cle`
  ADD CONSTRAINT `fk_mot_cle` FOREIGN KEY (`id_mot_cle`) REFERENCES `mot_cle` (`id`),
  ADD CONSTRAINT `fl_histoire` FOREIGN KEY (`id_histoire`) REFERENCES `histoire` (`id`);

--
-- Contraintes pour la table `histoire_public_cible`
--
ALTER TABLE `histoire_public_cible`
  ADD CONSTRAINT `fk_histoire_public` FOREIGN KEY (`id_histoire`) REFERENCES `histoire` (`id`),
  ADD CONSTRAINT `fk_public_cible` FOREIGN KEY (`id_public_cible`) REFERENCES `public_cible` (`id`);

--
-- Contraintes pour la table `site_rattachement`
--
ALTER TABLE `site_rattachement`
  ADD CONSTRAINT `fk_id_dt` FOREIGN KEY (`id_dt`) REFERENCES `dt` (`id`),
  ADD CONSTRAINT `fk_id_dtd` FOREIGN KEY (`id_dtd`) REFERENCES `dtd` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
