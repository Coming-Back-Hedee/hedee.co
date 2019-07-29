-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 17 Juillet 2019 à 14:33
-- Version du serveur :  5.7.11
-- Version de PHP :  7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `coming_back`
--

-- --------------------------------------------------------

--
-- Structure de la table `enseignes`
--

CREATE TABLE `enseignes` (
  `id` int(11) NOT NULL,
  `nom_enseigne` varchar(100) NOT NULL,
  `logo_enseigne` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `enseignes`
--

INSERT INTO `enseignes` (`id`, `nom_enseigne`, `logo_enseigne`) VALUES
(1, 'Adidas', NULL),
(2, 'Alinea', NULL),
(3, 'Andre', NULL),
(4, 'Apple ', NULL),
(5, 'Auchan', 'auchan.jpg'),
(6, 'BHV', NULL),
(7, 'Bigmat', NULL),
(8, 'Boulanger', NULL),
(9, 'Brico Depot ', NULL),
(10, 'Bricolex', NULL),
(11, 'Bricoman', NULL),
(12, 'Bricomarche', NULL),
(13, 'bricorama', NULL),
(14, 'BUT', NULL),
(15, 'C&A', NULL),
(16, 'camaieu', NULL),
(17, 'Carrefour', NULL),
(18, 'Castorama', NULL),
(19, 'Celio', NULL),
(20, 'Conforama', NULL),
(21, 'Cora', NULL),
(22, 'Courir', NULL),
(23, 'Cuisinella', NULL),
(24, 'Darty ', 'darty.jpg'),
(25, 'Decathlon', NULL),
(26, 'E.Lelerc', NULL),
(27, 'Electro Depot', NULL),
(28, 'Etam', NULL),
(29, 'Feu Vert', NULL),
(30, 'Fnac', NULL),
(31, 'Foot locker', NULL),
(32, 'Galerie Lafayette', NULL),
(33, 'Gamm Vert', NULL),
(34, 'Geant Casino', NULL),
(35, 'Gemo', NULL),
(36, 'Geox', NULL),
(37, 'Gifi', NULL),
(38, 'Go sport', NULL),
(39, 'Histoire d\'or', NULL),
(40, 'Hyper U', NULL),
(41, 'Ikea', NULL),
(42, 'Intermarche', NULL),
(43, 'Intersport', NULL),
(44, 'Jardiland', NULL),
(45, 'JD sport', NULL),
(46, 'JouetClub', NULL),
(47, 'L\'occitane', NULL),
(48, 'La boutique du coiffeur', NULL),
(49, 'La foir\'Foulle', NULL),
(50, 'La grande recre', NULL),
(51, 'La halle', NULL),
(52, 'Lapeyre', NULL),
(53, 'LEGO', NULL),
(54, 'Leroymerlin', NULL),
(55, 'MAC', NULL),
(56, 'Maison du Monde', NULL),
(57, 'Marc Orian', NULL),
(58, 'Marionnaud', NULL),
(59, 'Micromania', NULL),
(60, 'Monoprix', NULL),
(61, 'Mr Bircolage ', NULL),
(62, 'Nature et decouvertes', NULL),
(63, 'Nespresso', NULL),
(64, 'Nocibe', NULL),
(65, 'Norauto', NULL),
(66, 'Paris saint gramin', NULL),
(67, 'Plus de foot', NULL),
(68, 'Printemps', NULL),
(69, 'Samsung', NULL),
(70, 'Schmidt', NULL),
(71, 'Sephora', NULL),
(72, 'Sport 2000', NULL),
(73, 'Truffaut', NULL),
(74, 'Yves Rocher ', NULL),
(75, 'Zara', NULL),
(76, 'Casa', NULL),
(77, 'Ixina', NULL),
(78, 'Mobalpa', NULL),
(79, 'Gautier', NULL),
(80, 'Bo concept', NULL),
(81, 'Roche bobois', NULL),
(82, 'Ligne Roset', NULL),
(83, 'Made', NULL),
(84, 'Alice Delice ', NULL),
(85, 'Zodio', NULL),
(86, 'Du bruit dans la cuisine', NULL),
(87, 'Giitem', NULL);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `enseignes`
--
ALTER TABLE `enseignes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `enseignes`
--
ALTER TABLE `enseignes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
