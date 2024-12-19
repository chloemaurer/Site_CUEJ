-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 18 déc. 2024 à 23:26
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
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_article` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `chapo` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `auteur` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `image` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alt` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ordre` int NOT NULL,
  `id_chapitre` int NOT NULL,
  PRIMARY KEY (`id_article`),
  KEY `id_chapitre` (`id_chapitre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `titre`, `chapo`, `auteur`, `image`, `alt`, `ordre`, `id_chapitre`) VALUES
(1, 'Article 1', 'swdfxghjoklm', 'Chloé', '67632d93b68673.94460175.jpg', '', 0, 2),
(2, 'Article 2', 'Woahhhh', 'Moi', '67632ddccea794.62000240.jpeg', '', 0, 2),
(3, 'Article 3', 'ghbjn,;:', 'Moi', '67632e128cbc97.32848172.png', '', 0, 2),
(4, 'Article 3', 'helloooo!!!', 'Chloé', '', '', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bloc`
--

DROP TABLE IF EXISTS `bloc`;
CREATE TABLE IF NOT EXISTS `bloc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `texte` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `texte_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_titre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_citation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_legende` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `texte_credit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `style` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_1` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_2` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_3` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_4` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `audio` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `video` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `infographie` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alt` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ordre` int DEFAULT NULL,
  `id_article` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bloc`
--

INSERT INTO `bloc` (`id`, `type`, `texte`, `texte_1`, `texte_2`, `texte_3`, `texte_4`, `texte_titre`, `texte_citation`, `texte_legende`, `texte_credit`, `style`, `image`, `image_1`, `image_2`, `image_3`, `image_4`, `audio`, `video`, `infographie`, `alt`, `ordre`, `id_article`) VALUES
(76, 'h1', 'Les Moutons', NULL, NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, NULL, NULL, NULL, '', '', NULL, NULL, 1, 1),
(89, 'h2', 'Les Moutons 2', NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', 1, 2),
(93, 'image', '', NULL, NULL, NULL, NULL, '', '', '', '', '', '67628cd1712778.33776512.png', '', '', '', '', '', '', NULL, '', 5, 1),
(101, 'audio', '', NULL, NULL, NULL, NULL, 'Shrek', '', 'i&#039;m a believer', '', 'corp_de_texte', '', '', '', '', '', '', '', NULL, 'fantastique', 6, 1),
(102, 'audio', '', NULL, NULL, NULL, NULL, '', '', '', '', 'corp_de_texte', '', '', '', '', '', '', '', NULL, '', 7, 1),
(103, 'audio', '', NULL, NULL, NULL, NULL, '', '', '', '', 'corp_de_texte', '', '', '', '', '', NULL, '', NULL, '', 8, 1),
(111, 'texte_image', '', NULL, NULL, NULL, NULL, '', '', '', '', 'corp_de_texte', '', '', '', '', '', '', '', NULL, '', 9, 1),
(112, 'texte_image', 'Les Vaches 2', NULL, NULL, NULL, NULL, '', '', '', 'Moi', 'corp_de_texte', '6762b31bee0ee3.02923376.jpg', '', '', '', '', '', '', NULL, '', 10, 1),
(113, 'texte_image', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1&nbsp;500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged', NULL, NULL, NULL, NULL, '', '', 'vache', 'Moi', 'corp_de_texte', '6762b3d9ab1da0.75127351.jpg', '', '', '', '', '', '', NULL, '', 11, 1),
(114, 'audio', '', NULL, NULL, NULL, NULL, 'Shrek', '', 'i&#039;m a believer', '', 'corp_de_texte', '6762b4c00dfa55.89157643.jpg', '', '', '', '', 'shrek.mp3', '', NULL, 'fantastique', 12, 1),
(148, 'video', '', NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '6762e306cc6dc0.68650512.mp4', NULL, '', 29, 1),
(149, 'h2', 'Les Vaches 2', NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', 30, 1),
(150, 'video', '', NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '6762f3e7cbd994.48104858.mp4', NULL, '', 31, 1),
(151, 'carousel', 'Les Moutons', 'Les Moutons 2', 'Les Moutons 3', 'Les Moutons 4', '', '', '', '', '', '', '67633f596a81d0.95796095.jpeg', '67633f596b3351.13681592.jpeg', '67633f596bb771.27168151.jpeg', '67633f596c5544.24789932.jpeg', '', '', '', NULL, 'carousel', 2, 2),
(152, 'h2', 'Les Moutons', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', 3, 2),
(153, 'image', '', '', '', '', '', '', '', '', '', '', '676342c1e847a5.26958781.jpeg', '', '', '', '', '', '', NULL, '', 4, 2),
(157, 'video', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6763447b7f9749.10142507.mp4', NULL, '', 6, 2),
(162, 'audio', '', '', '', '', '', 'Noel', '', 'credit en fait', '', '', '6763454c0d5359.84346002.jpeg', '', '', '', '', '6763454c0d8e00.94006720.mp3', '', NULL, 'noel', 7, 2),
(163, 'audio_texte', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1&nbsp;500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1&nbsp;960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop', '', '', '', '', 'Noell&nbsp;!!!!!!!!!', '', 'i&#039;m a believer', 'Moi', 'corp_de_texte', '676349c5aa5b18.87552106.jpeg', '', '', '', '', '676349c5aa9b94.98208231.mp3', '', NULL, 'image d&#039;un champs', 8, 2);

-- --------------------------------------------------------

--
-- Structure de la table `chapitre`
--

DROP TABLE IF EXISTS `chapitre`;
CREATE TABLE IF NOT EXISTS `chapitre` (
  `id_chapitre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `chapo` text COLLATE utf8mb4_general_ci NOT NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alt` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_chapitre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chapitre`
--

INSERT INTO `chapitre` (`id_chapitre`, `titre`, `chapo`, `image`, `alt`) VALUES
(1, 'lutter pour l&#039;égalité', 'cbbbbcbcbcbcbcbcbcbcbcbzdbjkbc\r\njjzcbjkcbdjbkzbjzkbckbzcjbzjcbjkzbzcbjk\r\nczbjkdbjbcjbcdjbjcbddbdbdzcb\r\njzdjkdbdjkbdkbbdzcjdzbkdcbzdbjk', '67633094a9c6d5.93718376.jpeg', 'noelllll'),
(2, 'Faire collectif, se mobiliser', 'coucou&nbsp;: l&#039;artisant', '6763311304ec46.74047400.jpeg', 'fantastique'),
(3, 'la campagne et le fromage', 'Miam&nbsp;!!', '6763331d2c4bd4.81761855.jpeg', '');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_chapitre`) REFERENCES `chapitre` (`id_chapitre`);

--
-- Contraintes pour la table `bloc`
--
ALTER TABLE `bloc`
  ADD CONSTRAINT `bloc_ibfk_1` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
