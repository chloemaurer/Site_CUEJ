-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 22 déc. 2024 à 12:34
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
-- Base de données : `cuej_perso`
--

-- --------------------------------------------------------

--
-- Structure de la table `chapitre`
--

DROP TABLE IF EXISTS `chapitre`;
CREATE TABLE IF NOT EXISTS `chapitre` (
  `id_chapitre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `chapo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alt` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_chapitre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapitre`
--

INSERT INTO `chapitre` (`id_chapitre`, `titre`, `chapo`, `image`, `alt`) VALUES
(1, 'Battre la campagne', 'Le monde rural se décline au pluriel. Défense d’un terroir, sauvegarde d’une langue régionale ou préservation de l’écosystème local… En ruralité, les populations se mobilisent pour conserver leur identité, quitte à tenir tête aux élu·es et aux industriels.', '6767ffa50fa455.90410432.jpg', 'Battre campagne'),
(2, 'Cultiver les liens', 'Alors que les crises sociales et économiques s\'intensifient, la résilience et la solidarité deviennent des réponses incontournables. Des initiatives locales aux mouvements globaux, des citoyens s’organisent pour bâtir un monde plus juste et équitable. Ce chapitre met en lumière les dynamiques positives et les nouvelles formes de coopération qui émergent face à l\'adversité.\r\n\r\n', '67642009d866f3.27767050.jpg', 'fantastique'),
(3, 'Irriguer la lutte', 'L’art et la culture ne sont pas de simples expressions créatives : ils deviennent des outils puissants pour questionner, fédérer et transformer la société. Entre les projets de territoire, les initiatives dans l’espace public et l’intégration des nouvelles technologies, ce chapitre explore comment la culture peut devenir un moteur de changement social et humain.', '67642010d6d614.95026683.jpg', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
