-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 08 oct. 2025 à 23:21
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet2425`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

DROP TABLE IF EXISTS `avis`;
CREATE TABLE IF NOT EXISTS `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `spot_id` int NOT NULL,
  `pseudo` varchar(50) DEFAULT NULL,
  `commentaire` text,
  `note` tinyint UNSIGNED DEFAULT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `spot_id` (`spot_id`)
) ;

-- --------------------------------------------------------

--
-- Structure de la table `spots`
--

DROP TABLE IF EXISTS `spots`;
CREATE TABLE IF NOT EXISTS `spots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `localisation` varchar(255) DEFAULT NULL,
  `confort` tinyint UNSIGNED DEFAULT '0',
  `frequentation` enum('faible','moyenne','forte') DEFAULT 'moyenne',
  `image` varchar(255) DEFAULT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `spots`
--

INSERT INTO `spots` (`id`, `nom`, `description`, `localisation`, `confort`, `frequentation`, `image`, `date_ajout`) VALUES
(1, 'Derrière la machine à café', 'Endroit chaud, bruit de fond relaxant, mais risque de se faire griller par les profs.', 'Bâtiment B - RDC', 6, 'forte', 'cafe.jpg', '2025-10-08 23:14:06'),
(2, 'Sous l\'escalier du bâtiment A', 'Coin sombre et discret, parfait pour les siestes ninja.', 'Bâtiment A - Rez-de-chaussée', 8, 'moyenne', 'escalier.jpg', '2025-10-08 23:14:06'),
(3, 'CDI', 'Canapé moelleux, ambiance calme, risque de se faire réveiller par le silence absolu.', 'Bâtiment C - 2e étage', 9, 'faible', 'cdi.jpg', '2025-10-08 23:14:06'),
(4, 'Toilettes du 3e', 'Ambiance humide et sonore, pas idéal mais souvent libre.', 'Bâtiment D - 3e étage', 3, 'faible', 'toilettes.jpg', '2025-10-08 23:14:06');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
