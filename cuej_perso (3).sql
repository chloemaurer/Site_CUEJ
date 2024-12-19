-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 19 déc. 2024 à 08:57
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



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
  `titre` varchar(60)  NOT NULL,
  `chapo` text  NOT NULL,
  `auteur` varchar(20)  NOT NULL,
  `image` varchar(60)  NOT NULL,
  `alt` varchar(60)  NOT NULL,
  `ordre` int NOT NULL,
  `id_chapitre` int NOT NULL,
  PRIMARY KEY (`id_article`),
  KEY `id_chapitre` (`id_chapitre`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure de la table `bloc`
--

DROP TABLE IF EXISTS `bloc`;
CREATE TABLE IF NOT EXISTS `bloc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(60)  NOT NULL,
  `texte` text ,
  `texte_1` varchar(255)  DEFAULT NULL,
  `texte_2` varchar(255)  DEFAULT NULL,
  `texte_3` varchar(255)  DEFAULT NULL,
  `texte_4` varchar(255)  DEFAULT NULL,
  `texte_titre` varchar(255)  DEFAULT NULL,
  `texte_citation` varchar(255)  DEFAULT NULL,
  `texte_legende` varchar(255)  DEFAULT NULL,
  `texte_credit` varchar(255)  DEFAULT NULL,
  `style` varchar(60)  DEFAULT NULL,
  `image` varchar(60)  DEFAULT NULL,
  `image_1` varchar(60)  DEFAULT NULL,
  `image_2` varchar(60)  DEFAULT NULL,
  `image_3` varchar(60)  DEFAULT NULL,
  `image_4` varchar(60)  DEFAULT NULL,
  `audio` varchar(255)  DEFAULT NULL,
  `video` varchar(60)  DEFAULT NULL,
  `infographie` varchar(60)  DEFAULT NULL,
  `alt` varchar(60)  DEFAULT NULL,
  `ordre` int DEFAULT NULL,
  `id_article` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_article` (`id_article`)
) ENGINE=InnoDB;

-- --------------------------------------------------------

--
-- Structure de la table `chapitre`
--

DROP TABLE IF EXISTS `chapitre`;
CREATE TABLE IF NOT EXISTS `chapitre` (
  `id_chapitre` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(60)  NOT NULL,
  `chapo` text  NOT NULL,
  `image` text  NOT NULL,
  `alt` varchar(60)  NOT NULL,
  PRIMARY KEY (`id_chapitre`)
) ENGINE=InnoDB AUTO_INCREMENT=4;

--
-- Déchargement des données de la table `chapitre`
--

INSERT INTO `chapitre` (`id_chapitre`, `titre`, `chapo`, `image`, `alt`) VALUES
(1, 'lutter pour l&#039;égalité', 'cbbbbcbcbcbcbcbcbcbcbcbzdbjkbc\r\njjzcbjkcbdjbkzbjzkbckbzcjbzjcbjkzbzcbjk\r\nczbjkdbjbcjbcdjbjcbddbdbdzcb\r\njzdjkdbdjkbdkbbdzcjdzbkdcbzdbjk', '676371adc21b26.34076789.jpeg', 'noelllll'),
(2, 'Faire collectif, se mobiliser', 'coucou&nbsp;: l&#039;artisant', '676371ca14c1e4.57898337.jpeg', 'fantastique'),
(3, 'la campagne et le fromage', 'Miam&nbsp;!!', '676371d26560d9.10437054.jpeg', '');

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


