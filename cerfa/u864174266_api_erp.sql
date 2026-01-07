-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 07 fév. 2025 à 15:51
-- Version du serveur : 10.11.10-MariaDB
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u864174266_api_erp`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonement_cerfa`
--

CREATE TABLE `abonement_cerfa` (
  `id` int(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `quantite` int(255) NOT NULL,
  `id_produit` int(255) NOT NULL,
  `id_users` int(255) DEFAULT NULL,
  `id_centre` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `abonement_cerfa`
--

INSERT INTO `abonement_cerfa` (`id`, `date_debut`, `date_fin`, `quantite`, `id_produit`, `id_users`, `id_centre`) VALUES
(32, '2024-08-23', '2025-08-23', 94, 2, NULL, 38),
(33, '2024-10-03', '2024-10-03', 93, 3, NULL, 38),
(34, '2024-09-02', '2024-10-02', 2, 2, NULL, 2),
(35, '2024-09-02', '2024-09-02', 2, 3, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

CREATE TABLE `absences` (
  `id` int(11) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date DEFAULT NULL,
  `raison` varchar(250) DEFAULT NULL,
  `justificatif` varchar(250) DEFAULT NULL,
  `id_etudiants` int(11) NOT NULL,
  `dateCreation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `lieu_travail` varchar(50) DEFAULT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id`, `firstname`, `lastname`, `telephone`, `lieu_travail`, `id_users`) VALUES
(25, 'Jérôme', 'LAGNEAUX', NULL, NULL, 230),
(31, 'Victor', 'Hu', '0606060607', NULL, 259),
(34, 'Administrateur', 'LGX', NULL, '', 267),
(35, 'Marie', 'RUEDA', NULL, NULL, 269);

-- --------------------------------------------------------

--
-- Structure de la table `centres_de_formation`
--

CREATE TABLE `centres_de_formation` (
  `id` int(11) NOT NULL,
  `nomCentre` varchar(50) NOT NULL,
  `adresseCentre` varchar(50) NOT NULL,
  `codePostalCentre` varchar(50) NOT NULL,
  `villeCentre` varchar(50) NOT NULL,
  `telephoneCentre` varchar(50) NOT NULL,
  `id_entreprises` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `centres_de_formation`
--

INSERT INTO `centres_de_formation` (`id`, `nomCentre`, `adresseCentre`, `codePostalCentre`, `villeCentre`, `telephoneCentre`, `id_entreprises`) VALUES
(38, 'LGX Campus', '32, quai d\'Allier', '03200', 'Vichy', '', 166);

-- --------------------------------------------------------

--
-- Structure de la table `cerfa`
--

CREATE TABLE `cerfa` (
  `id` int(11) NOT NULL,
  `nomA` tinytext DEFAULT NULL,
  `nomuA` tinytext DEFAULT NULL,
  `prenomA` tinytext DEFAULT NULL,
  `sexeA` tinytext DEFAULT NULL,
  `naissanceA` tinytext DEFAULT NULL,
  `departementA` tinytext DEFAULT NULL,
  `communeNA` tinytext DEFAULT NULL,
  `nationaliteA` tinytext DEFAULT NULL,
  `regimeA` tinytext DEFAULT NULL,
  `situationA` tinytext DEFAULT NULL,
  `titrePA` tinytext DEFAULT NULL,
  `derniereCA` tinytext DEFAULT NULL,
  `securiteA` tinytext DEFAULT NULL,
  `intituleA` tinytext DEFAULT NULL,
  `titreOA` tinytext DEFAULT NULL,
  `declareSA` tinytext DEFAULT NULL,
  `declareHA` tinytext DEFAULT NULL,
  `declareRA` tinytext DEFAULT NULL,
  `rueA` tinytext DEFAULT NULL,
  `voieA` tinytext DEFAULT NULL,
  `complementA` tinytext DEFAULT NULL,
  `postalA` tinytext DEFAULT NULL,
  `communeA` tinytext DEFAULT NULL,
  `numeroA` tinytext DEFAULT NULL,
  `emailA` tinytext DEFAULT NULL,
  `nomR` tinytext DEFAULT NULL,
  `prenomR` varchar(255) DEFAULT NULL,
  `emailR` tinytext DEFAULT NULL,
  `rueR` tinytext DEFAULT NULL,
  `voieR` tinytext DEFAULT NULL,
  `complementR` tinytext DEFAULT NULL,
  `postalR` tinytext DEFAULT NULL,
  `communeR` tinytext DEFAULT NULL,
  `nomM` tinytext DEFAULT NULL,
  `prenomM` tinytext DEFAULT NULL,
  `naissanceM` tinytext DEFAULT NULL,
  `securiteM` tinytext DEFAULT NULL,
  `emailM` tinytext DEFAULT NULL,
  `emploiM` tinytext DEFAULT NULL,
  `diplomeM` tinytext DEFAULT NULL,
  `niveauM` tinytext DEFAULT NULL,
  `nomM1` tinytext DEFAULT NULL,
  `prenomM1` tinytext DEFAULT NULL,
  `naissanceM1` tinytext DEFAULT NULL,
  `securiteM1` tinytext DEFAULT NULL,
  `emailM1` tinytext DEFAULT NULL,
  `emploiM1` tinytext DEFAULT NULL,
  `diplomeM1` tinytext DEFAULT NULL,
  `niveauM1` tinytext DEFAULT NULL,
  `travailC` tinytext DEFAULT NULL,
  `modeC` varchar(255) DEFAULT NULL,
  `derogationC` tinytext DEFAULT NULL,
  `numeroC` tinytext DEFAULT NULL,
  `conclusionC` tinytext DEFAULT NULL,
  `debutC` tinytext DEFAULT NULL,
  `finC` tinytext DEFAULT NULL,
  `avenantC` tinytext DEFAULT NULL,
  `executionC` tinytext DEFAULT NULL,
  `dureC` tinytext DEFAULT NULL,
  `dureCM` varchar(255) DEFAULT NULL,
  `typeC` tinytext DEFAULT NULL,
  `rdC` tinytext DEFAULT NULL,
  `raC` tinytext DEFAULT NULL,
  `rpC` tinytext DEFAULT NULL,
  `rsC` tinytext DEFAULT NULL,
  `rdC1` tinytext DEFAULT NULL,
  `raC1` tinytext DEFAULT NULL,
  `rpC1` tinytext DEFAULT NULL,
  `rsC1` tinytext DEFAULT NULL,
  `rdC2` tinytext DEFAULT NULL,
  `raC2` tinytext DEFAULT NULL,
  `rpC2` tinytext DEFAULT NULL,
  `rsC2` tinytext DEFAULT NULL,
  `salaireC` tinytext DEFAULT NULL,
  `caisseC` tinytext DEFAULT NULL,
  `logementC` tinytext DEFAULT NULL,
  `avantageC` tinytext DEFAULT NULL,
  `autreC` tinytext DEFAULT NULL,
  `lieuO` tinytext DEFAULT NULL,
  `priveO` tinytext DEFAULT NULL,
  `attesteO` tinytext DEFAULT 'oui',
  `numeroInterne` varchar(255) DEFAULT NULL,
  `numeroExterne` varchar(255) DEFAULT NULL,
  `numeroDeca` varchar(255) DEFAULT NULL,
  `numeroInterneFacture2` varchar(255) NOT NULL,
  `numeroInterneFacture3` varchar(255) NOT NULL,
  `numeroInterneDocument2` varchar(255) NOT NULL,
  `numeroInterneDocument3` varchar(255) NOT NULL,
  `conventionOpco` varchar(255) DEFAULT NULL,
  `cerfaOpco` varchar(255) DEFAULT NULL,
  `factureOpco` varchar(255) DEFAULT NULL,
  `signatureEmployeur` varchar(255) DEFAULT NULL,
  `signatureApprenti` varchar(255) DEFAULT NULL,
  `signatureRepresentantApprenti` varchar(255) NOT NULL,
  `signatureEcole` varchar(255) DEFAULT NULL,
  `signatureConventionEmployeur` varchar(255) DEFAULT NULL,
  `signatureConventionEcole` varchar(255) DEFAULT NULL,
  `date_creation` date NOT NULL DEFAULT current_timestamp(),
  `idemployeur` int(255) NOT NULL,
  `idformation` int(255) NOT NULL,
  `id_centre` int(255) DEFAULT NULL,
  `id_users` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `cerfa`
--

INSERT INTO `cerfa` (`id`, `nomA`, `nomuA`, `prenomA`, `sexeA`, `naissanceA`, `departementA`, `communeNA`, `nationaliteA`, `regimeA`, `situationA`, `titrePA`, `derniereCA`, `securiteA`, `intituleA`, `titreOA`, `declareSA`, `declareHA`, `declareRA`, `rueA`, `voieA`, `complementA`, `postalA`, `communeA`, `numeroA`, `emailA`, `nomR`, `prenomR`, `emailR`, `rueR`, `voieR`, `complementR`, `postalR`, `communeR`, `nomM`, `prenomM`, `naissanceM`, `securiteM`, `emailM`, `emploiM`, `diplomeM`, `niveauM`, `nomM1`, `prenomM1`, `naissanceM1`, `securiteM1`, `emailM1`, `emploiM1`, `diplomeM1`, `niveauM1`, `travailC`, `modeC`, `derogationC`, `numeroC`, `conclusionC`, `debutC`, `finC`, `avenantC`, `executionC`, `dureC`, `dureCM`, `typeC`, `rdC`, `raC`, `rpC`, `rsC`, `rdC1`, `raC1`, `rpC1`, `rsC1`, `rdC2`, `raC2`, `rpC2`, `rsC2`, `salaireC`, `caisseC`, `logementC`, `avantageC`, `autreC`, `lieuO`, `priveO`, `attesteO`, `numeroInterne`, `numeroExterne`, `numeroDeca`, `numeroInterneFacture2`, `numeroInterneFacture3`, `numeroInterneDocument2`, `numeroInterneDocument3`, `conventionOpco`, `cerfaOpco`, `factureOpco`, `signatureEmployeur`, `signatureApprenti`, `signatureRepresentantApprenti`, `signatureEcole`, `signatureConventionEmployeur`, `signatureConventionEcole`, `date_creation`, `idemployeur`, `idformation`, `id_centre`, `id_users`) VALUES
(61, 'DEROZIER', '', 'Elise', 'F', '2005-02-17', 'Dordogne', 'BERGERAC', '1', '2', '10', '41', '/', '205022403708476', 'BAC PRO METIERS DE LA SECURITE', '41', 'non', 'non', 'non', '27', 'rue Neuve d’Argenson', '', '24100', 'BERGERAC', '', 'derozierelise@gmail.com', '', NULL, '', '', '', '', '', '', 'VILLEFOURCEIX', 'Frédéric', '1978-01-30', '178013306352912', 'fr-chris@orange.com', 'SSIAP 2', 'SSIAP 2', '4', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(62, 'CHADHOULI', '', 'Akram', '', '2003-10-07', '', 'MAMOUDZOU', '', '', 'stagiaire RSMA', 'A2SP/ SSIAP1', '3e ', '103109851109927', 'A2SP', 'A2SP', 'non', 'non', 'non', '17', 'IMPASSE CHATEAU D\'EAU', '', '97610', ' DZAOUDZI ', ' 06 39 02 55 70', 'akram.chadhouli@gmail.com', '', NULL, '', '', '', '', '', '', 'ZUBILLAGA', 'Céline', '1980-06-12', '2 80 06 81 060 013 30', 'contact@ageconsulting.fr', 'Responsable de centre', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', NULL, '', '', '', '43', '', '', '', '', '', '', '', '', '', '759.7756', '', '', '', '', '', '', '', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 6, 38, NULL),
(63, 'DHUYAOU', '', 'Melina', '', '2003-09-25', '', 'MAMOUDZOU', '', '', 'stagiaire RSMA', 'A2SP / SSIAP1', 'CAP', '203099851107285', 'CAP APS', 'CAP APS', 'non', 'non', 'non', '4', 'CHEMIN MOINECHA ZAKARIA  ', '', '97600', 'MAMOUDZOU', '06 92 67 42 94 ', 'youssoufmelina7@gmail.com', '', NULL, '', '', '', '', '', '', 'PLUTON', 'Thierry', '1979-06-06', '1 79 06 75 120 050 06', 't.pluton@altair-securite.fr', 'Responsable exploitation', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', NULL, '', '', '', '43', '', '', '', '', '', '', '', '', '', '759.7756', '', '', '', '', '', '', '', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 6, 38, NULL),
(64, 'HOUMADI', '', 'Dhoulihedje', '', '1998-04-07', '', 'TSINGONI', '', '', 'stagiaire RSMA', 'A2SP/ SSIAP1', '3e ', '198049850200802', 'A2SP', 'A2SP', '', '', '', '30', 'rue Madi Boina  ', '', '97650', 'BANDRABOUA', '06 39 04 38 24 ', 'dhoulhedenissoine@gmail.com', '', NULL, '', '', '', '', '', '', 'OUAZAR', 'Abdelghani', '1980-02-09', '1 80 02 99 352 656 21', 'a.ouazar@altair-securite.fr', 'Responsable d\'exploitation', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', NULL, '', '', '', '100', '', '', '', '100', '', '', '', '100', '', '1766.92', '', '', '', '', '', '', '', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 6, 38, NULL),
(65, 'M\'KIDADI', '', 'Nazou', '', '2004-03-26', '', 'MTSAMBORO', '', '', 'stagiaire RSMA', 'A2SP / SSIAP1', '3e ', '204039851201564', 'A2SP', 'A2SP', '', '', '', '1028', 'ROUTE NATIONAL ', '', '97630', 'HAMJAGO', '06 39 39 57 71', 'nazoumkidadi37@gmail.com', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', NULL, '', '', '', '43', '', '', '', '', '', '', '', '', '', '759.7756', '', '', '', '', '', '', '', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 6, 38, NULL),
(66, 'ZITIMBI', '', 'Rachica', '', '2002-07-04', '', 'MAMOUDZOU', '', '', 'stagiaire RSMA', 'A2SP/ SSIAP1', 'terminale bac pro restauration', '202079851108193', 'A2SP', 'A2SP /  BAC PRO RESTAURATION', '', '', '', '60', ' RUE AMBASSADEURE  M\'TSAPERE ', '', '97600', 'MAMOUDZOU', '06 39 66 71 69', 'Zitimbirachica@gmail.com', '', NULL, '', '', '', '', '', '', 'GALBON', 'Loic', '1986-09-09', '1 86 09 75 114 225 07', 'loic.galbon@altair-securite.fr', 'Responsable d\'exploitation', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '', '', '', '', '', '', NULL, '', '', '', '53', '', '', '', '', '', '', '', '', '', '936.4676', '', '', '', '', 'BORDEAUX', '', '', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 6, 38, NULL),
(68, 'NIETO', '', '', 'M', '1995-10-21', 'Seine-Saint-Denis', 'BONDY', '1', '2', '10', '49', '', '195109301029908', 'SSIAP2', '49', 'non', 'non', 'non', '3', 'rue Edgard Degas', '', '33270', 'FLOIRAC', '', 'Valentin.nieto@hotmail.fr', '', NULL, '', '', '', '', '', '', 'BARBUT', 'Erick ', '1971-10-30', '171102432218311', 'akosecurite@gmail.com', 'Responsable d\'exploitation', 'SSIAP3', '4', '', '', '', '', '', '', '', '', '', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '2513.25', 'AG2R', '', '1', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(69, 'MARTIN', '', 'Lucas', 'M', '2004-03-07', 'GIRONDE', 'LIBOURNE', '1', '2', '10', '38', '', '1 04 03 33 243 266 85', 'SSIAP1', '38', 'non', 'non', 'non', '1244', ' AVENUE DES VIGNERONS', '', '24610', 'VILLEFRANCHE DE LONCHAT', '', 'lucas24martin@gmail.com', '', NULL, '', '', '', '', '', '', 'PETIT ', 'Pascal', '1961-01-20', '1 61 01 33 522 065 52', 'pascal.petit58@orange.fr', 'SSIAP2', 'SSIAP2', '4', '', '', '', '', '', '', '', '', '', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(70, 'BERTRAND', '', 'Maxence', 'M', '2002-07-11', '33', 'Bordeaux', '1', '2', '10', '38', '1', '102073306390957', 'TFP APS', '38', 'non', 'non', 'non', '9', 'RUE SAINT VALENTIN', '', '33310', 'LORMONT', '0753868020', 'm.bertrand33310@gmail.com', '', '', '', '', '', '', '', '', 'CORNEAU', 'Thomas', '1983-09-26', '1830916374076', 'Thomas.corneau@hotmail.fr', 'Chef de poste', 'SSIAP1', '1', '', '', '', '', '', '', '', '', 'non', '1', '11', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', '12', '11', '2024-05-01', '2025-05-31', '100', 'SMIC', '2024-07-10', '', '', 'SMIC', '', '', '', 'SMIC', '1852.95        ', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2609236', '2409CA034627', '033202410125490', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/26259855966ed7556974b34.40055066124504051066607647c860d8.52182034conventionBertrand.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/109161442366ed750e370ed5.1812855076700112866608c5290dba8.13973450CONTRATS  BERTRAND.pdf', ' https://lgx-solution.fr/cerfa/public/assets/factureOpco/70dossier_prise_en_charge.pdf', '', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/41686300066f61a435b9db2.65246309.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/11281447336710d534c35625.65443225SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/1668698606710d53fbb1b17.83452590SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-02', 7, 6, 38, NULL),
(71, 'CORNILLIER ', '', 'Anaelle', 'F', '1999-06-04', 'GIRONDE', 'BRUGES', '1', '2', '10', '38', '', '299063307522821', 'SSIAP1', '38', 'non', 'non', 'non', '1', 'rue Camille St Saens', '', '33520', ' BRUGES', '', 'anaellecornillier@gmail.com', '', NULL, '', '', '', '', '', '', 'TEULET', 'Jérôme ', '1975-06-23', '1 75 06 33 243 099 / 22', 'jtnoa@yahoo.fr', 'Chef de poste', 'SSIAP2', '4', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(72, 'DUBUS', '', 'Esteban', 'M', '1997-03-31', 'GIRONDE', 'LEBOURNE', '1', '2', '10', '38', '', '1 97 03 33 243 335 18', 'SSIAP1', '38', 'non', 'non', 'non', '7', 'RUE PASSERIEUX', '', '24100', 'BERGERAC', '', 'dubusesteban@gmail.com', '', NULL, '', '', '', '', '', '', ' BAZIR', 'Ludovic ', '1984-07-07', '1 84 07 78 646 072 / 93', 'afrolucho@gmail.com', 'Chef de poste', 'SSIAP 2', '4', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(73, 'PEIGNE', '', 'Lucas', 'M', '2000-06-18', 'MARNE', 'VITRY LE FRANCOIS', '1', '2', '10', '49', '', '1 00 06 51 649 247 01', 'SSIAP2', '49', 'non', 'non', 'non', '63', 'COURS DU MEDOC', '', '33300', 'BORDEAUX', '', 'lucas.peigne@gmail.com', '', NULL, '', '', '', '', '', '', ' TEULET', 'Jérôme', '1975-06-23', '1 75 06 33 243 099 / 22', 'jtnoa@yahoo.fr', 'Chef de poste', 'SSIAP2', '4', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '2106.05', 'AG2R', '', '', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(76, 'FOUMENAIGUE ', '', 'Felix', 'M', '2000-08-01', 'Seine-Saint-Denis', 'saint denis', '1', '2', '10', '38', '', '100089741186872', 'SSIAP1', '38', 'non', 'non', 'non', '', 'Rue Marcel Cerdan', '', '33310', 'LORMONT', '', 'foumenaiguefelix032@gmail.com', '', NULL, '', '', '', '', '', '', 'CORNEAU', 'Thomas', '1983-09-26', '1 83 09 16 374 076 / 13', 'thomas.corneau@hotmail.fr', 'Chef de poste', 'SSIAP1', '3', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-04-30', '2024-05-01', '2025-05-31', '', '2024-05-01', '35', NULL, '11', '2024-05-01', '2025-05-31', '100', 'smic', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 7, 6, 38, NULL),
(77, 'DANIEL', '', 'Aliya', 'F', '1996-12-15', '976', 'Bandraboua', '1', '2', '11', '38', '2018', '2 96 12 98 502 017 72', 'CAP APS', '38', 'non', 'non', 'non', '8', 'Boulevard jean moulin', 'APPT 52 LES CERISIERS', '16000', 'AGOULEME', '07 83 22 07 81', 'malka976@hotmail.com', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-06-03', '2024-06-03', '2025-06-30', '', '2024-06-03', '35', NULL, '11', '2024-06-03', '2025-06-30', '100', 'smic', '', '', '100', '', '', '', '100', '', '1766.92', '', '', '', 'non', 'ECHILLAIS', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 20, 38, NULL),
(78, 'HALIDI', '', 'Machianlidine', 'M', '2003-02-19', '976', 'TSINGONI', '1', '2', '10', '49', '01', '1 03 02 98 517 004 08', 'A2SP', '42', 'non', 'non', 'oui', '22', 'Rue Bamana', '', '97670', 'OUANGANI', '06 39 99 39 38', 'halidimacha94@gmail.com', '', NULL, '', '', '', '', '', '', 'MOSEKA', ' Yolande', '1983-02-21', '2 83 02 99 312 005 26', 'contact@ageconsulting.fr', 'Assistante administrative', '', '', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-06-03', '2024-06-03', '2025-06-30', '', '2024-06-03', '35', NULL, '11', '2024-06-03', '2025-06-30', '100', 'smic', '', '', '', '', '', '', '', '', '1766.92', '', '', '', 'non', 'ECHILLAIS', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 20, 38, NULL),
(79, 'MLOZA', '', 'Djadid', 'M', '1999-12-31', '976', 'MAMOUDZOU', '1', '2', '10', '49', '01', '1 99 12 98 511 102 08', 'A2SP', '33', 'non', 'non', 'oui', '23', ' Rue Guitro lava', '', '97670', 'OUANGANI', '06 39 09 69 96', 'dididjadid@gmail.com', '', NULL, '', '', '', '', '', '', 'GUENIN ', 'Guillaume', '1974-12-25', '1 7412 78 545 069 10', 'g.guenin@altair-securite.fr', 'Responsable commercial', '', '', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-06-03', '2024-06-03', '2025-06-30', '', '2024-06-03', '35', NULL, '11', '2024-06-03', '2025-06-30', '100', 'smic', '', '', '', '', '', '', '', '', '1766.92', '', '', '', 'non', 'ECHILLAIS', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 20, 38, NULL),
(80, 'ALLAOUI', '', 'Binti Raiza', 'F', '2002-12-13', '976', 'Bandrélé', '1', '2', '10', '49', '01', '2 02 12 98 503 007 02', '\"A2SP (portuaire) SSIAP 1\"', '49', 'non', 'non', 'non', '', 'Quartier 100 villas - Poroani', '', '97620', 'CHIRONGUI', '\"06 92 45 78 76 \"', 'bintyraiza@gmail.com', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-06-03', '2024-06-03', '2025-06-30', '', '2024-06-03', '', NULL, '', '2024-06-03', '2025-06-30', '100', 'smic', '', '', '', '', '', '', '', '', '1766.92', '', '', '', 'non', 'ECHILLAIS', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 23, 38, NULL),
(81, 'ALI', '', 'Niafou', '', '1998-04-11', '976', 'Bouéni', '1', '2', '11', '38', '2018', '', 'TFP APS + SSIAP ', '43', 'non', 'non', 'non', '', '', '', '97625', 'KENIKELI', '06 93 53 81 27', 'niafouali8@gmail.com', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, '', '', '2024-06-03', '2024-06-03', '2025-06-30', '', '2024-06-03', '', NULL, '', '2024-06-03', '2025-06-30', '100', 'smic', '', '', '', '', '', '', '', '', '1766.92', '', '', '', '', 'ECHILLAIS', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 19, 23, 38, NULL),
(82, 'BEGUE', 'BEGUE', 'ALEXANDRE', 'M', '1972-12-20', '91', 'Savigny-sur-Orge', '1', '2', '4', '49', '1', '172129158907184', 'Formation Formateur', '49', 'non', 'non', 'oui', '25', 'Passage de l\'Amirauté', '', '03200', 'VICHY', '0651537209', 'alexandre.begue.pro@hotmail.com', '', '', '', '', '', '', '', '', 'DIAT', 'CATHERINE', '1968-10-19', '268109202408634', 'C.diat@macc1.net', 'Responsable departement formation securite', 'Cafoc', '3', '', '', '', '', '', '', '', '', 'non', '1', '', '003202406019251', '2024-11-27', '2024-12-01', '2025-09-30', '', '2024-12-01', '35', '00', '23', '2024-12-01', '2025-09-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'IVRY SUR SEINE', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1055202189674836626cc564.02879495SignatureKD.jpeg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/629985407674969914de4a0.11053013.png', '', 'https://lgx-solution.fr/cerfa/public/assets/signatureEcole/882124632674989626154c3.06302444.png', NULL, NULL, '2024-09-02', 33, 32, 38, NULL),
(83, 'LAURENT', 'LAURENT', 'Merry', 'M', '1992-02-17', '60', 'MERU', '1 ', '2', '10', '54', '22', '1 92 02 60 395 221 19', 'BTS communication', '41', 'non', 'non', 'non', '2', 'Place de l\'église', '', '60650', 'Savignies', '0681872076', 'webmerrypro@gmail.com', '', NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'non', NULL, '', '', '2024-07-01', '2024-07-01', '2025-07-31', '', '2024-07-01', '35', NULL, '11', '2024-07-01', '2025-07-31', '100', 'SMIC', '', '', '100', '', '', '', '100', '', '1766.92', 'MALAKOFF MEDERIC', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-02', 22, 21, 38, NULL),
(84, 'SUTTER', 'DUBOIS', 'JESSICA', 'F', '1978-02-25', '60', 'BEAUVAIS', '1', '2', '4', '58', '12', '278026005713715', 'Titre profesionnel ARH', '58', 'non', 'oui', 'non', '18', 'ROUTE DE NESLE', '', '80400', 'HOMBLEUX', '0682408280', 'j.sutter78@laposte.net', '', '', '', '', '', '', '', '', 'LAGNEAUX', 'Jérôme', '1977-06-24', '177060217304303', 'Jerome@lgx-france.fr', 'Dirigeant', 'Titre de Dirigeant option OFS', '6', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-08-30', '2024-09-01', '2025-08-31', '', '2024-09-01', '35', '00', '22', '2024-09-01', '2025-08-31', '100', 'SMIC', '', '', '', '', '', '', '', '', '1766.92', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', '2609318', '2409CA034707', '003202411009797', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/84SUTTER.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/108412492666ed7922577f72.84124918SUTTER_Pdf_cerfas2024-09-20_29_45_1726838985.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/98613449266d5d19b4108d3.91467792TamponCampusavecsignaturepage00011.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/100813963866d06dd736bb53.47321804.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/177696288166d5773bde3171.86871045TamponCampusavecsignaturepage00011.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/210000627466d5d1368d89b6.59897583TamponCampusavecsignaturepage00011.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/53940513466d577a6336b07.01253083TamponCampusavecsignaturepage00011.png', '2024-09-02', 21, 22, 38, NULL),
(98, 'DE ROLAND', '', 'JACKY', 'M', '1992-12-30', '973', 'CAYENNE', '1', '2', '11', '38', '1', '192129730296958', 'TFP APS', '38', 'non', 'non', 'non', '', 'Domaine des Vivrets', '', '60490', 'Marquéglise', '0694079233', 'jackyderoland@gmail.com', '', '', '', '', '', '', '', '', ' HOXHA ', 'VETON', '1990-06-09', '190069912115641', 'Veton.hoxha@groupe-cf.fr', 'CHEF DE SITE', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-08-30', '2024-09-02', '2025-08-31', '', '2024-09-02', '35', '00', '11', '2024-09-02', '2025-08-31', '100', 'SMIC', '', '', '', 'SMIC', '', '', '', 'SMIC', '1798.99', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/98DE ROLAND.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/194229829766d067bf6210b5.51035463SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/204067130266d07a888ade50.40474233.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/212028305666d579cb38b895.40683265SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/137694607166d0684bbfcf42.37938944SignatureTamponBarbara.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/137223872766d579d640f529.97900280SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-02', 18, 6, 38, NULL),
(99, 'TI JOSEPH', '', 'BRYAN', 'M', '1996-07-03', '973', 'KOUROU', '1', '2', '11', '38', '1', '196079730480679', 'TFP APS', '38', 'non', 'non', 'non', '', 'Domaine des Vivrets', '', '60490', 'Marquéglise', '0694095952', 'bryantijoseph97310@gmail.com', '', '', '', '', '', '', '', '', 'HAJJOUTI ', 'ABDELHAKIM', '1981-11-14', '181119935032819', 'Hakim.hajjouti@groupe-cf.fr', 'CHEF DE SITE', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-08-30', '2024-09-02', '2025-08-31', '', '2024-09-02', '35', '00', '11', '2024-09-02', '2025-08-31', '100', 'SMIC', '', '', '', 'SMIC', '', '', '', 'SMIC', '1798.99', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/99TI JOSEPH.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/37670263166d069f45884a1.89488745SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/205114839466d0870d978469.85044499.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/147712568566d57a28bd0256.42190444SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/181753451866d0685e6050f7.57313320SignatureTamponBarbara.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/158305178466d57a32bc32d4.92853014SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-02', 18, 6, 38, NULL),
(100, 'Chinama', '', 'Owen', 'M', '1997-10-12', '972', 'Schœlcher', '1', '2', '11', '38', '1', '197109722983568', 'TFP APS', '38', 'non', 'non', 'non', '', 'Domaine des Vivrets', '', '60490', 'Marquéglise', '0696656009', 'owenchinama92@gmail.com', '', '', '', '', '', '', '', '', 'VATIN', 'SANDRINE', '1983-04-11', '283046015906020', 'Sandrine.vatin@groupe-cf.fr', 'Ajointe exploitation', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-08-30', '2024-09-02', '2025-08-31', '', '2024-09-02', '35', '00', '11', '2024-09-02', '2025-08-31', '100', 'SMIC', '', '', '', 'SMIC', '', '', '', 'SMIC', '1798.99', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/100Chinama.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/175438945966d06875761de3.77862293SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/201014185866d07975204dc0.50390912.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/169633707666d579309d8e41.00514712SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/214546460266d0683378b665.22406168.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/189953511566d5793e7cf3f0.65669160SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-02', 18, 6, 38, NULL),
(102, 'ROULIERE', '', 'CELYNE', 'F', '1979-05-19', '33', 'BORDEAUX', '1', '2', '11', '38', '1', '279053306341888', 'TFP APS', '38', 'non', 'oui', 'non', '', 'Domaine des Vivrets', '', '60490', 'Marquéglise', '0659974471', 'celyne.rouliere79@gmail.com', '', '', '', '', '', '', '', '', 'GILLET', '   REMI', '1999-10-24', '199109305105092', 'Barbara.VALERO@groupe-cf.fr', 'Responsable de site', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-09-17', '2024-09-18', '2025-08-31', '', '2024-09-18', '35', '00', '11', '2024-09-18', '2025-08-31', '100', 'SMIC', '', '', '', '', '', '', '', '', '1798.99', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/102ROULIERE.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/196955462566e8443054cb65.94036116SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/11392717666e88fe67376b6.07296388.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/201857900166e9277038f387.06423455SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/43611367166e8443e9a7942.46338949SignatureNicolasDufosse.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/35457212566e9277aded1f7.08848865SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-04', 18, 6, 38, NULL),
(103, 'ABOUDOU SILAHI', '', 'FAISE', 'M', '2002-04-10', '976', ' Kani-Kéli', '1', '2', '11', '38', '1', '102049850900240', 'TFP APS', '38', 'non', 'non', 'non', '', 'Domaine des Vivrets', '', '60490', 'Marquéglise', '0693469884', 'aboudou.faise@gmail.com', '', '', '', '', '', '', '', '', 'GILLET', '   REMI', '1999-10-24', '199109305105092', 'Barbara.VALERO@groupe-cf.fr', 'Responsable de site', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-09-17', '2024-09-18', '2025-08-31', '', '2024-09-18', '35', '00', '11', '2024-09-18', '2025-08-31', '100', 'SMIC', '', '', '', '', '', '', '', '', '1798.99', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/103ABOUDOU SILAHI.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/62212190166e843ee3dc213.84895634SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/186366609266e8464967bae5.226875751000005675.jpg', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/142496826966e8470e058a13.71274431SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/38578707266e84403b04ab8.07727890SignatureNicolasDufosse.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/48663559266e8471737cf80.83811970SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-04', 18, 6, 38, NULL),
(104, 'SAID ALI ', '', ' FAZIL', 'M', '2004-10-01', '976', 'Mamoudzou', '1', '2', '11', '38', '1', '104109851107106', 'TFP APS', '38', 'non', 'non', 'non', '', 'Domaine des Vivrets', '', '60490', 'Marquéglise', '0692107597', 'fazilsaidali@icloud.com', '', '', '', '', '', '', '', '', 'Lestoquoi', 'Cyril', '1970-04-10', '170046017503855', 'Barbara.VALERO@groupe-cf.fr', 'Encadrant', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-09-17', '2024-09-18', '2025-08-31', '', '2024-09-18', '35', '00', '11', '2024-09-18', '2025-08-31', '99', 'SMIC', '', '', '', '', '', '', '', '', '1798.99', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/104SAID ALI .pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/190390877366e84452709879.50340387SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/67054037266e8458a4da5b9.39333102IMG3417.jpeg', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/190832242766e845c3259850.09849330SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/140414108466e844649a5c90.32015895SignatureNicolasDufosse.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/62646311366e845d0e2e135.11266202SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-04', 18, 6, 38, NULL),
(105, 'BONJOUR', '', 'Cassandra', 'F', '2003-02-16', '40', '	 MONT DE MARSAN', '1', '2', '10', '42', '1', '203024019228532', 'BAC', '42', 'non', 'non', 'non', '24 ', 'rue Georges Bernard ', '', '27000', 'EVREUX', '0609870166', 'cassandra.40bjr@gmail.com', '', '', '', '', '', '', '', '', 'DECAMBRON ', 'Hélène', '1992-09-24', '292098025323207', 'helene.decambron@groupe-cf.fr', 'AS QUALIFIE', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-09-09', '2024-09-09', '2025-09-07', '', '2024-09-09', '35', '00', '11', '2024-09-09', '2025-09-07', '99', 'SMIC', '', '', '', '', '', '', '', '', '1852.95', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/105BONJOUR.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/124595929266ded6698cc3f6.45625195.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/209774952566db1fd92d1e97.29113134image.jpg', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/26660786666dff5f93919e5.22744800SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/186599599866ded6bb9b6dc1.37639646SignatureNicolasDufosse.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/99779977466dff60262d181.29886864SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-06', 18, 6, 38, NULL),
(106, ' KORKUT', '', '	 Dilhan ', 'M', '2005-11-05', '27', '	 L’AIGLE', '1', '2', '10', '42', '1', '105116121422825', 'BAC', '42', 'non', 'non', 'non', '	 520 ', 'Route de Grosbois ', '', '27130', 'PISEUX', '0767825124', 'dilhankrkt@gmail.com', '', '', '', '', '', '', '', '', '	 DAVEY', 'Philippe', '1961-01-12', '161017504401717', 'philippe.davey@groupe-cf.fr', '	 PDG Groupe CF', 'BAC', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-09-09', '2024-09-09', '2025-09-07', '', '2024-09-09', '35', '00', '11', '2024-09-09', '2025-09-07', '100', 'SMIC', '', '', '', '', '', '', '', '', '1852.95', '	 Ag2r la mondiale', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/106 KORKUT.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/138549722466ded6ed773be2.44093992SignatureNicolasDufosse.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/188486257366db207c5e7746.70153033.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/88692166666dff584577c32.07385327SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/193227583966ded7022a50c7.63332979SignatureNicolasDufosse.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/180401712366dff58fa07e27.43759819SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-06', 18, 6, 38, NULL),
(107, 'paul', NULL, 'machin', NULL, '2024-05-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Route du formateur de du ciel', NULL, '30000', 'kyoto', NULL, 'testvandame@etudiant.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-09', 23, 25, 2, NULL),
(108, 'Virginie', NULL, 'MENDODALLE', NULL, '1991-11-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TEST', NULL, '60400', 'TEST', NULL, 'webmaster@lgx-france.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-09', 23, 25, 2, NULL),
(109, 'LEONARD ', '', 'Sandy', 'M', '1982-04-13', '03', 'VICHY', '1', '2', '10', '58', '1', '182040331006830', 'SSIAP 3', '58', 'non', 'non', 'oui', '7', 'RUE RENE FALLET', '', '03110', 'VENDAT', '0615833953', 'sl03@gmx.fr', '', '', '', '', '', '', '', '', 'Lagneaux', 'Jérôme', '1977-06-24', '177060217304303', 'Jerome@lgx-france.fr', 'Dirigeant', 'Titre de Dirigeant option OFS', '6', '', '', '', '', '', '', '', '', 'non', '1', '12', '', '2024-09-16', '2024-09-17', '2025-09-30', '', '2024-09-17', '35', '00', '11', '2024-09-17', '2025-09-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1766.92', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', '2609378', '2409CA034767', '003202411025209', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/109LEONARD .pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/139860430766ed7bf3d34c73.75626544LEONARD _Pdf_cerfas2024-09-20_41_38_1726839698.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/130680756666e404ed8f2295.85150582.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/211856156566e1eb278cf9e8.98892209.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/156002778766e412fd23cbf8.84530668SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/81082223066e4049eeddc59.51924945.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/213952052666e413075d76b1.29333491SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-11', 21, 26, 38, NULL),
(111, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'developpement@lgx-france.fr', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'oui', NULL, NULL, NULL, '0', '', '', '', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '2024-09-20', 21, 34, 38, NULL),
(112, 'Vogler', '', 'Audrey', 'F', '2001-06-13', '74', 'Ambilly', '1', '2', '12', '58', '32', '201067400804589', 'Deug sociologie ', '58', 'non', 'non', 'non', '3', 'Rue du CEAT ', 'E14', '31500', 'Toulouse ', '0618443612', 'melvynevogler@gmail.com', '', '', '', '', '', '', '', '', 'SENOCQ', 'LUCILE', '1982-09-11', '282093155530460', 'Lsenocq@rps-groupe.com', 'Directrice RH et PAIE', '11', '5', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-09-27', '2024-10-01', '2025-09-30', '', '2024-10-01', '35', '00', '11', '2024-10-01', '2025-09-30', '53', 'SMIC', '', '', '', '', '', '', '', '', '936.47', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2626037', '2410CA002032', '031202411062146', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/5661334766fceb3fe46bd5.82002181convention Vogler Audrey.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/123157057066fcea4aac3b92.25934582Cerfa Vogler Audrey.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/204487432266f68b49902122.01520981.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/16134413566fa81cbf046b0.84874458SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', NULL, ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/4515000666fa81d4daae08.94205113SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-09-23', 24, 27, 38, NULL),
(117, 'Hu', '', 'Victor', 'M', '1993-07-02', '93', 'Montreuil', '1', '2', '4', '69', '1', '193079304821173', 'Concepteur Développeur d\'Applications', '69', 'non', 'non', 'non', '20', 'Route de Montluçon', '', '03390', 'Montmarault', '0611076121', 'victor@lgx-france.fr', '', '', '', '', '', '', '', '', 'LEVAL', 'Claudie', '1984-12-03', '284126061202131', 'Claudie@lgx-france.fr', 'Responsable pedagogique', 'Marketing digital', '5', '', '', '', '', '', '', '', '', 'non', '2', '12', '003202310093114', '2023-10-01', '2023-10-01', '2024-09-30', '', '2023-10-01', '35', '00', '21', '2023-10-08', '2024-09-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', 'CA-0247286-1', 'CA-0247286-1', 'Array', '3885674', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/117Hu.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/14541276046759a65e3ef424.51462923Hu_Pdf_cerfas2024-12-11_24_44_1733927084.pdf', ' https://lgx-solution.fr/cerfa/public/assets/factureOpco/117dossier_prise_en_charge.pdf', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1130398013671f662847aa53.33252713TamponCREATIONavecsignature.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/1543811889671f4420526da5.86227795.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureRepresentantApprenti/363033056674d99b663b894.43360603.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/921312467671f456c655d06.56223053SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/636689876671f667e936421.37233627TamponCREATIONavecsignature.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/568254922671f476de3a597.45512285SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-10-18', 35, 33, 38, NULL),
(119, 'FAVROT', 'LOUESDON FAVROT', 'Ludivine', 'F', '1999-01-22', '89', 'AUXERRE', '1', '2', '4', '49', '12', '2990189024356', 'Infographiste Metteur en Page', '58', 'non', 'non', 'non', '14', 'Grande Rue', 'Neuilly', '89113', 'VALRAVILLON', '0678488785', 'ludivine@lgx-france.fr', '', '', '', '', '', '', '', '', 'GOMEZ', 'ALEXANDRA', '1977-06-03', '277066015902293', 'Alexandra@lgx-france.fr', 'FORMATRICE ADULTES', 'DEES', '4', '', '', '', '', '', '', '', '', 'non', '2', '', '2312CA002610', '2024-11-25', '2024-12-01', '2025-11-30', '', '2024-12-01', '35', '00', '21', '2024-12-01', '2025-11-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1804.87', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', '2709405', '2411CA035871', NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/170462677567445c96ed2c48.18302905FAVROT_Pdf_conventions2024-11-25_15_07_1732533307.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/132517690367445c682d9254.23364509FAVROT_Pdf_cerfas2024-11-25_14_57_1732533297.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/77825119671bb33f56b977.95685836SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/1355633775671f40c63030f3.62300848.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/398774271671bb3166a9070.52142719SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/368707189671f7623802c28.00103749SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/1352998673671f7608ca2ec9.66803625SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-10-22', 21, 29, 38, NULL),
(120, 'Emeline', '', 'Carre', 'F', '2001-11-05', '59', 'Cambrai', '1', '2', '4', '69', '11', '2011159122258', 'Responsable petite ou moyenne structure', '55', 'non', 'non', 'non', '32', 'RUE DE L\'ÉCUREUIL', '', '60170', 'CAMBRONNE LES RIBECOURT', '0602002433', 'emeline@lgx-france.fr', '', '', '', '', '', '', '', '', 'MENDO', 'VIRGINIE', '1991-11-12', '291110216824476', 'Contact@lechosolutions.fr', 'CHEF DE PROJET', 'Concepteur développeur d\'application', '6', '', '', '', '', '', '', '', '', 'non', '2', '', '003202401047528', '2023-10-01', '2023-10-01', '2024-09-30', '', '2023-10-01', '35', '00', '21', '2023-10-01', '2024-09-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', '2364074', '243002512', '', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/120Emeline.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/428900966733273edc7028.48939806Vogler_Pdf_cerfas2024-11-12_50_33_1731405033.pdf', ' https://lgx-solution.fr/cerfa/public/assets/factureOpco/120dossier_prise-1-4_repaired.pdf', 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1181470198671fa003d587f9.04929897TamponCREATIONavecsignature.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/7895351406720add195d5f5.15971831.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/2022543348671f9f51cea356.07456566SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/791875998671fa013507328.84226467TamponCREATIONavecsignature.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/1759204499671f9f5a7f9051.55801649SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-10-22', 36, 34, 38, NULL),
(125, 'Ragou', 'Ragou', 'Boris', 'M', '1995-11-19', '974', 'Saint-Pierre', '1', '2', '4', '58', '12', '195119741693994', 'IMP Infographiste Metteur en Page', '58', 'non', 'non', 'oui', '19', 'Allée des canelles', 'PK17', '97430', 'Le Tampon', '0693452051', 'boris.ragou@hotmail.com', '', '', '', '', '', '', '', '', 'LAGNEAUX', 'Jérôme', '1977-06-24', '177060217304303', 'Jerome@lgx-france.fr', 'Dirigeant', 'Titre de Dirigeant option OFS', '6', '', '', '', '', '', '', '', '', 'non', '2', '', '003202312008414', '2024-11-25', '2024-12-01', '2025-11-30', '', '2024-12-01', '35', '00', '21', '2024-12-01', '2025-11-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', '2848599', '243237559', '003202412037009', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/1075663356745c5e1630084.53719428Ragou_Pdf_conventions2024-11-26_56_45_1732625805.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/2689094016745c5c2986c07.69339079Ragou_Pdf_cerfas2024-11-26_55_14_1732625714.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1482333927673b2b48693984.24892768TamponCREATIONavecsignature.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/97437092673b3b4aced854.20290369.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/210083659673b2a4e6f06d1.18643289SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/1472449328673b2b86b75bc9.85125295TamponCREATIONavecsignature.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/1975965439673b2b65cf0015.05076533SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-10-28', 22, 29, 38, NULL),
(126, 'LONCKE', '', 'FABIEN', 'M', '2000-10-21', '60', 'Compiégne', '1', '2', '4', '69', '1', '100106015935162', 'Concepteur Développeur D\'applications ', '58', 'non', 'non', 'non', '32', 'RUE', 'De l\'Écureuil', '60170', 'Cambronne Les Ribécourt', '0756826515', 'fabien@lgx-france.fr', '', '', '', '', '', '', '', '', 'LAGNEAUX', 'Jérôme', '1977-06-24', '177060217304303', 'Jerome@lgx-france.fr', 'Dirigeant', 'Titre de Dirigeant option OFS', '6', '', '', '', '', '', '', '', '', 'non', '2', '', '003202312008496', '2024-11-25', '2024-12-01', '2025-11-30', '', '2024-12-01', '35', '00', '21', '2024-12-01', '2025-11-30', '100', 'SMIC', '', '', '', '', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', '2848603', '243237563', '003202412027231', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/14923593736745c6644eb1a3.41069902LONCKE_Pdf_conventions2024-11-26_59_14_1732625954.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/11783370666745c641396008.44032242LONCKE_Pdf_cerfas2024-11-26_53_39_1732625619.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/19670884046720aa759f1968.56506184TamponCREATIONavecsignature.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/18647726316720b09d226704.10286969.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/8913525666720aa3edfc6f4.58050565SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/12718833756720aa864fb893.88625909TamponCREATIONavecsignature.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/17350745916720aa48b07e01.90166121SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-10-28', 22, 29, 38, NULL),
(127, 'HERDUIN', '', 'Simon', 'M', '1999-09-21', '80', 'AMIENS', '1', '2', '4', '58', '1', '1990902160001', 'Assistant Ressources Humaines', '58', 'non', 'non', 'non', '13', 'Rue Lucas', '', '03200', 'Vichy', '0770160010', 'simon@lgx-france.fr', '', '', '', '', '', '', '', '', 'DEGORRE', 'CHRISTOPHE', '1971-03-18', '171036211913869', 'christophe@lgx-france.fr', 'RESPONSABLE COMMERCIAL', 'MASTER HSE', '5', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-10-31', '2024-11-01', '2026-10-31', '', '2024-11-01', '35', '00', '22', '2024-11-01', '2025-10-31', '100', 'SMIC', '2025-11-01', '2026-10-31', '100', 'SMIC', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/127HERDUIN.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/8647707216728e1bc1a5b25.55736652TamponSOLUTIONavecsignature.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/2550400206729da074cf0f5.66885115.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/10757404286728e17e86b225.57318739SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/3405566536728e1cd539057.30428212TamponSOLUTIONavecsignature.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/13298163166728e18b63c622.45946371SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-04', 27, 30, 38, NULL),
(128, 'Da Veiga', 'Da Veiga', 'Quentin', 'M', '1993-09-11', '63', 'Clermont-Ferrand ', '1', '1', '4', '49', '1', '193096311317568', 'Infographiste Metteur en Page', '43', 'non', 'non', 'oui', '4', 'Rue de l\'abreuvoir ', 'Lieu-dit les minots', '63350', 'Luzillat ', '0659778854', 'daveigart63350@gmail.com', '', '', '', '', '', '', '', '', 'DEGORRE', 'Christophe', '1971-03-18', '171036211913861', 'Christophe@lgx-france.fr', 'Responsable commercial', 'MASTER HSE', '5', '', '', '', '', '', '', '', '', 'non', '1', '12', '003202312053722', '2024-10-31', '2024-11-01', '2026-10-31', '', '2024-11-01', '35', '00', '22', '2024-11-01', '2025-10-31', '100', 'SMIC', '2025-11-01', '2026-10-31', '100', 'SMIC', '', '', '', '', '1801.80', 'MALAKOFF HUMANIS', '', '', '', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/128Da Veiga.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/14082338046728aa248031c0.48224337TamponSOLUTIONavecsignature.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/10710420386728aa8530b427.30051392.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/13065178156728a9eb7ccc78.08409641SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/8728968486728ab0260cdb3.77758426TamponSOLUTIONavecsignature.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/975975916728aad63b0536.98185329SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-04', 27, 30, 38, NULL),
(134, 'CHIKHI', 'CHIKHI', 'Sofiane', 'M', '1995-04-21', '99', 'Alger', '2', '2', '10', '38', '1', '195049935295289', 'SSIAP 1', '79', 'non', 'non', 'non', '151', 'BOULEVARD DANIELLE CASANOVA', '', '13014', 'MARSEILLE', '0758789259', 'chikhisofiane1995@gmail.com', '', '', '', '', '', '', '', '', 'Miretto', 'Olivier', '1978-03-16', '178031305552849', 'Omiretto@rps-groupe.com', 'Chef de site', 'BAC économique et social', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-11-06', '2024-11-21', '2025-11-26', '', '2024-11-21', '35', '00', '11', '2024-11-21', '2025-11-26', '100', 'SMC', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2686514', '2411CA013405', NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/18173380826734bca2e04ea5.37122540CHIKHI_Pdf_conventions2024-11-13_45_17_1731498317.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/20117814046734bc6fbf90b4.68636077CHIKHI_Pdf_cerfas2024-11-13_52_24_1731498744.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/739959923672cfc61b98212.23571620.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/1833147277672b84112423e2.43981914.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/1921253501672b6930c6a139.50273272SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/842808587672cfc5599b677.98985564.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/211890549672b69393ddfd3.84816554SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-06', 29, 6, 38, NULL);
INSERT INTO `cerfa` (`id`, `nomA`, `nomuA`, `prenomA`, `sexeA`, `naissanceA`, `departementA`, `communeNA`, `nationaliteA`, `regimeA`, `situationA`, `titrePA`, `derniereCA`, `securiteA`, `intituleA`, `titreOA`, `declareSA`, `declareHA`, `declareRA`, `rueA`, `voieA`, `complementA`, `postalA`, `communeA`, `numeroA`, `emailA`, `nomR`, `prenomR`, `emailR`, `rueR`, `voieR`, `complementR`, `postalR`, `communeR`, `nomM`, `prenomM`, `naissanceM`, `securiteM`, `emailM`, `emploiM`, `diplomeM`, `niveauM`, `nomM1`, `prenomM1`, `naissanceM1`, `securiteM1`, `emailM1`, `emploiM1`, `diplomeM1`, `niveauM1`, `travailC`, `modeC`, `derogationC`, `numeroC`, `conclusionC`, `debutC`, `finC`, `avenantC`, `executionC`, `dureC`, `dureCM`, `typeC`, `rdC`, `raC`, `rpC`, `rsC`, `rdC1`, `raC1`, `rpC1`, `rsC1`, `rdC2`, `raC2`, `rpC2`, `rsC2`, `salaireC`, `caisseC`, `logementC`, `avantageC`, `autreC`, `lieuO`, `priveO`, `attesteO`, `numeroInterne`, `numeroExterne`, `numeroDeca`, `numeroInterneFacture2`, `numeroInterneFacture3`, `numeroInterneDocument2`, `numeroInterneDocument3`, `conventionOpco`, `cerfaOpco`, `factureOpco`, `signatureEmployeur`, `signatureApprenti`, `signatureRepresentantApprenti`, `signatureEcole`, `signatureConventionEmployeur`, `signatureConventionEcole`, `date_creation`, `idemployeur`, `idformation`, `id_centre`, `id_users`) VALUES
(135, 'DOUMBOUYA', 'DOUMBOUYA', 'Mohamed', 'M', '2001-02-18', '99', 'CONAKRY', '3', '2', '10', '49', '1', '101029933030157', 'Titre APS', '49', 'non', 'non', 'non', '3', 'RUE MARIE ROSE GINESTE', '', '31300', 'TOULOUSE', '0755681745', 'mohamedoumbouya76@gmail.com', '', '', '', '', '', '', '', '', 'Humbert', 'Sylvain', '1980-08-02', '180083155504563', 'Shumbert@rps-groupe.com', 'Chef de site', 'SSIAP 2', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-11-06', '2024-11-21', '2025-11-26', '', '2024-11-21', '35', '00', '11', '2024-11-21', '2025-11-26', '100', 'SMC', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2686544', '2411CA013435', NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/11069834766734bdf25efeb1.56806504DOUMBOUYA_Pdf_conventions2024-11-13_48_05_1731498485.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/2598236296734bdbb057737.37601539DOUMBOUYA_Pdf_cerfas2024-11-13_48_38_1731498518.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/577345921672cfc47a8db91.94351373.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/51360535367345ede39b651.15798868.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/1814735485672b6979ee1e23.70173148SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/833486427672cfc25255983.57176080.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/2031481190672b6982e630b3.27951727SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-06', 28, 6, 38, NULL),
(136, 'HOUPIN', 'HOUPIN', 'Nicolas', 'M', '2003-01-25', '973', 'CAYENNE', '1', '2', '10', '49', '1', '103019730298655', 'Titre APS', '49', 'non', 'non', 'non', '11', 'IMPASSE VITRY', 'APPT 35', '31200', 'TOULOUSE', '0615519978', 'nicolashoupin973@gmail.com', '', '', '', '', '', '', '', '', 'Humbert', 'Sylvain', '1980-08-02', '180083155504563', 'Shumbert@rps-groupe.com', 'Chef de site', 'SSIAP 2', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-11-06', '2024-11-21', '2025-11-26', '', '2024-11-21', '35', '00', '11', '2024-11-21', '2025-11-26', '100', 'SMC', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2686495', '2411CA013386', '031202412068887', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/12297916916734bc2d87ffa1.92348000HOUPIN_Pdf_conventions2024-11-13_44_04_1731498244.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/318782466734bbe99c47b5.28174389HOUPIN_Pdf_cerfas2024-11-13_51_31_1731498691.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1233911778672cfc7aad2211.79657323.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/1050108937673469737a9700.82912556.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/113463787672b68ea2137b3.75271991SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/885718069672cfc6d185f23.77592918.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/1940324400672b68f3718805.99699281SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-06', 28, 6, 38, NULL),
(137, 'KASMI', 'KASMI', 'Lina', 'F', '2003-09-26', '13', 'MARSEILLE', '1', '2', '10', '49', '1', '203091320515828', 'Titre APS', '49', 'non', 'non', 'non', '16', 'BOULEVARD DANIELLE CASANOVA', '', '13014', 'MARSEILLE', '0765205312', 'linalea.kasmi@gmail.com', '', '', '', '', '', '', '', '', 'Miretto', 'Olivier', '1978-03-13', '178031305552849', 'Omiretto@rps-groupe.com', 'Responsable exploitation', 'BAC économique et social', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-11-06', '2024-11-21', '2025-11-26', '', '2024-11-21', '35', '00', '11', '2024-11-21', '2025-11-26', '100', 'SMC', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2686552', '2411CA013443', '013202412068986', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/12488716226734be7e07a127.89101350KASMI_Pdf_conventions2024-11-13_43_33_1731509013.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/16192620636734be55012326.70293577KASMI_Pdf_cerfas2024-11-13_53_07_1731498787.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1953634603672cfca4674c96.07220143.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/126672766673474f9e803c2.03148290.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/428223671672b6832275395.01517947SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/11933157856734ba696d5b57.67559535Capturedcran20241113154023.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/667293136672b683aae8689.80288235SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-06', 29, 6, 38, NULL),
(138, ' TANDJAOUI', 'TANDJAOUI', ' Abdallah', 'M', '2000-06-21', '31', 'MURET', '1', '2', '10', '42', '1', '100063139526097', 'BAC', '42', 'non', 'non', 'non', '17', 'rue Pierre de Capelle', ' Appt 1194', '31600', 'MURET', '0766536512', 'Abdallah.tan@outlook.fr', '', '', '', '', '', '', '', '', 'Mamar', 'Djebbour', '1982-07-31', '182073155578186', 'Mamarmamar31@yahoo.fr', 'Agent de maîtrise', 'BAC économique et social', '2', '', '', '', '', '', '', '', '', 'non', '2', '', '', '2024-11-06', '2024-11-21', '2025-11-26', '', '2024-11-21', '35', '00', '11', '2024-11-21', '2025-11-26', '100', 'SMC', '', '', '', '', '', '', '', '', '1852.95', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2686525', '2411CA013416', '031202412069005', '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/16183543086734bd2480e8b4.70755797_TANDJAOUI_Pdf_conventions2024-11-13_46_13_1731498373.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/2229321186734bceebd2b81.80686353_TANDJAOUI_Pdf_cerfas2024-11-13_54_03_1731498843.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/1802657653672cfc9015cad6.70396659.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/104032480267347f7aab0041.30546754.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/1814337752672b688e3bf760.32140804SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/419273350672cfc86b4b423.87425994.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/1966954132672b6896a35116.56787265SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-06', 30, 6, 38, NULL),
(143, 'Savatier', 'Savatier ', 'Léa ', 'F', '2008-04-17', '60', 'Compiegne ', '1', '2', '1', '33', '40', '208046015933921', 'aucun diplôme ou titre préparé ', '13', 'non', 'non', 'non', '13 ', 'rue serpente ', '', '60150', 'thourotte ', '0663598375', 'leasavatier2008@gmail.com', 'Bricaud ', 'Aurore ', 'aurore84lea@gmail.com', '13 ', 'rue serpente ', '', '60150', 'thourotte ', 'VATIN', 'SANDRINE', '1983-04-11', '283046015906020', 'Sandrine.vatin@groupe-cf.fr', 'Agent d’exploitation', 'BAC', '4', '', '', '', '', '', '', '', '', 'non', '1', '', '', '2024-11-15', '2024-11-18', '2025-08-31', '', '2024-11-18', '35', '00', '11', '2024-11-18', '2025-08-31', '27', 'SMIC', '', '', '', '', '', '', '', '', '486.49', 'Ag2r lamondiale', '', '', 'non', 'VICHY', 'oui', 'oui', NULL, NULL, NULL, '0', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/143Savatier.pdf', NULL, NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/151922147367376c4b812e63.35726106SignatureBarbara.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/4524118567376cb003ea42.06675415.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/84920826867376bb54272c1.91657850SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/189776891367376d0d936a38.03980176SignatureBarbara.png', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/204675614867376d407eebb2.98259822SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-15', 18, 31, 38, NULL),
(144, 'SABOURIN', 'SABOURIN', 'LUCIE', 'F', '1995-08-05', '92', 'SEVRES', '1', '2', '4', '58', '1', '295089207222708', 'TP - CIP', '58', 'non', 'non', 'non', '8', 'RUE LASEGUE', '', '92320', 'CHATILLON', '0664646034', 'luciesabourin@outlook.com', '', '', '', '', '', '', '', '', 'BROCHE', 'JEAN-CLAUDE', '1961-09-20', '161097505122716', 'Jean-claude@orange.fr', 'Directeur', '99', '5', '', '', '', '', '', '', '', '', 'non', '1', '', 'xxxxxxxxxxxxxxx', '2024-11-27', '2024-12-01', '2025-12-31', '', '2024-12-01', '35', '00', '21', '2024-12-01', '2025-12-31', '100', 'SMC', '', '', '', '', '', '', '', '', '1801.80', 'AG2R', '', '', '', 'VICHY', 'oui', 'oui', '2714305', '2411CA040746', '094202501015134', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/assets/conventionOpco/6899219726749a66366a246.77067001SABOURIN_Pdf_conventions2024-11-29_28_40_1732872520.pdf', ' https://lgx-solution.fr/cerfa/public/assets/cerfaOpco/11059985416749a62845fc24.47360459SABOURIN_Pdf_cerfas2024-11-29_31_30_1732879890.pdf', NULL, 'https://lgx-solution.fr/cerfa/public/assets/signatureEmployeur/20788358266749812b0cb735.40748537SignatureMBrochepage00011.jpg', 'https://lgx-solution.fr/cerfa/public/assets/signatureApprenti/186812096749805620b5e8.70596781.png', '', ' https://lgx-solution.fr/cerfa/public/assets/signatureEcole/16863451426745e7734ed173.86705484SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', 'https://lgx-solution.fr/cerfa/public/assets/signatureConventionEmployeur/13394225306749818ecd9bb9.45999383SignatureMBrochepage00011.jpg', ' https://lgx-solution.fr/cerfa/public/assets/signatureConventionEcole/2851702296745e77f09c2a4.94988080SIGNATURECFALGXCAMPUSAVECSIGNATURE.png', '2024-11-26', 32, 29, 38, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `clients_cerfa`
--

CREATE TABLE `clients_cerfa` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `adressePostale` varchar(255) NOT NULL,
  `codePostal` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `idCreation` int(11) NOT NULL,
  `roleCreation` int(255) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients_cerfa`
--

INSERT INTO `clients_cerfa` (`id`, `firstname`, `lastname`, `adressePostale`, `codePostal`, `ville`, `telephone`, `idCreation`, `roleCreation`, `id_users`) VALUES
(3, 'Heriol', 'Fiemo', '15 rue des saint sauveurs', '92260', 'FONTENAY AUX ROSES', '0753868020', 1, 1, 139);

-- --------------------------------------------------------

--
-- Structure de la table `conseillers_financeurs`
--

CREATE TABLE `conseillers_financeurs` (
  `id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `type_financeur` varchar(50) NOT NULL,
  `id_entreprises` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `conseillers_financeurs`
--

INSERT INTO `conseillers_financeurs` (`id`, `lastname`, `firstname`, `type_financeur`, `id_entreprises`, `id_users`) VALUES
(16, 'Hu', 'Victor', 'type-1', 166, 247);

-- --------------------------------------------------------

--
-- Structure de la table `cours`
--

CREATE TABLE `cours` (
  `id` int(11) NOT NULL,
  `id_formateurs` int(11) DEFAULT NULL,
  `id_events` int(11) NOT NULL,
  `id_matieres` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cours`
--

INSERT INTO `cours` (`id`, `id_formateurs`, `id_events`, `id_matieres`) VALUES
(1387, 40, 2834, NULL),
(1388, NULL, 2835, NULL),
(1389, NULL, 2836, NULL),
(1390, NULL, 2837, NULL),
(1391, NULL, 2838, NULL),
(1392, 40, 2847, NULL),
(1393, 40, 2848, NULL),
(1394, 40, 2849, NULL),
(1395, 40, 2850, NULL),
(1396, 40, 2851, NULL),
(1397, 40, 2852, NULL),
(1398, 40, 2853, NULL),
(1399, 40, 2854, NULL),
(1400, 40, 2862, 27),
(1401, 40, 2864, 27);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id` int(11) NOT NULL,
  `nomE` varchar(255) DEFAULT NULL,
  `typeE` varchar(255) DEFAULT NULL,
  `specifiqueE` varchar(255) DEFAULT NULL,
  `totalE` int(11) DEFAULT NULL,
  `siretE` varchar(14) DEFAULT NULL,
  `codeaE` varchar(6) DEFAULT NULL,
  `codeiE` varchar(5) DEFAULT NULL,
  `rueE` varchar(255) DEFAULT NULL,
  `voieE` varchar(255) DEFAULT NULL,
  `complementE` varchar(255) DEFAULT NULL,
  `postalE` varchar(10) DEFAULT NULL,
  `communeE` varchar(255) DEFAULT NULL,
  `emailE` varchar(255) DEFAULT NULL,
  `numeroE` varchar(20) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL,
  `id_centre` int(255) DEFAULT NULL,
  `idopco` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id`, `nomE`, `typeE`, `specifiqueE`, `totalE`, `siretE`, `codeaE`, `codeiE`, `rueE`, `voieE`, `complementE`, `postalE`, `communeE`, `emailE`, `numeroE`, `id_users`, `id_centre`, `idopco`) VALUES
(7, 'RPS  SECURITE', '12', '0', 100, '44862172200058', '8010Z', '1351', '76', 'Rue du Courant', '', '33310', 'LORMONT', 'service-rh@rps-groupe.com', '0534635103', NULL, 38, 5),
(18, 'Capital Sécurité', '12', '0', 600, '40055588400032', '8010z	', '1351', '3', 'rue Notre Dame de Bon Secours', '', '60200', 'Compiegne', 'Barbara.valero@groupe-cf.fr', '0344364160', NULL, 38, 5),
(19, 'ALTAIR SECURITE', '12', '0', 380, '34329965700111', '8010Z', '1351', '212', 'bd Anatole France', '', '93200', 'ST DENIS', 'contact@ageconsulting.fr', '0149713270', NULL, 38, 5),
(20, 'BSL SECURITE LYON', '12', '0', 180, '83302268400017', '0810Z', '1351', '96', 'BD Marius Vivier Merle', '', '69003', 'Lyon', 'aldinamuratovic@groupebsl.com', '0140179732', NULL, 38, 5),
(21, 'LGX CAMPUS', '12', '0', 10, '97767542000029', '8559A', '1516', '17', 'boulevard carnot', '', '03200', 'VICHY', 'campus@lgx-france.fr', '0463884133', NULL, 38, 5),
(22, 'LGX CREATION ', '12', '0', 10, '97767521400026', '6201Z', '1486', '17', 'chemin Doyat', '', '03250', 'ARRONNES', 'campus@lgx-france.fr', '0463884134', NULL, 38, 26),
(23, 'test employeur virginie', '11', '1', 1, '12345678998765', '1', '1', '1', '1', '1', '11111', 't', 'contact@lechosolutions.fr', '0303030303', NULL, 2, 0),
(24, 'A3F EXPERTISES', '16', '0', 3, '53164642000038', '8559B', '1516', '3', 'RUE JEAN AMIEL ', 'BAT F ', '31700', 'BLAGNAC', 'contact@a3fexpertises.fr', '0534471396', NULL, 38, 5),
(25, 'LGX FRANCE', '12', '0', 1, '95246028500028', '70.10Z', '1486', '17', 'chemin Doyat', '', '03250', 'ARRONNES', 'jerome.france@lgx-france.fr', '0463884133', NULL, 38, 26),
(27, 'LGX SOLUTION', '12', '0', 5, '97776540300026', '78.10Z', '1486', '17', 'chemin Doyat', '', '03250', 'ARRONNES', 'campus@lgx-france.fr', '0463884133', NULL, 38, 5),
(28, 'RPS Sécurité Occitanie ', '12', '0', 250, '44862172200066', '8010Z', '1351', '23', 'rue Boudeville', '', '31100', 'TOULOUSE', 'service-rh@rps-groupe.com', '0534635103', NULL, 38, 5),
(29, 'RPS Sécurité Marseille ', '12', '0', 100, '44862172200074', '8010Z', '1351', '9', 'avenue Claude Monet', '', '13014', 'Marseille', 'service-rh@rps-groupe.com', '0534635103', NULL, 38, 5),
(30, 'RPS SECURITE PORTET 2', '12', '0', 60, '44862172200124', '8010Z', '1351', '3', 'Rue Jean Amiel', '', '31700', 'Blagnac', 'service-rh@rps-groupe.com', '0534635103', NULL, 38, 5),
(32, 'CFPS', '12', '0', 5, '33087353000050', '8559A', '1516', '12', 'RUE RAYMOND LEFEBVRE', '', '94250', 'GENTILLY', 'jean-claudebroche@orange.fr', '0141983819', NULL, 38, 5),
(33, 'CREDER', '12', '0', 31, '80471064800085', '7022Z	', '1486', '6', 'rue du Professeur Pierre Dangeard', 'Espace masterclub Entrée 3', '33300', 'Bordeaux', 'creder.rh@creder.com', '0556341659', NULL, 38, 26),
(35, 'LGX CREATION 2', '12', '0', 10, '33442627700110', '6201Z', '1486', '17 ', 'chemin Doyat', '', '03250', 'ARRONNES', 'zeufackheriol9@gmail.com', '0463884134', NULL, 38, 29),
(36, 'LGX CREATION 3', '12', '0', 10, '97767521400026', '6201Z', '1486', '17', 'chemin Doyat', '', '03250', 'ARRONNES', 'zeufackheriol9@gmail.com', '0463884134', NULL, 38, 28);

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `siret` varchar(50) NOT NULL,
  `nomEntreprise` varchar(50) NOT NULL,
  `nomDirecteur` varchar(50) NOT NULL,
  `adressePostale` varchar(250) NOT NULL,
  `codePostal` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `ape` varchar(50) NOT NULL,
  `intracommunautaire` varchar(50) NOT NULL,
  `isActif` tinyint(1) NOT NULL,
  `soumis_tva` tinyint(1) NOT NULL,
  `domaineActivite` varchar(50) NOT NULL,
  `formeJuridique` varchar(50) NOT NULL,
  `siteWeb` varchar(100) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `logo` varchar(250) DEFAULT NULL,
  `dateCreation` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `siret`, `nomEntreprise`, `nomDirecteur`, `adressePostale`, `codePostal`, `ville`, `telephone`, `ape`, `intracommunautaire`, `isActif`, `soumis_tva`, `domaineActivite`, `formeJuridique`, `siteWeb`, `fax`, `logo`, `dateCreation`, `email`) VALUES
(166, '97767521400018', 'LGX CREATION', 'Lagneaux', '32, quai d\'Allier', '03200', 'Vichy', '0632966351', '6201Z', 'xxxxxxx', 1, 0, 'Sécurité', 'SAS', '', '', NULL, '2024-08-23 13:51:08', 'jerome.creation@lgx-france.fr');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises_type`
--

CREATE TABLE `entreprises_type` (
  `id_entreprises` int(11) NOT NULL,
  `is_accueil` tinyint(4) DEFAULT NULL,
  `id_centres_de_formation` int(11) DEFAULT NULL,
  `is_financeur` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipements`
--

CREATE TABLE `equipements` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `quantite` int(11) NOT NULL,
  `id_salles` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `equipements`
--

INSERT INTO `equipements` (`id`, `nom`, `quantite`, `id_salles`) VALUES
(40, 'Chaise', 20, 73),
(41, 'Rétroprojecteur', 1, 73);

-- --------------------------------------------------------

--
-- Structure de la table `etat_ticket`
--

CREATE TABLE `etat_ticket` (
  `id` int(11) NOT NULL,
  `etat` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etat_ticket`
--

INSERT INTO `etat_ticket` (`id`, `etat`) VALUES
(1, 'Envoyé'),
(2, 'En cours de traitement'),
(3, 'Résolu'),
(4, 'Abandonné');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `adressePostale` varchar(50) NOT NULL,
  `codePostal` varchar(50) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `id_entreprises` int(11) DEFAULT NULL,
  `id_centres_de_formation` int(11) NOT NULL,
  `id_conseillers_financeurs` int(11) DEFAULT NULL,
  `id_session` int(11) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `lastname`, `firstname`, `adressePostale`, `codePostal`, `ville`, `date_naissance`, `id_entreprises`, `id_centres_de_formation`, `id_conseillers_financeurs`, `id_session`, `id_users`) VALUES
(57, 'Hu', 'Victor', '20, route de Montluçon', '03390', 'Montmarault', '1993-07-02', NULL, 38, NULL, NULL, 254),
(58, 'Modification', 'Victor', '30, rue exemple', '04040', 'Vichy', '2000-12-04', NULL, 38, NULL, 59, 256),
(59, 'Etudiante', 'Virginie', '12 rue d el\'école', '02400', 'bercy', '1991-11-12', NULL, 38, NULL, 60, 257),
(60, 'Testetudiant2', 'Virginie', 'rue', '02200', 'ville', '1991-11-12', NULL, 38, NULL, 60, 258);

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `id_salles` int(11) DEFAULT NULL,
  `id_users` int(11) NOT NULL,
  `id_modalites` int(11) NOT NULL,
  `id_centres_de_formation` int(11) NOT NULL,
  `id_types_event` int(11) NOT NULL,
  `id_recurrence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `events`
--

INSERT INTO `events` (`id`, `nom`, `debut`, `fin`, `url`, `description`, `id_salles`, `id_users`, `id_modalites`, `id_centres_de_formation`, `id_types_event`, `id_recurrence`) VALUES
(2802, 'Réunion d\'information ', '2024-09-30 11:30:00', '2024-09-30 12:30:00', 'http://www.', 'Réunion de rentrée pour accueillir les nouveaux arrivants.', NULL, 188, 1, 38, 1, NULL),
(2803, 'Réunion d\'information ', '2024-09-30 11:35:00', '2024-09-30 12:35:00', 'https://test.fr', 'réunion pour accueillir les nouveaux arrivants.', NULL, 188, 2, 38, 1, NULL),
(2824, 'Even test Virginie', '2024-10-03 10:08:00', '2024-10-03 11:08:00', 'https://test.fr', 'test', NULL, 188, 2, 38, 1, NULL),
(2825, 'f', '2024-10-08 10:16:00', '2024-10-08 11:16:00', NULL, NULL, NULL, 188, 2, 38, 1, 2825),
(2826, 'f', '2024-10-09 10:16:00', '2024-10-09 11:16:00', NULL, NULL, NULL, 188, 2, 38, 1, 2825),
(2827, 'Réunion d\'information ', '2024-10-29 11:00:00', '2024-10-29 12:00:00', 'www.google.fr', 'Bienvenue', NULL, 188, 2, 38, 1, NULL),
(2828, 'Ajout event simple', '2024-10-30 11:19:00', '2024-10-30 12:19:00', NULL, NULL, 71, 253, 1, 38, 1, NULL),
(2829, 'Ajout event simple notification', '2024-10-30 12:10:00', '2024-10-30 13:01:00', NULL, NULL, 71, 253, 1, 38, 1, NULL),
(2830, 'Ajout event recurrent', '2024-10-31 09:00:00', '2024-10-31 10:00:00', NULL, NULL, NULL, 253, 2, 38, 1, 2830),
(2831, 'Ajout event recurrent', '2024-11-01 09:00:00', '2024-11-01 10:00:00', NULL, NULL, NULL, 253, 2, 38, 1, 2830),
(2832, 'Ajout event recurrent', '2024-11-02 09:00:00', '2024-11-02 10:00:00', NULL, NULL, NULL, 253, 2, 38, 1, 2830),
(2833, 'Ajout event recurrent nb occurence', '2024-10-31 10:00:00', '2024-10-31 11:00:00', NULL, NULL, NULL, 253, 2, 38, 1, 2833),
(2834, 'Séance 1 : Découverte', '2024-10-30 11:47:00', '2024-10-30 12:47:00', NULL, '', 71, 253, 1, 38, 2, NULL),
(2835, 'Infographie', '2024-10-30 09:00:00', '2024-10-30 10:00:00', NULL, '', 71, 253, 1, 38, 2, 2835),
(2836, 'Infographie', '2024-10-31 09:00:00', '2024-10-31 10:00:00', NULL, '', 71, 253, 1, 38, 2, 2835),
(2837, 'PHP', '2024-10-30 15:00:00', '2024-10-30 16:00:00', NULL, '', 71, 253, 1, 38, 2, 2837),
(2838, 'PHP', '2024-10-31 15:00:00', '2024-10-31 16:00:00', NULL, '', 71, 253, 1, 38, 2, 2837),
(2841, 'Modif évènement récurrent date fin', '2024-10-31 09:00:00', '2024-10-31 10:00:00', 'http://test.fr', NULL, 71, 248, 1, 38, 1, NULL),
(2846, 'Ajout simple', '2024-10-30 14:00:00', '2024-10-30 15:00:00', NULL, NULL, NULL, 248, 2, 38, 1, NULL),
(2847, 'Test cours', '2024-10-30 17:00:00', '2024-10-30 18:00:00', NULL, '', 71, 248, 1, 38, 2, NULL),
(2848, 'Test chevauchement', '2024-10-30 07:00:00', '2024-10-30 08:00:00', NULL, '', 71, 248, 1, 38, 2, 2848),
(2849, 'Test chevauchement', '2024-11-04 07:00:00', '2024-11-04 08:00:00', NULL, '', 71, 248, 1, 38, 2, 2848),
(2850, 'Test chevauchement', '2024-11-05 07:00:00', '2024-11-05 08:00:00', NULL, '', 71, 248, 1, 38, 2, 2848),
(2851, 'Test chevauchement', '2024-11-06 07:00:00', '2024-11-06 08:00:00', NULL, '', 71, 248, 1, 38, 2, 2848),
(2852, 'Test cours récurrent - Nb occurence', '2024-10-30 08:00:00', '2024-10-30 09:00:00', NULL, '', 71, 248, 1, 38, 2, 2852),
(2853, 'Test cours récurrent - Nb occurence', '2024-11-04 08:00:00', '2024-11-04 09:00:00', NULL, '', 71, 248, 1, 38, 2, 2852),
(2854, 'Test cours récurrent - Nb occurence', '2024-11-05 08:00:00', '2024-11-05 09:00:00', NULL, '', 71, 248, 1, 38, 2, 2852),
(2858, 'Test event recurrent', '2024-10-31 09:00:00', '2024-10-31 10:00:00', NULL, NULL, 71, 253, 1, 38, 1, 2855),
(2862, 'Test cours notif', '2024-10-31 13:43:00', '2024-10-31 20:43:00', 'http://test.fr', 'test', NULL, 188, 2, 38, 2, NULL),
(2863, 'Even test Virginie', '2024-10-31 14:05:00', '2024-10-31 15:05:00', 'https://test.fr', NULL, NULL, 188, 2, 38, 3, NULL),
(2864, 'Séance 1 : Découverte', '2024-10-31 10:00:00', '2024-10-31 11:00:00', NULL, '', 71, 253, 1, 38, 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `event_sessions`
--

CREATE TABLE `event_sessions` (
  `id` int(11) NOT NULL,
  `id_events` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_sessions`
--

INSERT INTO `event_sessions` (`id`, `id_events`) VALUES
(59, 2824),
(59, 2825),
(59, 2826),
(60, 2827),
(60, 2858),
(60, 2862),
(60, 2864);

-- --------------------------------------------------------

--
-- Structure de la table `event_users`
--

CREATE TABLE `event_users` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `event_users`
--

INSERT INTO `event_users` (`id`, `id_users`) VALUES
(2863, 257);

-- --------------------------------------------------------

--
-- Structure de la table `facture_cerfa`
--

CREATE TABLE `facture_cerfa` (
  `id` int(11) NOT NULL,
  `numeroOF` varchar(50) DEFAULT NULL,
  `lieuF` varchar(255) DEFAULT NULL,
  `ibanF` varchar(34) DEFAULT NULL,
  `repreF` varchar(255) DEFAULT NULL,
  `emploiRF` varchar(255) DEFAULT NULL,
  `motif` varchar(255) DEFAULT NULL,
  `motif1` varchar(255) DEFAULT NULL,
  `motif2` varchar(255) DEFAULT NULL,
  `motif3` varchar(255) DEFAULT NULL,
  `motif4` varchar(255) DEFAULT NULL,
  `motif5` varchar(255) DEFAULT NULL,
  `montant` float DEFAULT NULL,
  `montant1` float DEFAULT NULL,
  `montant2` float DEFAULT NULL,
  `montant3` float DEFAULT NULL,
  `montant4` float DEFAULT NULL,
  `montant5` float DEFAULT NULL,
  `echeance1` varchar(255) DEFAULT NULL,
  `echeance2` varchar(255) DEFAULT NULL,
  `echeance3` varchar(255) DEFAULT NULL,
  `echeance4` varchar(255) DEFAULT NULL,
  `date1` date DEFAULT NULL,
  `date2` date DEFAULT NULL,
  `date3` date DEFAULT NULL,
  `date4` date DEFAULT NULL,
  `ht1` decimal(10,2) DEFAULT NULL,
  `ht2` decimal(10,2) DEFAULT NULL,
  `ht3` decimal(10,2) DEFAULT NULL,
  `ht4` decimal(10,2) DEFAULT NULL,
  `idcerfa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `facture_cerfa`
--

INSERT INTO `facture_cerfa` (`id`, `numeroOF`, `lieuF`, `ibanF`, `repreF`, `emploiRF`, `motif`, `motif1`, `motif2`, `motif3`, `motif4`, `motif5`, `montant`, `montant1`, `montant2`, `montant3`, `montant4`, `montant5`, `echeance1`, `echeance2`, `echeance3`, `echeance4`, `date1`, `date2`, `date3`, `date4`, `ht1`, `ht2`, `ht3`, `ht4`, `idcerfa`) VALUES
(27, '127639.001', 'Douala', '77777777777777777', 'Jerome', 'Dirigeant', 'PEDAGOGIE', 'PREMIEREQUIPEMENT', '', '', '', '', 12, 1000, 0, 0, 0, 0, '1', '2', '3', '4', '2024-12-09', '2024-12-17', '2024-12-18', '2024-12-10', 1.00, 2.00, 3.00, 4.00, 70),
(28, '127639.001', 'vicy', '7618715002000800419719307', 'Jerome', 'Dirigeant', 'PEDAGOGIE', 'PREMIEREQUIPEMENT', '', '', '', '', 6930, 500, 0, 0, 0, 0, '1', '2', '3', '', '2024-11-01', '2025-05-01', '2025-08-01', '0000-00-00', 2678.40, 2008.80, 2008.80, 0.00, 120),
(29, '127639.001', 'vichy', '7610278060670003080234949', 'Jerome', 'Dirigeant', 'PEDAGOGIE', 'PREMIEREQUIPEMENT', '', '', '', '', 6825, 500, 0, 0, 0, 0, '1', '2', '3', '', '2023-10-01', '2024-04-01', '2024-07-01', '0000-00-00', 2730.00, 2047.50, 2047.50, 0.00, 117);

-- --------------------------------------------------------

--
-- Structure de la table `fiches_suivi`
--

CREATE TABLE `fiches_suivi` (
  `id` int(11) NOT NULL,
  `dateCreation` timestamp NOT NULL,
  `contenu` varchar(50) NOT NULL,
  `id_formateurs` int(11) DEFAULT NULL,
  `id_etudiants` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `formateurs`
--

CREATE TABLE `formateurs` (
  `id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `adressePostale` varchar(50) DEFAULT NULL,
  `codePostal` varchar(50) DEFAULT NULL,
  `ville` varchar(50) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `siret` varchar(100) DEFAULT NULL,
  `id_centres_de_formation` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formateurs`
--

INSERT INTO `formateurs` (`id`, `lastname`, `firstname`, `adressePostale`, `codePostal`, `ville`, `telephone`, `siret`, `id_centres_de_formation`, `id_users`) VALUES
(31, 'LAVAL', 'Claudie', NULL, NULL, NULL, NULL, NULL, 38, 195),
(40, 'Hu', 'Victor', '10, rue exemple', NULL, 'Exemple', '06060606', NULL, 38, 248);

-- --------------------------------------------------------

--
-- Structure de la table `formateurs_participant_session`
--

CREATE TABLE `formateurs_participant_session` (
  `id` int(11) NOT NULL,
  `id_formateurs` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formateurs_participant_session`
--

INSERT INTO `formateurs_participant_session` (`id`, `id_formateurs`) VALUES
(60, 40);

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

CREATE TABLE `formation` (
  `id` int(11) NOT NULL,
  `nomF` varchar(255) DEFAULT NULL,
  `diplomeF` varchar(255) DEFAULT NULL,
  `intituleF` varchar(255) DEFAULT NULL,
  `numeroF` varchar(20) DEFAULT NULL,
  `siretF` varchar(14) DEFAULT NULL,
  `codeF` varchar(255) DEFAULT NULL,
  `rnF` varchar(255) DEFAULT NULL,
  `entrepriseF` varchar(255) DEFAULT NULL,
  `responsableF` varchar(255) DEFAULT NULL,
  `prix` int(11) DEFAULT NULL,
  `rueF` varchar(255) DEFAULT NULL,
  `voieF` varchar(255) DEFAULT NULL,
  `complementF` varchar(255) DEFAULT NULL,
  `postalF` varchar(10) DEFAULT NULL,
  `communeF` varchar(255) DEFAULT NULL,
  `emailF` varchar(255) DEFAULT NULL,
  `debutO` date DEFAULT NULL,
  `prevuO` date DEFAULT NULL,
  `dureO` int(11) DEFAULT NULL,
  `nomO` varchar(255) DEFAULT NULL,
  `numeroO` varchar(20) DEFAULT NULL,
  `siretO` varchar(14) DEFAULT NULL,
  `rueO` varchar(255) DEFAULT NULL,
  `voieO` varchar(255) DEFAULT NULL,
  `complementO` varchar(255) DEFAULT NULL,
  `postalO` varchar(10) DEFAULT NULL,
  `communeO` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `id_centre` int(255) DEFAULT NULL,
  `id_users` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`id`, `nomF`, `diplomeF`, `intituleF`, `numeroF`, `siretF`, `codeF`, `rnF`, `entrepriseF`, `responsableF`, `prix`, `rueF`, `voieF`, `complementF`, `postalF`, `communeF`, `emailF`, `debutO`, `prevuO`, `dureO`, `nomO`, `numeroO`, `siretO`, `rueO`, `voieO`, `complementO`, `postalO`, `communeO`, `logo`, `id_centre`, `id_users`) VALUES
(6, 'LGX Campus', '49', 'TP - Agent de Sûreté et de Sécurité Privée', '0031144C', '97767542000029', '46T34401', '34507', 'non', 'oui', 7250, '17', 'BOULEVARD CARNOT', '', '03200', 'VICHY', 'jerome@lgx-france.fr', '2024-11-21', '2025-11-26', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/7f3f27266dd90eac6322401270f034f0.png', 38, NULL),
(20, 'Groupe MCFG', '49', 'TP agent de sûreté et de sécurité privée', '0171668W', '85177673200014', '46T34', '34507', 'non', 'non', 7250, '1', 'Le Bois des Perrières', '', '17620', 'Echilais', 'email@email.com', '2024-02-01', '2025-02-28', 404, '', '', '', '', '', '', '', '', '', 38, NULL),
(21, 'LGX Campus ', '58', 'TP - Formateur professionnel d\'adultes', '0031144C', '97767542000011', '36T33', '37275', 'non', 'non', 7250, '32', 'QUAI D\'ALLIER', '', '03200', 'Vichy', 'jerome.creation@lgx-france.fr', '2024-06-01', '2025-07-31', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/17eb0fef69666b040279a9dcff14227a.png', 38, NULL),
(22, 'LGX Campus ', '58', 'Responsable Petite et Moyenne Structure', '0031144C', '97767542000011', '36T31', '38575', 'oui', 'oui', 6930, '32', 'QUAI D\'ALLIER', '', '03200', 'Vichy', 'jerome@lgx-france.fr', '2024-09-01', '2025-08-31', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/c1b6867aa695901ca3adb4ca09441298.png', 38, NULL),
(23, 'Groupe MCFG', '49', 'TP- Opérateur  en vidéoprotection et en télésurveillance', '0171668W', '85177673200014', '46T34', '34507', 'non', 'non', 7250, '1', 'Le Bois des Perrières', '', '17618', 'Echilais', 'email@email.com', '2024-06-10', '2024-04-14', 401, '', '', '', '', '', '', '', '', '', 38, NULL),
(25, 'CFA test', '80', 'Test', '12345678', '12345678898765', '46T34', '34507', 'oui', 'oui', 1, '1', '1', '', '12122', '1', 'virginiedalle60@gmail.com', '2024-09-09', '2024-09-14', 32, '', '', '', '', '', '', '10012', '', '', 2, NULL),
(26, 'LGX Campus', '58', 'TP Formateur Professionnel Adultes', '0031144C', '97767542000011', '36T33', '37275', 'oui', 'oui', 7250, '32', 'QUAI D\'ALLIER', '', '03200', 'Vichy', 'jerome@lgx-france.fr', '2024-09-17', '2025-09-30', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/0edf125f2eeb856017c57640ad5e5341.png', 38, NULL),
(27, 'LGX Campus', '58', 'TP Responsable petite et moyenne structure', '0031144C', '97767542000011', '36T31', '38575', 'non', 'oui', 6930, '32', 'QUAI D ALLIER', '', '03200', 'VICHY', 'jerome@lgx-france.fr', '2024-10-01', '2025-09-30', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/aeabcbf33095343d25f28944df7a5a18.png', 38, NULL),
(28, 'LGX CAMPUS', '58', 'TP Responsable Petite et Moyenne structure', '0031144C', '97767542000029', '36T31001', '38575', 'non', 'oui', 6930, '17', 'boulevard carnot', '', '03200', 'VICHY', 'jerome@lgx-france.fr', '2024-11-01', '2025-08-31', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/b2ed2128ac97a1d6b45e2a82722aac2b.png', 38, NULL),
(29, 'LGX Campus', '58', 'TP Responsable Petite Moyenne Stucture', '0031144C', '97767542000029', '36T31001', '38575', 'non', 'oui', 6930, '17', 'boulevard carnot', '', '03200', 'VICHY', 'jerome@lgx-france.fr', '2024-12-01', '2025-08-31', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/d1ffedf2261e7e5f41a6fabaf6824d38.png', 38, NULL),
(30, 'LGX Campus', '58', 'Responsable Petite et Moyenne Structure', '0031144C', '97767542000029', '36T31', '38575', 'non', 'oui', 6930, '17', 'boulevard carnot', '', '03200', 'VICHY', 'jerome.solution@lgx-france.fr', '2024-11-01', '2026-10-31', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/f16b2040c288fd2ae8b08cf7bf3637a6.png', 38, NULL),
(31, 'LGX CAMPUS', '49', 'TP - Agent de Sûreté et de Sécurité Privée', '0031144C', '97767542000029', '46T34401', '34507', 'non', 'oui', 7250, '17', 'BOULEVARD CARNOT', '', '03200', 'VICHY', 'jerome@lgx-france.fr', '2024-09-01', '2025-06-30', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/396b7e776e50cda8be13ac2ce3dbc683.png', 38, NULL),
(32, 'Institut National de Sûreté et de Sécurité Privée', '58', 'TP - Formateur professionnel d\'adultes', '0942520D', '81508143500035', '36T33', '37275', 'non', 'oui', 8971, '9', 'RUE PIERRE ET MARIE CURIE', '', '94200', 'IVRY-SUR-SEINE', 'contact@inssiformation.fr', '2024-12-01', '2025-09-20', 405, '', '', '', '', '', '', '', '', '', 38, NULL),
(33, '1901 FORMATION', '49', 'TP agent de sûreté et de sécurité privée', '0031144C', '40412904100020', '46T34401', '34507', 'non', 'non', 7250, '32', 'QUAI D\'ALLIER', '', '03200', 'Vichy', 'zeufackheriol9@gmail.com', '2023-10-01', '2024-09-30', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/36a209c6d0debdf7398334a2995f8741.png', 38, NULL),
(34, 'LGX CAMPUS 2', '49', 'TP agent de sûreté et de sécurité privée', '0031144C', '97767542000029', '46T34401', '34507', 'non', 'non', 7250, '32', 'QUAI D\'ALLIER', '', '03200', 'Vichy', 'zeufackheriol9@gmail.com', '2023-10-01', '2024-09-30', 405, '', '', '', '', '', '', '', '', ' https://lgx-solution.fr/cerfa/public/logo/08e72d5b429d81f424471d754ec6ebae.png', 38, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

CREATE TABLE `formations` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prix` varchar(50) NOT NULL,
  `lienFranceCompetence` varchar(50) NOT NULL,
  `id_centres_de_formation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `formations`
--

INSERT INTO `formations` (`id`, `nom`, `prix`, `lienFranceCompetence`, `id_centres_de_formation`) VALUES
(28, 'Formation 1', '20', 'test.fr', 38),
(29, 'Concepteur développeur d\'applications', '1000', 'https://www.francecompetences.fr/recherche/rncp/37', 38);

-- --------------------------------------------------------

--
-- Structure de la table `gender`
--

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `gender_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gender`
--

INSERT INTO `gender` (`id`, `gender_name`) VALUES
(1, 'Féminin'),
(2, 'Masculin'),
(3, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaires_centre`
--

CREATE TABLE `gestionnaires_centre` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `id_centres_de_formation` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gestionnaires_centre`
--

INSERT INTO `gestionnaires_centre` (`id`, `firstname`, `lastname`, `telephone`, `id_centres_de_formation`, `id_users`) VALUES
(41, 'Heriol', 'LGX', NULL, 38, 174),
(45, 'Stéphanie', 'LGX', NULL, 38, 178),
(46, 'Jessica', 'LGX', NULL, 38, 179),
(52, 'Virginie', 'LGX', NULL, 38, 188),
(62, 'Jérôme', 'Lagneaux', '0632966351', 38, 238),
(63, 'Simon', 'Herduin', NULL, 38, 240),
(65, 'Victor', 'Hu', '0606060606', 38, 253),
(66, 'Victor', 'Test', NULL, 38, 265);

-- --------------------------------------------------------

--
-- Structure de la table `gestionnaires_entreprise`
--

CREATE TABLE `gestionnaires_entreprise` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `lieu_travail` varchar(50) DEFAULT NULL,
  `id_entreprises` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `gestionnaires_entreprise`
--

INSERT INTO `gestionnaires_entreprise` (`id`, `firstname`, `lastname`, `telephone`, `lieu_travail`, `id_entreprises`, `id_users`) VALUES
(23, 'Victor', 'Hu', '', '', 166, 249);

-- --------------------------------------------------------

--
-- Structure de la table `infos_complementaires`
--

CREATE TABLE `infos_complementaires` (
  `id` int(11) NOT NULL,
  `dateCreation` datetime NOT NULL,
  `objet` varchar(100) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_etudiants` int(11) DEFAULT NULL,
  `id_types_infos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `matieres`
--

CREATE TABLE `matieres` (
  `id` int(11) NOT NULL,
  `matiere_nom` varchar(100) NOT NULL,
  `id_formations` int(11) DEFAULT NULL,
  `id_sessions` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `matieres`
--

INSERT INTO `matieres` (`id`, `matiere_nom`, `id_formations`, `id_sessions`) VALUES
(25, 'Test', 29, 59),
(26, 'Cybersécurité', 29, 60),
(27, 'Analyse des besoins', 29, 60),
(28, 'Module 1', 29, 59);

-- --------------------------------------------------------

--
-- Structure de la table `modalites`
--

CREATE TABLE `modalites` (
  `id` int(11) NOT NULL,
  `modalites_nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `modalites`
--

INSERT INTO `modalites` (`id`, `modalites_nom`) VALUES
(1, 'Présentiel'),
(2, 'Distanciel'),
(3, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `opco`
--

CREATE TABLE `opco` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `cle` varchar(1600) NOT NULL,
  `lienE` varchar(255) NOT NULL,
  `lienCe` varchar(255) NOT NULL,
  `lienCo` varchar(255) NOT NULL,
  `lienF` varchar(255) NOT NULL,
  `lienT` varchar(255) NOT NULL,
  `clid` varchar(255) NOT NULL,
  `clse` varchar(255) NOT NULL,
  `id_users` int(11) DEFAULT NULL,
  `id_centre` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `opco`
--

INSERT INTO `opco` (`id`, `nom`, `cle`, `lienE`, `lienCe`, `lienCo`, `lienF`, `lienT`, `clid`, `clse`, `id_users`, `id_centre`) VALUES
(5, 'Akto', 'kEsTBykl+0gXwDf+XlnXTIlWVKJYtuYI', 'https://cfa-ws.akto.fr/SorApiEchangeCFA/v1/dossiers/etats', 'https://cfa-ws.akto.fr/SorApiEchangeCFA/v1/dossiers', 'https://cfa-ws.akto.fr/SorApiEchangeCFA/v1/conventions', 'https://cfa-ws.akto.fr/SorApiEchangeCFA/v1/Factures', 'https://cfa-ws.akto.fr/SorApiIdentityServer/connect/token', 'OPCO_AKTO_LGX_CAMPUS', '48CM3gEvr9j5Nu', NULL, 38),
(26, 'Atlas', 'npqtG8Ca3UhYakWESS7sRJFpkv4i7La5', 'https://cfa-ws.opco-atlas.org/SorApiEchangeCFA/v1/dossiers/etats', 'https://cfa-ws.opco-atlas.org/SorApiEchangeCFA/v1/dossiers', 'https://cfa-ws.opco-atlas.org/SorApiEchangeCFA/v1/conventions', 'https://cfa-ws.opco-atlas.org/SorApiEchangeCFA/v1/Factures', 'https://cfa-ws.opco-atlas.org/SorApiIdentityServer/connect/token', 'ATLAS_CFA0033_3T8Dz', '&e4F3c)Zq', NULL, 38),
(27, 'AFDAS', 'yLpmiqlq54e6WhdAe1q2WJScXeoYVRgfIBiEAu8zGyoVcj0hrTZU8VWtG/Gl6AEF', 'https://api-cfa.afdas.com/v1/dossiers/etats', 'https://api-cfa.afdas.com/v1/dossiers', 'https://api-cfa.afdas.com/v1/conventions', 'https://api-cfa.afdas.com/v1/factures', 'https://afdas.okta.com/oauth2/aus3fsuhhkagKx83W417/v1/token', '0oajly8fv5rz9h4O5417', 'vLL_rdL3Z1VoXiQxg-7jrGEobiZ7U-2Ni7AdpXNNMIx0xG3e5FplX8FcdoTAjn7S', NULL, 38),
(28, 'EP', 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJnWm1VSy1KMUdoZlRFZE83ZzBScFlBQWNMSkVDdFpvRDBEOWxMV1JUNGNBIn0.eyJleHAiOjE3NjU1NDc0NDcsImlhdCI6MTczNDAxMTQ0NywianRpIjoiN2NhOTQwYzEtNTQ4OS00ZGQ4LWE2ZTItNDMxMTAzZmY4OWE3IiwiaXNzIjoiaHR0cHM6Ly9rZXljbG9hay1jb25zb2xlLW1hc3Rlci5sYWIub3Bjb2VwLmZyL2F1dGgvcmVhbG1zL2ludGVyb3Bjby1jZmEiLCJhdWQiOiJhY2NvdW50Iiwic3ViIjoiMTIyYjA2YzYtMDljZC00Nzk0LWJhNmMtYjZjOThiYzBmYWQ3IiwidHlwIjoiQmVhcmVyIiwiYXpwIjoicG9ydGFpbGFhIiwic2Vzc2lvbl9zdGF0ZSI6IjkwNTllMTk2LWE2MTgtNDQ4OC04YTM3LWY1ZjY2YzUwZDU0YSIsInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJkZWZhdWx0LXJvbGVzLWludGVyb3Bjby1jZmEiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSBvZmZsaW5lX2FjY2VzcyIsInNpZCI6IjkwNTllMTk2LWE2MTgtNDQ4OC04YTM3LWY1ZjY2YzUwZDU0YSIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJuYW1lIjoiTEdYIENhbXB1cyBFZGl0ZXVyIExHWCBDcsOpYXRpb24iLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiI5Nzc2NzU0MjAwMDAyOSIsImdpdmVuX25hbWUiOiJMR1ggQ2FtcHVzIiwiZmFtaWx5X25hbWUiOiJFZGl0ZXVyIExHWCBDcsOpYXRpb24iLCJlbWFpbCI6ImRlbW8xMDRAeW9wbWFpbC5jb20ifQ.MToEOYNR_m-sPegpJ68FlP28JOGtdlykbIDqXxo7ufHkbgEjmfz92jw92IpOAo6YFTLhaiR0Qn6UHBIXicD7ZGoiaUHOVIBnDhJPAGkAOWaJdAZX6nkpJoK9PYtMFJAN6lenLDUVSoKXr_7fVWuoYKjokMcvdKS1ImtvENzw7bVOmOcSvuAWwkvyrogf8nNzuEH1EPQtd_wuhZoSu3szY-kl99fJO5kp-bty_UCGuPm9uGuGjD-PM95YHdbFVdONChKXFK57gHrD1dcZXLU3cqupTmt5KIkPpkljJhg9NCZJ4F3beYkfIx6XMFc7aYb9V9b6ElYWJvcH_F3uW5iV6g', 'https://kong-master.lab.opcoep.fr/v1/dossiers/etats', 'https://kong-master.lab.opcoep.fr/v1/dossiers', 'https://kong-master.lab.opcoep.fr/v1/', 'https://kong-master.lab.opcoep.fr/v1/factures', 'https://keycloak-master.lab.opcoep.fr/auth/realms/interopco-partenaires/protocol/openid-connect/token', 'lgx-creation', 'Obpp95sbf7ILNRoLns0lIJdCUsJojEeZ', NULL, 38),
(29, 'MOBILITES', 'API', 'https://moov-test-echangecfa-api.opcomobilites.fr/SorApiEchangeCFA/v1/dossiers/etats', 'https://moov-test-echangecfa-api.opcomobilites.fr/SorApiEchangeCFA/v1/dossiers', 'https://moov-test-echangecfa-api.opcomobilites.fr/SorApiEchangeCFA/v1/conventions', 'https://moov-test-echangecfa-api.opcomobilites.fr/SorApiEchangeCFA/v1/Factures', 'https://moov-test-echangecfa-api.opcomobilites.fr/SorApiIdentityServer/connect/token', 'OPCO_MOBILITES_CFA_LGX_Campus', '7v5E54pXcrAeE4', NULL, 38);

-- --------------------------------------------------------

--
-- Structure de la table `pointages`
--

CREATE TABLE `pointages` (
  `id` int(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `entree_sortie` tinyint(11) NOT NULL,
  `is_pointed` tinyint(11) NOT NULL,
  `id_etudiants` int(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pointages`
--

INSERT INTO `pointages` (`id`, `date`, `entree_sortie`, `is_pointed`, `id_etudiants`) VALUES
(153, '2024-10-03 13:38:41', 1, 0, NULL),
(154, '2024-10-03 13:39:17', 0, 0, NULL),
(155, '2024-10-04 09:07:07', 1, 0, NULL),
(156, '2024-10-04 09:14:29', 0, 0, NULL),
(157, '2024-10-10 08:49:23', 1, 0, NULL),
(158, '2024-10-10 08:49:38', 0, 0, NULL),
(159, '2024-10-10 12:51:44', 1, 0, NULL),
(160, '2024-10-10 12:51:49', 0, 0, NULL),
(161, '2024-10-11 08:15:17', 1, 0, NULL),
(162, '2024-10-11 08:15:57', 0, 0, NULL),
(163, '2024-10-14 07:26:44', 1, 0, NULL),
(164, '2024-10-14 07:27:19', 0, 0, NULL),
(165, '2024-10-14 12:03:54', 1, 0, NULL),
(166, '2024-10-14 12:04:58', 0, 0, NULL),
(167, '2024-10-16 07:13:01', 1, 1, NULL),
(168, '2024-10-30 10:54:19', 1, 0, 57),
(169, '2024-10-30 10:57:44', 0, 0, 57);

-- --------------------------------------------------------

--
-- Structure de la table `pointages_infos`
--

CREATE TABLE `pointages_infos` (
  `id` int(11) NOT NULL,
  `etudiant_email` varchar(50) NOT NULL,
  `etudiant_nom` varchar(100) NOT NULL,
  `etudiant_prenom` varchar(100) NOT NULL,
  `id_centres_de_formation` int(11) NOT NULL,
  `centres_nom` varchar(100) NOT NULL,
  `id_entreprises` int(11) DEFAULT NULL,
  `entreprises_siret` varchar(20) DEFAULT NULL,
  `entreprises_nom` varchar(100) DEFAULT NULL,
  `id_formations` int(11) NOT NULL,
  `formations_nom` varchar(100) NOT NULL,
  `id_session` int(11) NOT NULL,
  `session_nom` varchar(100) NOT NULL,
  `session_dateDebut` date NOT NULL,
  `session_dateFin` date NOT NULL,
  `id_conseillers_financeurs` int(11) DEFAULT NULL,
  `financeurs_nom` varchar(100) DEFAULT NULL,
  `financeurs_prenom` varchar(100) DEFAULT NULL,
  `id_entreprises_financeurs` int(11) DEFAULT NULL,
  `entreprises_financeurs_siret` varchar(20) DEFAULT NULL,
  `entreprises_financeurs_nom` varchar(100) DEFAULT NULL,
  `id_pointages` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pointages_infos`
--

INSERT INTO `pointages_infos` (`id`, `etudiant_email`, `etudiant_nom`, `etudiant_prenom`, `id_centres_de_formation`, `centres_nom`, `id_entreprises`, `entreprises_siret`, `entreprises_nom`, `id_formations`, `formations_nom`, `id_session`, `session_nom`, `session_dateDebut`, `session_dateFin`, `id_conseillers_financeurs`, `financeurs_nom`, `financeurs_prenom`, `id_entreprises_financeurs`, `entreprises_financeurs_siret`, `entreprises_financeurs_nom`, `id_pointages`) VALUES
(65, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 157),
(66, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 158),
(67, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 159),
(68, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 160),
(69, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 161),
(70, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 162),
(71, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 163),
(72, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 164),
(73, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 165),
(74, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 166),
(75, 'hu-victor@hotmail.com', 'TEST', 'Victor', 38, 'LGX Campus', NULL, NULL, NULL, 29, 'Concepteur développeur d\'applications', 59, 'Session test Virginie', '2024-10-03', '2024-10-30', NULL, NULL, NULL, NULL, NULL, NULL, 167);

-- --------------------------------------------------------

--
-- Structure de la table `produit_cerfa`
--

CREATE TABLE `produit_cerfa` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `prix_dossier` varchar(255) NOT NULL,
  `prix_abonement` varchar(255) DEFAULT NULL,
  `caracteristique1` varchar(255) DEFAULT NULL,
  `caracteristique2` varchar(255) DEFAULT NULL,
  `caracteristique3` varchar(255) DEFAULT NULL,
  `caracteristique4` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit_cerfa`
--

INSERT INTO `produit_cerfa` (`id`, `nom`, `type`, `prix_dossier`, `prix_abonement`, `caracteristique1`, `caracteristique2`, `caracteristique3`, `caracteristique4`) VALUES
(2, 'Dossier Apprentissage', '1', '25', '10', 'Remplissage automatique du CERFA et convention', 'Envoi automatique du dossier aux OPCO Responsable', 'Remontée de l’historique de vos dossiers auprès des OPCO', 'Signature électronique des elements du dossier (Cerfa et Convention)'),
(3, 'Facturation Dossier  Apprentissage', '3', '10', '', 'Génération automatique des factures', 'Gestion des échéanciers automatique des OPCO', '', ''),
(7, 'Dossier Professionalisation', '2', '25', '10', 'Remplissage automatique du CERFA et convention', 'Envoi automatique du dossier aux OPCO Responsable', 'Remontée de l’historique de vos dossiers auprès des OPCO', 'Signature électronique des elements du dossier (Cerfa et Convention)'),
(8, 'Facturation Dossier Professionnalisation', '4', '10', '', 'Remplissage automatique du CERFA et convention', 'Gestion des échéanciers automatique des OPCO', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `produit_cerfa_facture`
--

CREATE TABLE `produit_cerfa_facture` (
  `id` int(11) NOT NULL,
  `id_centre` int(255) DEFAULT NULL,
  `id_users` int(255) DEFAULT NULL,
  `id_produit` int(255) NOT NULL,
  `quantite` int(255) DEFAULT NULL,
  `totalDossier` int(255) DEFAULT NULL,
  `totalFacture` int(255) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `totalAbonement` int(255) DEFAULT NULL,
  `date_achat` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit_cerfa_facture`
--

INSERT INTO `produit_cerfa_facture` (`id`, `id_centre`, `id_users`, `id_produit`, `quantite`, `totalDossier`, `totalFacture`, `date_debut`, `date_fin`, `totalAbonement`, `date_achat`) VALUES
(18, 38, NULL, 2, 100, 2500, 2620, '2024-08-23', '2025-08-23', 120, '2024-08-23'),
(19, 38, NULL, 3, 100, 1000, 1000, '2024-08-29', '2024-08-29', NULL, '2024-08-29'),
(20, 2, NULL, 2, 1, 25, 35, '2024-09-02', '2024-10-02', 10, '2024-09-02'),
(21, 2, NULL, 3, 2, 20, 20, '2024-09-02', '2024-09-02', NULL, '2024-09-02'),
(22, 2, NULL, 2, 1, 25, 25, '2024-09-02', '2024-10-02', NULL, '2024-09-09'),
(23, 38, NULL, 2, 1, 25, 25, '2024-08-23', '2025-08-23', NULL, '2024-09-23'),
(24, 38, NULL, 2, 1, 25, 25, '2024-08-23', '2025-08-23', NULL, '2024-09-23'),
(25, 38, NULL, 2, 1, 25, 25, '2024-08-23', '2025-08-23', NULL, '2024-09-23'),
(26, 38, NULL, 2, 1, 25, 25, '2024-08-23', '2025-08-23', NULL, '2024-09-23'),
(27, 38, NULL, 2, 1, 25, 25, '2024-08-23', '2025-08-23', NULL, '2024-09-23'),
(28, 38, NULL, 2, 1, 25, 25, '2024-08-23', '2025-08-23', NULL, '2024-09-23'),
(29, 38, NULL, 3, 1, 10, 10, '2024-09-23', '2024-09-23', NULL, '2024-09-23'),
(30, 38, NULL, 8, 3, 30, 30, '2024-09-23', '2024-09-23', NULL, '2024-09-23'),
(31, 38, NULL, 7, 3, 75, 195, '2024-09-23', '2025-09-23', 120, '2024-09-23'),
(32, 38, NULL, 2, 3, 75, 75, '2024-08-23', '2025-08-23', NULL, '2024-09-24'),
(33, 38, NULL, 2, 2, 50, 50, '2024-08-23', '2025-08-23', NULL, '2024-09-30'),
(34, 38, NULL, 3, 1, 10, 12, '2024-10-03', '2024-10-03', NULL, '2024-10-03'),
(35, 38, NULL, 8, 20, 200, 240, '2024-10-03', '2024-10-03', NULL, '2024-10-03'),
(36, 38, NULL, 7, 1, 25, 174, '2024-10-03', '2025-10-03', 120, '2024-10-03'),
(37, 38, NULL, 2, 5, 125, 150, '2024-08-23', '2025-08-23', NULL, '2024-10-25'),
(38, 38, NULL, 2, 1, 25, 30, '2024-08-23', '2025-08-23', NULL, '2024-12-18');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `nb_place` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `financeur_entreprise_id` int(11) NOT NULL,
  `financeur_entreprise_nom` varchar(100) NOT NULL,
  `date_demande` datetime NOT NULL DEFAULT current_timestamp(),
  `id_conseillers_financeurs` int(11) NOT NULL,
  `id_session` int(11) NOT NULL,
  `id_reservations_statut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservations_statut`
--

CREATE TABLE `reservations_statut` (
  `id` int(11) NOT NULL,
  `nom_statut` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations_statut`
--

INSERT INTO `reservations_statut` (`id`, `nom_statut`) VALUES
(1, 'Envoyée'),
(2, 'En cours de traitement'),
(3, 'Validée'),
(4, 'Refusée');

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `role` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `role`) VALUES
(1, 'Administrateur'),
(2, 'Gestionnaire d\'entreprise'),
(3, 'Gestionnaire de centre'),
(4, 'Formateur'),
(5, 'Étudiant'),
(6, 'Financeur'),
(7, 'ClientCERFA');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `capacite_accueil` int(11) DEFAULT NULL,
  `id_centres_de_formation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `nom`, `capacite_accueil`, `id_centres_de_formation`) VALUES
(71, 'Salle A', 30, 38),
(72, 'Salle B', 25, 38),
(73, 'Salle C', 20, 38);

-- --------------------------------------------------------

--
-- Structure de la table `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `nomSession` varchar(100) NOT NULL,
  `nbPlace` int(11) NOT NULL,
  `id_formations` int(11) NOT NULL,
  `id_centres_de_formation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `session`
--

INSERT INTO `session` (`id`, `dateDebut`, `dateFin`, `nomSession`, `nbPlace`, `id_formations`, `id_centres_de_formation`) VALUES
(59, '2024-10-03', '2024-10-30', 'Session test Virginie', 15, 29, 38),
(60, '2024-10-29', '2025-11-01', 'CDA291024011125', 10, 29, 38);

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `objet` varchar(200) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL,
  `dateCreation` timestamp NOT NULL,
  `reponse` varchar(255) DEFAULT NULL,
  `id_users` int(11) NOT NULL,
  `id_etat_ticket` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tickets`
--

INSERT INTO `tickets` (`id`, `objet`, `telephone`, `description`, `dateCreation`, `reponse`, `id_users`, `id_etat_ticket`) VALUES
(61, 'ticket test', '0666666666', 'test test', '2024-10-14 14:40:59', NULL, 188, 1),
(62, 'ticket test', '0888888888', 'test test', '2024-10-14 14:42:06', NULL, 188, 3),
(63, 'ticket test', '0888888888', 'test test', '2024-10-14 14:43:45', NULL, 188, 1),
(65, 'Problème suppression d\'un formateur à une session', '0606060606', 'J\'ai un problème pour supprimer un formateur d\'une session, le bouton ne fonctionne pas', '2024-10-30 10:42:30', NULL, 253, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tickets_echanges`
--

CREATE TABLE `tickets_echanges` (
  `id` int(11) NOT NULL,
  `dateCreation` timestamp NOT NULL DEFAULT current_timestamp(),
  `contenu` varchar(250) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_tickets` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tickets_echanges`
--

INSERT INTO `tickets_echanges` (`id`, `dateCreation`, `contenu`, `id_users`, `id_tickets`) VALUES
(43, '2024-10-30 10:43:12', 'Quand je clique sur le bouton, il ne se passe rien', 253, 65);

-- --------------------------------------------------------

--
-- Structure de la table `types_event`
--

CREATE TABLE `types_event` (
  `id` int(11) NOT NULL,
  `type_event_nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_event`
--

INSERT INTO `types_event` (`id`, `type_event_nom`) VALUES
(1, 'Général'),
(2, 'Cours'),
(3, 'Privé');

-- --------------------------------------------------------

--
-- Structure de la table `types_infos`
--

CREATE TABLE `types_infos` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_infos`
--

INSERT INTO `types_infos` (`id`, `nom`) VALUES
(1, 'Information'),
(2, 'Problème'),
(3, 'Remarque'),
(4, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `id_role` int(11) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `id_gender` int(11) DEFAULT NULL,
  `id_apolearn` int(11) DEFAULT NULL,
  `username_apolearn` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `id_role`, `reset_token`, `id_gender`, `id_apolearn`, `username_apolearn`) VALUES
(139, 'zeufackheriol9@gmail.com', '$2y$10$TV7Bvn8dnY65kK4nlRqS5u2BDCzxrJ5l7n2tjKdvWMMvz8ncdfi.a', 7, '48aef4ae61b56452c21e3fdb9d358524097743b65977c1b6e96cc1c5d12f7329::::2024-10-30_16:10:16', NULL, NULL, NULL),
(174, 'heriol@lgx-france.fr', '$2y$10$QsyKJAeq.B.mzfIxwEJIp.g8X05wIbqwNd1Ecnyrz3dJYkvWv1b5m', 3, NULL, NULL, NULL, NULL),
(178, 'stephanie@lgx-france.fr', '$2y$10$9wCBXStFq4Wlj9TF2HZEeOk4j8Wy.ee1at7tBXm3cii1rrLGtoTy2', 3, NULL, NULL, NULL, NULL),
(179, 'jessica@lgx-france.fr', '$2y$10$NVgE6J0oVOeHQlwY7LlSqeXRaECO4VN.MVfxTInIv0IZkDHOZ.m0O', 3, NULL, NULL, NULL, NULL),
(188, 'contact@lechosolutions.fr', '$2y$10$Z7D7QN29cx2BXnCDjuRzfOuuq89DISzyMu3okW6u0g4p70da0EFym', 3, NULL, NULL, NULL, NULL),
(195, 'c@lgx.com', '$2y$10$QpYgxwDajw.iLU6R9E9GRO8JcdmlXO8EIvL0UYfp8z9vPes5afAIy', 4, NULL, NULL, NULL, NULL),
(230, 'jerome@lgx-france.fr', '$2y$10$2gMmWkWj2RPBSkwfrlzQ8.2Mo43lygi5udPTQbab968z8bTaU5tp2', 1, NULL, NULL, NULL, NULL),
(238, 'jerome.campus@lgx-france.fr', '$2y$10$5ZyWjg/3PWiRBt4Ij64OoeRQpzTgawG.TQjh.29mAG6UabxmLdsPS', 3, NULL, NULL, NULL, NULL),
(240, 'simon@lgx-france.fr', '$2y$10$gSIBSY5W6jRN1jmmDkxjkOvxkstkI1r7I4sVcRrnRDaTOIubIC2La', 3, NULL, NULL, NULL, NULL),
(247, 'apache-cargo-igloo@duck.com', '$2y$10$N13y/qO4H6lZPf9W4wKSgeQK8/e284lX6KNlJTPN0Qb6YxV0Bci0C', 6, NULL, NULL, NULL, NULL),
(248, 'lanky-lucid-tingly@duck.com', '$2y$10$JGdv9WLv1OrqZ9ySau2rJeeLSniGcCOg8jBVEUXVLrUydDh8GxMX6', 4, NULL, NULL, NULL, NULL),
(249, 'exodus-ditch-maybe@duck.com', '$2y$10$OVq6KhucxRg/DA/1k9uczOC9ztzVTOM/DmemXEqOnA.hlnvVHUZSq', 2, NULL, NULL, NULL, NULL),
(253, 'oozy-voting-expose@duck.com', '$2y$10$UUsZpVJ8fR4VD8nvR1zX9OEC0kp/BM12XSCjXG.4foIv7Mv68EnnG', 3, NULL, NULL, NULL, NULL),
(254, 'upon-gawk-gills@duck.com', '$2y$10$q81teOT6OovUEk2sBeU5d.XFzbzl5QZmwuDOEsHklyudrAFhNgLcK', 5, NULL, NULL, NULL, NULL),
(256, 'ploy-scooter-prone@duck.com', '$2y$10$bzwV885azp03sWsuk67TwONO.BWbt1e1BnWg1hR/2P1E2ZWUtcttO', 5, NULL, NULL, NULL, NULL),
(257, 'aelita-prives@hotmail.fr', '$2y$10$PW.Y7Pk9LlOH279hVPYBEesqJ6xQ9nE3iPzUvFWGDgbw6R746MpDG', 5, '474f27c4e8bb7b76107a9e982c2f30c1c67eb4d19479b90bdcadf4a5c73bc046::::2024-11-19_10:32:25', NULL, 3402, 'virginieformateur'),
(258, 'noreplylgx@gmail.com', '$2y$10$CWtqwOc/2ww7xg6KRAgn..7WioFzH8xVyKMLuV5nIrmOf8WqeFJ4O', 5, NULL, NULL, 71918, 'testetudiant2_virginie_jebp8'),
(259, 'botany-dude-cupped@duck.com', '$2y$10$PbPDjhBScyNEkpylo09JzOKEkG7blRP8yEzonZ6pb87NaIUgzRjEC', 1, NULL, NULL, NULL, NULL),
(265, 'pretty-slam-nimbly@duck.com', '$2y$10$aSRCR/Vi9.1hWQfsJQPX2ORv.kiua/Thagtvgq6vIShdO9pTrhgqi', 3, NULL, NULL, NULL, NULL),
(267, 'developpement@lgx-france.fr', '$2y$10$RjVPPERuUoBsTVZpKYdwX.XXNi1HVMcksYz8U5QZ/EWKmJpLNz83i', 1, NULL, NULL, NULL, NULL),
(269, 'mrueda@rps-groupe.com', '$2y$10$eltpFMcVTr7T9wVtlBb6dO1lrlGOCa8mykyUDEqz9X5HIDsLbnlq.', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `_formations__catalogue_`
--

CREATE TABLE `_formations__catalogue_` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `lienFranceCompetence` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonement_cerfa`
--
ALTER TABLE `abonement_cerfa`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absences_etudiants_FK` (`id_etudiants`);

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `administrateurs_users_FK` (`id_users`);

--
-- Index pour la table `centres_de_formation`
--
ALTER TABLE `centres_de_formation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `centres_de_formation_entreprises_FK` (`id_entreprises`);

--
-- Index pour la table `cerfa`
--
ALTER TABLE `cerfa`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `clients_cerfa`
--
ALTER TABLE `clients_cerfa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_cerfa_users_FK` (`id_users`);

--
-- Index pour la table `conseillers_financeurs`
--
ALTER TABLE `conseillers_financeurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conseillers_financeurs_entreprises_FK` (`id_entreprises`),
  ADD KEY `conseillers_financeurs_users0_FK` (`id_users`);

--
-- Index pour la table `cours`
--
ALTER TABLE `cours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cours_events_AK` (`id_events`),
  ADD KEY `cours_formateurs_FK` (`id_formateurs`),
  ADD KEY `cours_matieres1_FK` (`id_matieres`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `entreprises_type`
--
ALTER TABLE `entreprises_type`
  ADD UNIQUE KEY `id_entreprises` (`id_entreprises`),
  ADD KEY `id_centres_de_formation_FK` (`id_centres_de_formation`);

--
-- Index pour la table `equipements`
--
ALTER TABLE `equipements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `equipements_salles_FK` (`id_salles`);

--
-- Index pour la table `etat_ticket`
--
ALTER TABLE `etat_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiants_conseillers_financeurs1_FK` (`id_conseillers_financeurs`),
  ADD KEY `etudiants_entreprises_FK` (`id_entreprises`),
  ADD KEY `etudiants_session2_FK` (`id_session`),
  ADD KEY `etudiants_users3_FK` (`id_users`),
  ADD KEY `etudiants_centres_formation_FK` (`id_centres_de_formation`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_centres_de_formation2_FK` (`id_centres_de_formation`),
  ADD KEY `events_modalites1_FK` (`id_modalites`),
  ADD KEY `events_types_event3_FK` (`id_types_event`),
  ADD KEY `events_users0_FK` (`id_users`),
  ADD KEY `events_salles_FK` (`id_salles`);

--
-- Index pour la table `event_sessions`
--
ALTER TABLE `event_sessions`
  ADD PRIMARY KEY (`id`,`id_events`),
  ADD KEY `event_sessions_events0_FK` (`id_events`);

--
-- Index pour la table `event_users`
--
ALTER TABLE `event_users`
  ADD PRIMARY KEY (`id`,`id_users`),
  ADD KEY `event_users_users0_FK` (`id_users`);

--
-- Index pour la table `facture_cerfa`
--
ALTER TABLE `facture_cerfa`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `fiches_suivi`
--
ALTER TABLE `fiches_suivi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fiches_suivi_etudiants_AK` (`id_etudiants`),
  ADD KEY `fiches_suivi_formateurs_FK` (`id_formateurs`);

--
-- Index pour la table `formateurs`
--
ALTER TABLE `formateurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formateurs_centres_de_formation_FK` (`id_centres_de_formation`),
  ADD KEY `formateurs_users0_FK` (`id_users`);

--
-- Index pour la table `formateurs_participant_session`
--
ALTER TABLE `formateurs_participant_session`
  ADD PRIMARY KEY (`id`,`id_formateurs`),
  ADD KEY `formateurs_participant_session_formateurs0_FK` (`id_formateurs`);

--
-- Index pour la table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `formations`
--
ALTER TABLE `formations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formations_centres_de_formation_FK` (`id_centres_de_formation`);

--
-- Index pour la table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `gestionnaires_centre`
--
ALTER TABLE `gestionnaires_centre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gestionnaires_centre_centres_de_formation_FK` (`id_centres_de_formation`),
  ADD KEY `gestionnaires_centre_users0_FK` (`id_users`);

--
-- Index pour la table `gestionnaires_entreprise`
--
ALTER TABLE `gestionnaires_entreprise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gestionnaires_entreprise_users0_FK` (`id_users`),
  ADD KEY `gestionnaires_entreprise_entreprises_FK` (`id_entreprises`);

--
-- Index pour la table `infos_complementaires`
--
ALTER TABLE `infos_complementaires`
  ADD PRIMARY KEY (`id`),
  ADD KEY `infos_complementaires_types_FK` (`id_types_infos`),
  ADD KEY `infos_complementaires_etudiants0_FK` (`id_etudiants`),
  ADD KEY `infos_complementaires_users_FK` (`id_users`);

--
-- Index pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `matieres_formations_FK` (`id_formations`),
  ADD KEY `matieres_sessions_FK` (`id_sessions`);

--
-- Index pour la table `modalites`
--
ALTER TABLE `modalites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `opco`
--
ALTER TABLE `opco`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pointages`
--
ALTER TABLE `pointages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pointages_etudiants_FK` (`id_etudiants`);

--
-- Index pour la table `pointages_infos`
--
ALTER TABLE `pointages_infos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pointages_infos_pointages_AK` (`id_pointages`);

--
-- Index pour la table `produit_cerfa`
--
ALTER TABLE `produit_cerfa`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produit_cerfa_facture`
--
ALTER TABLE `produit_cerfa_facture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservations_reservations_statut1_FK` (`id_reservations_statut`),
  ADD KEY `reservations_conseillers_financeurs_FK` (`id_conseillers_financeurs`),
  ADD KEY `reservations_session0_FK` (`id_session`);

--
-- Index pour la table `reservations_statut`
--
ALTER TABLE `reservations_statut`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salles_centres_de_formation_FK` (`id_centres_de_formation`);

--
-- Index pour la table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_formations_FK` (`id_formations`),
  ADD KEY `id_centres_de_formation` (`id_centres_de_formation`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_users_FK` (`id_users`),
  ADD KEY `tickets_etat_ticket0_FK` (`id_etat_ticket`);

--
-- Index pour la table `tickets_echanges`
--
ALTER TABLE `tickets_echanges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tickets_echanges_tickets0_FK` (`id_tickets`),
  ADD KEY `tickets_echanges_users_FK` (`id_users`);

--
-- Index pour la table `types_event`
--
ALTER TABLE `types_event`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `types_infos`
--
ALTER TABLE `types_infos`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_role_FK` (`id_role`),
  ADD KEY `users_gender_FK` (`id_gender`);

--
-- Index pour la table `_formations__catalogue_`
--
ALTER TABLE `_formations__catalogue_`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonement_cerfa`
--
ALTER TABLE `abonement_cerfa`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT pour la table `centres_de_formation`
--
ALTER TABLE `centres_de_formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `cerfa`
--
ALTER TABLE `cerfa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT pour la table `clients_cerfa`
--
ALTER TABLE `clients_cerfa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `conseillers_financeurs`
--
ALTER TABLE `conseillers_financeurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `cours`
--
ALTER TABLE `cours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1402;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT pour la table `equipements`
--
ALTER TABLE `equipements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `etat_ticket`
--
ALTER TABLE `etat_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2865;

--
-- AUTO_INCREMENT pour la table `facture_cerfa`
--
ALTER TABLE `facture_cerfa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `fiches_suivi`
--
ALTER TABLE `fiches_suivi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `formateurs`
--
ALTER TABLE `formateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `formations`
--
ALTER TABLE `formations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `gestionnaires_centre`
--
ALTER TABLE `gestionnaires_centre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT pour la table `gestionnaires_entreprise`
--
ALTER TABLE `gestionnaires_entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `infos_complementaires`
--
ALTER TABLE `infos_complementaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `matieres`
--
ALTER TABLE `matieres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `modalites`
--
ALTER TABLE `modalites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `opco`
--
ALTER TABLE `opco`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `pointages`
--
ALTER TABLE `pointages`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT pour la table `pointages_infos`
--
ALTER TABLE `pointages_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT pour la table `produit_cerfa`
--
ALTER TABLE `produit_cerfa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `produit_cerfa_facture`
--
ALTER TABLE `produit_cerfa_facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservations_statut`
--
ALTER TABLE `reservations_statut`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT pour la table `tickets_echanges`
--
ALTER TABLE `tickets_echanges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT pour la table `types_event`
--
ALTER TABLE `types_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `types_infos`
--
ALTER TABLE `types_infos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT pour la table `_formations__catalogue_`
--
ALTER TABLE `_formations__catalogue_`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_etudiants_FK` FOREIGN KEY (`id_etudiants`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD CONSTRAINT `administrateurs_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `centres_de_formation`
--
ALTER TABLE `centres_de_formation`
  ADD CONSTRAINT `centres_de_formation_entreprises_FK` FOREIGN KEY (`id_entreprises`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `clients_cerfa`
--
ALTER TABLE `clients_cerfa`
  ADD CONSTRAINT `clients_cerfa_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `conseillers_financeurs`
--
ALTER TABLE `conseillers_financeurs`
  ADD CONSTRAINT `conseillers_financeurs_entreprises_FK` FOREIGN KEY (`id_entreprises`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `conseillers_financeurs_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cours`
--
ALTER TABLE `cours`
  ADD CONSTRAINT `cours_events0_FK` FOREIGN KEY (`id_events`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cours_formateurs_FK` FOREIGN KEY (`id_formateurs`) REFERENCES `formateurs` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `cours_matieres1_FK` FOREIGN KEY (`id_matieres`) REFERENCES `matieres` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `entreprises_type`
--
ALTER TABLE `entreprises_type`
  ADD CONSTRAINT `id_centres_de_formation_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_entreprises_FK` FOREIGN KEY (`id_entreprises`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `equipements`
--
ALTER TABLE `equipements`
  ADD CONSTRAINT `equipements_salles_FK` FOREIGN KEY (`id_salles`) REFERENCES `salles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `etudiants_centres_formation_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`),
  ADD CONSTRAINT `etudiants_conseillers_financeurs1_FK` FOREIGN KEY (`id_conseillers_financeurs`) REFERENCES `conseillers_financeurs` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `etudiants_entreprises_FK` FOREIGN KEY (`id_entreprises`) REFERENCES `entreprises` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `etudiants_session2_FK` FOREIGN KEY (`id_session`) REFERENCES `session` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `etudiants_users3_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_centres_de_formation2_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_modalites1_FK` FOREIGN KEY (`id_modalites`) REFERENCES `modalites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_salles_FK` FOREIGN KEY (`id_salles`) REFERENCES `salles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `events_types_event3_FK` FOREIGN KEY (`id_types_event`) REFERENCES `types_event` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `events_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `event_sessions`
--
ALTER TABLE `event_sessions`
  ADD CONSTRAINT `event_sessions_events0_FK` FOREIGN KEY (`id_events`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_sessions_session_FK` FOREIGN KEY (`id`) REFERENCES `session` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `event_users`
--
ALTER TABLE `event_users`
  ADD CONSTRAINT `event_users_events_FK` FOREIGN KEY (`id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `event_users_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fiches_suivi`
--
ALTER TABLE `fiches_suivi`
  ADD CONSTRAINT `fiches_suivi_etudiants0_FK` FOREIGN KEY (`id_etudiants`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `fiches_suivi_formateurs_FK` FOREIGN KEY (`id_formateurs`) REFERENCES `formateurs` (`id`);

--
-- Contraintes pour la table `formateurs`
--
ALTER TABLE `formateurs`
  ADD CONSTRAINT `formateurs_centres_de_formation_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formateurs_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `formateurs_participant_session`
--
ALTER TABLE `formateurs_participant_session`
  ADD CONSTRAINT `formateurs_participant_session_formateurs0_FK` FOREIGN KEY (`id_formateurs`) REFERENCES `formateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formateurs_participant_session_session_FK` FOREIGN KEY (`id`) REFERENCES `session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `formations`
--
ALTER TABLE `formations`
  ADD CONSTRAINT `formations_centres_de_formation_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `gestionnaires_centre`
--
ALTER TABLE `gestionnaires_centre`
  ADD CONSTRAINT `gestionnaires_centre_centres_de_formation_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `gestionnaires_centre_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `gestionnaires_entreprise`
--
ALTER TABLE `gestionnaires_entreprise`
  ADD CONSTRAINT `gestionnaires_entreprise_entreprises_FK` FOREIGN KEY (`id_entreprises`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gestionnaires_entreprise_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `infos_complementaires`
--
ALTER TABLE `infos_complementaires`
  ADD CONSTRAINT `infos_complementaires_etudiants0_FK` FOREIGN KEY (`id_etudiants`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `infos_complementaires_types_FK` FOREIGN KEY (`id_types_infos`) REFERENCES `types_infos` (`id`),
  ADD CONSTRAINT `infos_complementaires_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matieres`
--
ALTER TABLE `matieres`
  ADD CONSTRAINT `matieres_formations_FK` FOREIGN KEY (`id_formations`) REFERENCES `formations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `matieres_sessions_FK` FOREIGN KEY (`id_sessions`) REFERENCES `session` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pointages`
--
ALTER TABLE `pointages`
  ADD CONSTRAINT `pointages_etudiants_FK` FOREIGN KEY (`id_etudiants`) REFERENCES `etudiants` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Contraintes pour la table `pointages_infos`
--
ALTER TABLE `pointages_infos`
  ADD CONSTRAINT `pointages_infos_pointages_FK` FOREIGN KEY (`id_pointages`) REFERENCES `pointages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_conseillers_financeurs_FK` FOREIGN KEY (`id_conseillers_financeurs`) REFERENCES `conseillers_financeurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_reservations_statut1_FK` FOREIGN KEY (`id_reservations_statut`) REFERENCES `reservations_statut` (`id`),
  ADD CONSTRAINT `reservations_session0_FK` FOREIGN KEY (`id_session`) REFERENCES `session` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `salles`
--
ALTER TABLE `salles`
  ADD CONSTRAINT `salles_centres_de_formation_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_centres_de_formation0_FK` FOREIGN KEY (`id_centres_de_formation`) REFERENCES `centres_de_formation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `session_formations_FK` FOREIGN KEY (`id_formations`) REFERENCES `formations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_etat_ticket0_FK` FOREIGN KEY (`id_etat_ticket`) REFERENCES `etat_ticket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `tickets_echanges`
--
ALTER TABLE `tickets_echanges`
  ADD CONSTRAINT `tickets_echanges_tickets0_FK` FOREIGN KEY (`id_tickets`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tickets_echanges_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_gender_FK` FOREIGN KEY (`id_gender`) REFERENCES `gender` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `users_role_FK` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
