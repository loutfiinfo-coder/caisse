-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 02 mars 2026 à 16:39
-- Version du serveur : 9.1.0
-- Version de PHP : 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `caisse`
--

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

DROP TABLE IF EXISTS `caisse`;
CREATE TABLE IF NOT EXISTS `caisse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `typeentree` varchar(255) NOT NULL,
  `dateop` date NOT NULL,
  `client` varchar(255) NOT NULL,
  `montant` varchar(255) NOT NULL,
  `libelle` text NOT NULL,
  `date_piece` date NOT NULL,
  `ref_piece` varchar(255) NOT NULL,
  `fournisseur` varchar(255) NOT NULL,
  `concerne` varchar(255) NOT NULL,
  `remarque` text NOT NULL,
  `idfamille` int NOT NULL,
  `idsuccursale` varchar(200) NOT NULL,
  `valide` varchar(255) NOT NULL DEFAULT 'non',
  `acomptabilise` varchar(255) NOT NULL DEFAULT 'oui',
  `type_alimentation` int NOT NULL,
  `naturesortie` varchar(255) NOT NULL,
  `miseadisposition` text NOT NULL,
  `caissier` varchar(255) NOT NULL,
  `mad_agence` varchar(255) NOT NULL,
  `mad_compte` varchar(255) NOT NULL,
  `mad_beneficiaire` varchar(255) NOT NULL,
  `mad_cin` varchar(255) NOT NULL,
  `deleted` varchar(10) NOT NULL DEFAULT 'non',
  `deleter` varchar(255) NOT NULL,
  `numdemande` varchar(255) NOT NULL,
  `numpiececaisse` varchar(255) NOT NULL,
  `chantier` varchar(255) NOT NULL,
  `idfamillevente` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idcaisse` int NOT NULL,
  `urldoc` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `famille`
--

DROP TABLE IF EXISTS `famille`;
CREATE TABLE IF NOT EXISTS `famille` (
  `id` int NOT NULL AUTO_INCREMENT,
  `famille` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `famille`
--

INSERT INTO `famille` (`id`, `famille`) VALUES
(4, 'Transfert entre caisses');

-- --------------------------------------------------------

--
-- Structure de la table `famillevente`
--

DROP TABLE IF EXISTS `famillevente`;
CREATE TABLE IF NOT EXISTS `famillevente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `famille` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `miseadisposition`
--

DROP TABLE IF EXISTS `miseadisposition`;
CREATE TABLE IF NOT EXISTS `miseadisposition` (
  `miseadisposition` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `miseadisposition`
--

INSERT INTO `miseadisposition` (`miseadisposition`) VALUES
('<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CASABLANCA , le [dateop]</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; A Monsieur le Directeur de&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;[mad_agence]</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</p><p><b>N°:</b>&nbsp; &nbsp; [numero]</p><p><b>OBJET:</b>&nbsp; &nbsp; Mise à&nbsp; Disposition</p><p>Monsieur Le Directeur,</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Nous vous prions de bien vouloir débiter notre compte N° <b>[mad_compte]</b></p><p>Pour mettre à&nbsp; la disposition à&nbsp; l\'ordre de Monsieur:</p><p><b>[mad_beneficiaire]</b> titulaire de la carte d\'identité nationale N°<b> [mad_cin]</b></p><p>Un montant de<b> [montant]</b></p><p>Soit&nbsp; <b>[montant_en_lettre]</b></p><p>Et ce pour réglement: <b>[libelle]</b></p><p>Veuillez agréer Monsieur le directeur nos salutations les plus distinguées</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ........................</p>');

-- --------------------------------------------------------

--
-- Structure de la table `succursale`
--

DROP TABLE IF EXISTS `succursale`;
CREATE TABLE IF NOT EXISTS `succursale` (
  `id` int NOT NULL AUTO_INCREMENT,
  `succursale` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `datelastcloture` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `type_alimentation_caisse`
--

DROP TABLE IF EXISTS `type_alimentation_caisse`;
CREATE TABLE IF NOT EXISTS `type_alimentation_caisse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `type_alimentation_caisse`
--

INSERT INTO `type_alimentation_caisse` (`id`, `type`) VALUES
(4, 'Associé'),
(5, 'Mise à  disposition'),
(6, 'Remboursement'),
(7, 'Transfert entre caisses'),
(8, 'chèque');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pages` text NOT NULL,
  `admin` varchar(10) NOT NULL DEFAULT 'non',
  `ste` text NOT NULL,
  `session` varchar(100) NOT NULL DEFAULT 'non',
  `supression` varchar(100) NOT NULL DEFAULT 'non',
  `modification` varchar(100) NOT NULL DEFAULT 'non',
  `justification` varchar(100) NOT NULL DEFAULT 'non',
  `acomptabilise` varchar(255) NOT NULL,
  `editdocument` varchar(100) NOT NULL DEFAULT 'non',
  `boncaisse` varchar(10) NOT NULL DEFAULT 'non',
  `editdateop` varchar(255) NOT NULL,
  `exportexcel` varchar(255) NOT NULL,
  `colonnecaisse` text NOT NULL,
  `colonnesortie` text NOT NULL,
  `colonneentree` text NOT NULL,
  `cloturercaisse` varchar(255) NOT NULL,
  `transfertcaisse` varchar(255) NOT NULL,
  `showdeletedoperations` varchar(255) NOT NULL,
  `fermeturercaisse` varchar(255) NOT NULL,
  `affichersolde` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `pages`, `admin`, `ste`, `session`, `supression`, `modification`, `justification`, `acomptabilise`, `editdocument`, `boncaisse`, `editdateop`, `exportexcel`, `colonnecaisse`, `colonnesortie`, `colonneentree`, `cloturercaisse`, `transfertcaisse`, `showdeletedoperations`, `fermeturercaisse`, `affichersolde`) VALUES
(16, 'admin', 'admin', 'famille, societe, sortie, entree, ', '', '', '', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'oui', 'caisse|reference|dateop|libelle|nature_entree|type_alimentation|famille|client|beneficiaire|notes|caissier|', 'caisse|reference|dateop|libelle|famille|beneficiaire|notes|acomptabilise|justifie|documents|', 'caisse|reference|dateop|libelle|nature_entree|type_alimentation|client|notes|acomptabilise|justifie|documents|', 'oui', 'oui', 'non', 'oui', 'oui');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
