-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 06 Juin 2016 à 11:16
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `apu_webspace`
--

-- --------------------------------------------------------

--
-- Structure de la table `coefficient`
--

CREATE TABLE IF NOT EXISTS `coefficient` (
  `id_grade_module` int(10) NOT NULL AUTO_INCREMENT,
  `grade_type` varchar(3) DEFAULT NULL,
  `id_module3` int(10) DEFAULT NULL,
  `coefficient` float DEFAULT NULL,
  PRIMARY KEY (`id_grade_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

--
-- Contenu de la table `coefficient`
--

INSERT INTO `coefficient` (`id_grade_module`, `grade_type`, `id_module3`, `coefficient`) VALUES
(22, 'LEC', 1, 0.5),
(23, 'LAB', 1, 0.25),
(27, 'PRJ', 1, 0.25),
(28, 'LEC', 6, 0.5),
(29, 'LAB', 6, 0.5),
(31, 'LEC', 5, 0.5),
(32, 'LAB', 5, 0.5),
(33, 'LEC', 9, 0.5),
(34, 'LAB', 9, 0.25),
(35, 'PRJ', 9, 0.25),
(36, 'LEC', 10, 0.5),
(37, 'LAB', 10, 0.5),
(38, 'LEC', 11, 1),
(39, 'LEC', 12, 0.5),
(40, 'PRJ', 12, 0.5),
(41, 'PRJ', 13, 1),
(42, 'LAB', 14, 1);

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

CREATE TABLE IF NOT EXISTS `grade` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `student_id` int(10) NOT NULL,
  `grade_id` int(10) NOT NULL,
  `grade` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `grade`
--

INSERT INTO `grade` (`id`, `student_id`, `grade_id`, `grade`) VALUES
(1, 1, 22, 100),
(2, 2, 22, 100),
(5, 1, 28, 80),
(6, 2, 29, 50),
(7, 1, 31, 85),
(8, 1, 29, 84);

-- --------------------------------------------------------

--
-- Structure de la table `intake`
--

CREATE TABLE IF NOT EXISTS `intake` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `intake`
--

INSERT INTO `intake` (`id`, `name`) VALUES
(1, 'UCFEFREI1603'),
(5, 'UC1F1509ACS'),
(6, 'AFCF1604AS');

-- --------------------------------------------------------

--
-- Structure de la table `intake_student`
--

CREATE TABLE IF NOT EXISTS `intake_student` (
  `id_student4` int(8) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_student4`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `intake_student`
--

INSERT INTO `intake_student` (`id_student4`, `id_groupe`) VALUES
(1, 1),
(2, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id_module` bigint(10) NOT NULL AUTO_INCREMENT,
  `name_module` varchar(255) DEFAULT NULL,
  `id_group2` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `module`
--

INSERT INTO `module` (`id_module`, `name_module`, `id_group2`) VALUES
(1, 'Databases', 1),
(5, 'Network', 1),
(6, 'C++', 1),
(9, 'Graph Theory', 1),
(10, 'Optimization and Complexity', 1),
(11, 'Communication', 1),
(12, 'Operating Systems', 1),
(13, 'Web Programming', 1),
(14, 'Test', 1);

-- --------------------------------------------------------

--
-- Structure de la table `professor`
--

CREATE TABLE IF NOT EXISTS `professor` (
  `id_professor` int(10) NOT NULL AUTO_INCREMENT,
  `name_professor` varchar(255) DEFAULT NULL,
  `surname_professor` varchar(255) DEFAULT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`id_professor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `professor`
--

INSERT INTO `professor` (`id_professor`, `name_professor`, `surname_professor`, `password`) VALUES
(2, 'Ouerdane', 'Safia', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
(3, 'Lahlou', 'Karim', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
(4, 'Sicard', 'Nicolas', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
(5, 'Teller', 'Patrick', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
(6, 'Soma', 'Jean', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
(7, 'Barbot', 'Hervé', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
(8, 'Padmakumar', 'Dhason', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW');

-- --------------------------------------------------------

--
-- Structure de la table `responsible`
--

CREATE TABLE IF NOT EXISTS `responsible` (
  `name_responsible` varchar(255) DEFAULT NULL,
  `surname_responsible` varchar(255) DEFAULT NULL,
  `mobile_responsible` int(10) DEFAULT NULL,
  `email_responsible` varchar(255) DEFAULT NULL,
  `street_responsible` varchar(255) DEFAULT NULL,
  `cp_responsible` bigint(5) DEFAULT NULL,
  `city_responsible` varchar(255) DEFAULT NULL,
  `student_id` int(10) NOT NULL,
  `id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `responsible`
--

INSERT INTO `responsible` (`name_responsible`, `surname_responsible`, `mobile_responsible`, `email_responsible`, `street_responsible`, `cp_responsible`, `city_responsible`, `student_id`, `id`) VALUES
('Poupa', 'David', 626752813, 'david@poupa.fr', '35 rue de la Parole', 75000, 'Paris', 1, 1),
('Buob', 'Laurent', 627165382, 'laurent@buob.fr', '32 Boulevard Raspail', 75014, 'Paris', 2, 2),
('Barbot', 'Hervé', 92872728, 'herve@barbot.fr', '34 avenue de la Joie', 34000, 'Montpellier', 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `registered` date DEFAULT NULL,
  `previous_school` varchar(255) DEFAULT NULL,
  `photo` blob,
  `street` varchar(255) DEFAULT NULL,
  `cp` int(5) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `student_id` int(8) NOT NULL AUTO_INCREMENT,
  `tp_number` varchar(8) NOT NULL,
  `password` text NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `student`
--

INSERT INTO `student` (`name`, `surname`, `birthdate`, `gender`, `registered`, `previous_school`, `photo`, `street`, `cp`, `city`, `mobile`, `phone`, `student_id`, `tp_number`, `password`) VALUES
('Poupa', 'Adrien', '1995-04-30', 'M', '2015-09-01', 'Classe préparatoire', NULL, '30 avenue de la République', 94800, 'Villejuif', '0692771632', '', 1, 'TP040860', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
('Buob', 'Edgar', '1995-02-01', 'M', '2015-09-02', 'ORT', NULL, '30 avenue de la République', 75014, 'Paris', '0623456789', '', 2, 'TP040261', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
('Crockett ', 'Davy', '1900-01-01', 'M', '1900-01-01', 'Aucun', NULL, '30 avenue de la République', 75001, 'Paris', '0627157381', '', 3, 'TP042615', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW'),
('Barbot', 'Timothée', '1993-07-22', 'M', '2015-09-01', 'IUT Villetanuse', NULL, '30 avenue de la République', 94800, 'Villejuif', '0627153847', '', 4, 'TP046218', '$2y$10$fmV6Z11GX6xvrqL84GhyDeOmMxTfUb39B84Sk5ArQiwdnG4ALe9yW');

-- --------------------------------------------------------

--
-- Structure de la table `teaches_module`
--

CREATE TABLE IF NOT EXISTS `teaches_module` (
  `id_professor` int(10) NOT NULL AUTO_INCREMENT,
  `id_module` bigint(10) NOT NULL,
  PRIMARY KEY (`id_professor`,`id_module`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `teaches_module`
--

INSERT INTO `teaches_module` (`id_professor`, `id_module`) VALUES
(2, 1),
(3, 1),
(3, 5),
(3, 8),
(4, 2),
(4, 6),
(4, 7),
(4, 8),
(4, 14),
(5, 10),
(6, 11),
(7, 9),
(8, 12);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
