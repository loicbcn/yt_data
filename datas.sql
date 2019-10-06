-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Dim 06 Octobre 2019 à 20:08
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `recup_vids`
--

-- --------------------------------------------------------

--
-- Structure de la table `myvids`
--

CREATE TABLE `myvids` (
  `id` int(11) NOT NULL,
  `yt_id` varchar(11) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `extension` varchar(5) DEFAULT NULL,
  `friendly` varchar(255) DEFAULT NULL,
  `friendly_suffix` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `myvids`
--
ALTER TABLE `myvids`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `myvids`
--
ALTER TABLE `myvids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
