-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 08 déc. 2024 à 22:08
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
-- Base de données : `maurerc_cms`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `chapo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `auteur` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `titre`, `chapo`, `auteur`) VALUES
(1, 'Article 7', 'swd', 'fguhj'),
(2, 'Article 2 !', 'coucou', 'Chloé'),
(3, 'Article 3', 'coucou', 'Chloé'),
(4, 'Article 5', 'qfghj', 'dhsdh'),
(9, 'Article 13', 'bonjour', 'sans auteur'),
(10, 'Article 9', 'bonjour', 'Moi');

-- --------------------------------------------------------

--
-- Structure de la table `bloc`
--

DROP TABLE IF EXISTS `bloc`;
CREATE TABLE IF NOT EXISTS `bloc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `texte` text COLLATE utf8mb4_general_ci NOT NULL,
  `style` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `src` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alt` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `id_article` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bloc`
--

INSERT INTO `bloc` (`id`, `type`, `texte`, `style`, `src`, `alt`, `id_article`) VALUES
(1, 'h2', 'La Campagne ! Hello', 'aucun', '', '', 1),
(2, 'paragraphe', 'Dans les près on voit des vaches et des moutons. Il y a des fermes et des fermiers', '', '', '', 1),
(49, 'image', '', 'yr dfbj utjg', 'https://img.freepik.com/vecteurs-libre/paysage-printemps-dessine-main_23-2148822586.jpg?t=st=1732874767~exp=1732878367~hmac=363bc219df97ba45b84d59d6f12df5c4fefed805f3f2cd963a35d3dfe74fa049&w=826', 'image d\'un champs', 4),
(54, 'h2', 'La Campagne ! Coucou ?', '', '', '', 4),
(56, 'h3', 'Les Vaches', '', '', '', 2),
(57, 'h2', 'La Campagne ! Coucou', 'bleu', '', '', 1),
(60, 'h3', 'Les Vaches 2', 'rouge', '', '', 2),
(71, 'h3', 'Les Moutons', 'violet', '', '', 4),
(72, 'h2', 'Avion', 'rouge', '', '', 2);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bloc`
--
ALTER TABLE `bloc`
  ADD CONSTRAINT `bloc_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
