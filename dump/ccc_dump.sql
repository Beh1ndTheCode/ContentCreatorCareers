-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 13, 2024 at 06:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ccc`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddCandidate` (IN `in_username` VARCHAR(32), IN `in_password` VARCHAR(32), IN `in_email` VARCHAR(64), IN `in_name` VARCHAR(16), IN `in_surname` VARCHAR(16), INOUT `new_user_id` INT(10) UNSIGNED, INOUT `new_profile_id` INT(10) UNSIGNED)   BEGIN
    INSERT INTO user (username, password, email) VALUES (in_username, in_password, in_email);
    SET new_user_id = LAST_INSERT_ID();
    INSERT INTO profile (user_id) VALUES (new_user_id);
    SET new_profile_id = LAST_INSERT_ID();
    INSERT INTO candidate (id, name, surname) VALUES (new_profile_id, in_name, in_surname);
    INSERT INTO user_role (username, role_id) VALUES (in_username, 2);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddEmployer` (IN `in_username` VARCHAR(32), IN `in_password` VARCHAR(32), IN `in_email` VARCHAR(64), IN `in_name` VARCHAR(16), INOUT `new_user_id` INT(10) UNSIGNED, INOUT `new_profile_id` INT(10) UNSIGNED)   BEGIN
    INSERT INTO user (username, password, email) VALUES (in_username, in_password, in_email);
    SET new_user_id = LAST_INSERT_ID();
    INSERT INTO profile (user_id) VALUES (new_user_id);
    SET new_profile_id = LAST_INSERT_ID();
    INSERT INTO employer (id, name) VALUES (new_profile_id, in_name);
    INSERT INTO user_role (username, role_id) VALUES (in_username, 3);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddJobOffer` (IN `in_employer_id` INT(10) UNSIGNED, IN `in_name` VARCHAR(32), IN `in_salary` FLOAT UNSIGNED, IN `in_type` VARCHAR(16), IN `in_language_id` INT(10) UNSIGNED, IN `in_quantity` SMALLINT(5) UNSIGNED, IN `in_description` TEXT, INOUT `job_offer_id` INT(10) UNSIGNED)   BEGIN
    INSERT INTO job_offer (employer_id, name, salary, type, language_id, quantity, description) VALUES (in_employer_id, in_name, in_salary, in_type, in_language_id, in_quantity, in_description);
    SET job_offer_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `AddRequirement` (IN `in_job_offer_id` INT(10) UNSIGNED, IN `in_name` VARCHAR(32), IN `in_level` TINYINT(2) UNSIGNED, IN `in_description` TEXT)   BEGIN
    INSERT INTO requirement (job_offer_id, name, level, description) VALUES (in_job_offer_id, in_name, in_level, in_description);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `country` varchar(32) NOT NULL,
  `postal_code` int(10) UNSIGNED DEFAULT NULL,
  `city` varchar(32) NOT NULL,
  `street` varchar(64) DEFAULT NULL,
  `civic` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`profile_id`, `country`, `postal_code`, `city`, `street`, `civic`) VALUES
(1, 'Italia', 184, 'Roma', 'Via Nazionale', '123'),
(2, 'Italia', 186, 'Roma', 'Via del Corso', '54'),
(3, 'Spagna', 8007, 'Barcellona', 'Passeig de Gràcia', '45'),
(4, 'Francia', 75001, 'Parigi', 'Rue de Rivoli', '78'),
(6, 'Regno Unito', NULL, 'Londra', 'Bond Street', '120'),
(7, 'Stati Uniti', 94105, 'San Francisco', 'Market Street', '57'),
(16, 'Italia', 50123, 'Firenze', 'Via dei Calzaiuoli', '10'),
(17, 'Stati Uniti', 10001, 'New York', 'Broadway', '234'),
(18, 'Stati Uniti', 30303, 'Atlanta', 'Peachtree Street\r\n', '789'),
(19, 'Francia', 75008, 'Parigi', 'Avenue Montaigne', '55'),
(20, 'Italia', 187, 'Roma', 'Via del Corso', '99'),
(21, 'Italia', 100, 'Roma', 'Viale Mazzini', '14'),
(22, 'Italia', 20125, 'Milano', 'Viale San Raffaele', '27'),
(23, 'Francia', 75008, 'Parigi', '14 Rue Royale', '10'),
(24, 'Italia', 50123, 'Firenze', 'Via della Vigna Nuova', '7'),
(25, 'US', 10018, 'New York', 'Eighth Avenue', '620'),
(26, 'UK', 0, 'Londra', 'Kings Place', '90'),
(27, 'US', 95054, 'San Jose', 'Park Avenue', '345'),
(28, 'US', 20036, 'Washington D.C', '17th street NW', '1145'),
(29, 'UK', 0, 'Londra', 'Wood Lane', '201'),
(30, 'US', 10036, 'New York', 'Times Square', '7'),
(31, 'US', 78701, 'Austin', 'Lavaca Street', '1400'),
(32, 'US', 20036, 'Washington D.C.', 'North Capitol Street NE', '1111'),
(33, 'US', 10169, 'New York', 'Fifth Avenue', '60'),
(34, 'US', 10111, 'New York', 'Hudson Street', '395'),
(35, 'US', 55905, 'Rochester', 'First Street SW', '200'),
(36, 'US', 10036, 'New York', 'World Trade Center', '1'),
(38, 'US', 10010, 'New York', 'Avenue of the Americans', '1177'),
(39, 'US', 94107, 'San Francisco', 'Bush Street', '350'),
(40, 'US', 90210, 'Los Angeles', 'North Central Avenue\r\n', '655');

-- --------------------------------------------------------

--
-- Table structure for table `application`
--

CREATE TABLE `application` (
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `job_offer_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application`
--

INSERT INTO `application` (`candidate_id`, `job_offer_id`, `date`) VALUES
(3, 19, '2024-09-04'),
(4, 20, '2024-08-21'),
(7, 1, '2024-07-01'),
(16, 21, '2024-09-11'),
(17, 22, '2024-08-12');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(16) NOT NULL,
  `surname` varchar(16) NOT NULL,
  `age` tinyint(2) DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `name`, `surname`, `age`, `language_id`) VALUES
(1, 'Mario', 'Rossi', 33, 2),
(3, 'Maria', 'González', 28, 3),
(4, 'Pierre', 'Dupont', 45, 4),
(6, 'Anna', 'Kovalenko', 31, 10),
(7, 'John', 'Smith', 50, 1),
(16, 'Luca', 'Bianchi', 37, 2),
(17, 'Emma', 'Schmidt', 26, 5),
(18, 'Carolina', 'Martínez', 29, 3),
(19, 'Sophie', 'Leblanc', 33, 4),
(20, 'Marco', 'Moretti', 39, 2);

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `service_id` int(10) UNSIGNED DEFAULT NULL,
  `sottotitolo1` varchar(255) DEFAULT NULL,
  `sottotitolo2` varchar(255) DEFAULT NULL,
  `sottotitolo3` varchar(255) DEFAULT NULL,
  `sottotitolo4` varchar(255) DEFAULT NULL,
  `testo1` text DEFAULT NULL,
  `testo2` text DEFAULT NULL,
  `testo3` text DEFAULT NULL,
  `testo4` text DEFAULT NULL,
  `immagine1` varchar(255) DEFAULT NULL,
  `immagine2` varchar(255) DEFAULT NULL,
  `immagine3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`service_id`, `sottotitolo1`, `sottotitolo2`, `sottotitolo3`, `sottotitolo4`, `testo1`, `testo2`, `testo3`, `testo4`, `immagine1`, `immagine2`, `immagine3`) VALUES
(24, 'What is a dummy text?', 'Why another dummy text generator?', 'Why doesn’t the generator put as many characters as I told him?', 'What means “Print special chars as HTML entities”?', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', NULL, NULL, NULL),
(25, 'Register an account', 'Specify & Search Your Job', 'Apply For Job', NULL, 'inJob is the leading and longest-running online recruitment in Turkey. We understand that job-seekers come to us not only for a job, but for an pportunity to realize their professional.', 'You’ll receive applications via email. You can also manage jobs and candidates from your Indeed dashboard. Review applications, Schedule interviews and view recommended candidates all from one place.', 'inJob is the leading and longest-running online recruitment in Turkey. We understand that job-seekers come to us not only for a job, but for an pportunity to realize their professional.', NULL, 'uploads/content_images/page25_immagine1.png', 'uploads/content_images/page25_immagine2.png', 'uploads/content_images/page25_immagine3.png'),
(26, '1. Terms', '2. Limitations', '3. Revisions and Errata', '4. Site Terms of Use Modifications', 'By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.', 'Whilst we try to ensure that the standard of the Website remains high and to maintain the continuity of it, the internet is not an inherently stable medium, and errors, omissions, interruptions of service and delays may occur at any time. We do not accept any liability arising from any such errors, omissions, interruptions or delays or any ongoing obligation or responsibility to operate the Website (or any particular part of it) or to provide the service offered on the Website. We may vary the specification of this site from time to time without notice', 'You may only use the Website for lawful purposes when seeking employment or help with your career, when purchasing training courses or when recruiting staff. You must not under any circumstances seek to undermine the security of the Website or any information submitted to or available through it. In particular, but without limitation, you must not seek to access, alter or delete any information to which you do not have authorised access, seek to overload the system via spamming or flooding, take any action or use any device, routine or software to crash, delay, damage or otherwise interfere with the operation of the Website or attempt to decipher, disassemble or modify any of the software, coding or information comprised in the Website.', 'Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally wow absolutely crud meretriciously hastily dalmatian a glowered inset one echidna cassowary some parrot and much as goodness some froze the sullen much connected bat wonderfully on instantaneously. Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally.', NULL, NULL, NULL),
(27, 'About Job Hunt', 'What we do', 'Our Service', 'What means “Print special chars as HTML entities”?', 'Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally wow absolutely crud meretriciously hastily dalmatian a glowered inset one echidna cassowary some parrot and much as goodness some froze the sullen much connected bat wonderfully on instantaneously eel valiantly petted this along across highhandedly much.Repeatedly dreamed alas opossum but dramatically despite expeditiously that jeepers loosely yikes that as or eel underneath kept and slept compactly far purred sure abidingly up above fitting to strident wiped set waywardly far the and pangolin horse approving paid chuckled cassowary oh above a much opposite far much hypnotically more therefore wasp less that hey apart well like while superbly orca and far hence one.Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy.', 'Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally wow absolutely crud meretriciously hastily dalmatian a glowered inset one echidna cassowary some parrot and much as goodness some froze the sullen much connected bat wonderfully on instantaneously eel valiantly petted this along across highhandedly much.Repeatedly dreamed alas opossum but dramatically despite expeditiously that jeepers loosely yikes that as or eel underneath kept and slept compactly far purred sure abidingly up above fitting to strident wiped set waywardly far the and pangolin horse approving paid chuckled cassowary oh above a much opposite far much hypnotically more therefore wasp less that hey apart well like while superbly orca and far hence one.Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE `employer` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `since` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employer`
--

INSERT INTO `employer` (`id`, `name`, `since`) VALUES
(2, 'CNB Comunicazione', '2009'),
(21, 'Rai', '2015'),
(22, 'MediaWorld', '2001'),
(23, 'L\'Oreal', '2015'),
(24, 'Gucci', '2024'),
(25, 'The New York Tim', '2006'),
(26, 'The Guardian', '2019'),
(27, 'Adobe', '2014'),
(28, 'Pentagram', '2016'),
(29, 'National Geograp', '2011'),
(30, 'BBC Earth', '2009'),
(31, 'Travel + Leisure', '2014'),
(32, 'Lonely Planet', '2023'),
(33, 'NPR', '2018'),
(34, 'Forbes', '2008'),
(35, 'WebMD', '2013'),
(36, 'Mayo Clinic', '2020'),
(37, 'Vogue', '2007'),
(38, 'Elle', '2019'),
(39, 'Twitch', '2016'),
(40, 'IGN', '2021');

-- --------------------------------------------------------

--
-- Table structure for table `expertise`
--

CREATE TABLE `expertise` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expertise`
--

INSERT INTO `expertise` (`id`, `title`) VALUES
(1, 'Ads'),
(14, 'Graphic Design'),
(16, 'Magazine'),
(15, 'News'),
(11, 'Podcasting'),
(9, 'Social Media Manager'),
(12, 'Streaming'),
(2, 'Videomaking'),
(13, 'Vlogging'),
(10, 'Writer / Blogger');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(10) UNSIGNED NOT NULL,
  `profile_id` int(10) UNSIGNED NOT NULL,
  `type` enum('profilo','banner','portfolio') NOT NULL,
  `path` varchar(256) NOT NULL DEFAULT 'skins/jobhunt/images/profile.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `profile_id`, `type`, `path`) VALUES
(14, 27, 'profilo', 'uploads/profile_images/resized_66e20b9e63714.png'),
(15, 30, 'profilo', 'uploads/profile_images/resized_66e20be731580.png'),
(16, 21, 'profilo', 'uploads/profile_images/resized_66e20c93e4bf5.jpg'),
(17, 23, 'profilo', 'uploads/profile_images/resized_66e20ceedfb09.png'),
(18, 34, 'profilo', 'uploads/profile_images/resized_66e20d33509dc.png'),
(19, 25, 'profilo', 'uploads/profile_images/resized_66e20dcfcb1e5.png'),
(20, 7, 'profilo', 'uploads/profile_images/resized_66e2a3c5e9a03.jpg'),
(21, 6, 'profilo', 'uploads/profile_images/resized_66e2a3f6c91e4.jpg'),
(22, 3, 'profilo', 'uploads/profile_images/resized_66e2a42351ed1.jpg'),
(23, 4, 'profilo', 'uploads/profile_images/resized_66e2a444f10d8.png'),
(24, 20, 'profilo', 'uploads/profile_images/resized_66e2a4e5aef46.jpeg'),
(25, 1, 'profilo', 'uploads/profile_images/resized_66e2a55f414ee.jpg'),
(26, 7, 'portfolio', 'uploads/portfolios/66e2af2f8b511.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `id` int(10) UNSIGNED NOT NULL,
  `employer_id` int(10) UNSIGNED NOT NULL,
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `type` enum('current','past') NOT NULL,
  `first_work_date` date NOT NULL,
  `last_work_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job`
--

INSERT INTO `job` (`id`, `employer_id`, `candidate_id`, `name`, `type`, `first_work_date`, `last_work_date`, `description`) VALUES
(9, 21, 1, 'Videomaker e Produttore di Conte', 'current', '2016-01-15', NULL, 'Videomaker e produttore di contenuti video per programmi e documentari televisivi.'),
(10, 2, 1, 'Video Content Producer', 'past', '2013-05-10', '2015-12-31', 'Responsabile della produzione di contenuti video per campagne pubblicitarie e promozionali.'),
(11, 23, 3, 'Social Media Manager', 'past', '2019-04-15', NULL, 'Social Media Manager, responsabile della strategia e gestione delle campagne pubblicitarie per i prodotti di bellezza.'),
(12, 24, 3, 'Social Media Coordinator', 'past', '2016-03-01', '2019-02-28', 'Coordinatrice dei contenuti sui social media e analisi delle performance per la linea di abbigliamento di lusso.\r\n'),
(13, 25, 4, 'Content Writer', 'current', '2010-09-01', NULL, 'Content Writer e Blogger, scrive articoli su cultura, arte e letteratura per una delle principali testate giornalistiche internazionali.\r\n'),
(14, 26, 4, 'Editorial Writer', 'past', '2005-01-01', '2010-04-18', 'Redattore e curatore di contenuti per un blog dedicato alla letteratura e alle recensioni di libri.'),
(15, 28, 6, 'Freelance Graphic Designer', 'past', '2014-09-01', '2023-11-30', 'Graphic Designer freelance, specializzata in design per contenuti social media e branding.'),
(16, 29, 16, 'Documentarista e Videomaker', 'past', '2024-02-01', NULL, 'Documentarista e videomaker, specializzato in progetti su sostenibilità e ambiente.'),
(17, 30, 16, 'Video Producer', 'past', '2015-03-01', '2021-01-31', 'Produttore di video e contenuti multimediali per campagne di sensibilizzazione ambientale.'),
(18, 31, 17, 'Travel Vlogger', 'current', '2018-05-01', NULL, 'Travel Vlogger e fotografa, crea contenuti video e fotografici su destinazioni di viaggio per un canale YouTube di successo.'),
(19, 33, 7, 'Podcast Producer', 'current', '2012-06-01', NULL, 'Creatore di contenuti per podcast e articoli su temi legati al mercato immobiliare.'),
(20, 34, 7, 'Radio Host', 'past', '2005-04-01', '2009-12-31', 'Host di un programma radiofonico.'),
(21, 35, 18, 'Health Content Specialist', 'past', '2017-01-01', '2021-06-30', 'Coordinatrice dei contenuti e specialista in marketing per campagne di salute e fitness.'),
(22, 37, 19, 'Fashion Blogger e Influencer', 'current', '2017-11-01', NULL, 'Blogger di moda e influencer, gestisce contenuti e collaborazioni con marchi del settore moda.\r\n'),
(23, 38, 19, 'Fashion Editor', 'past', '2013-06-01', '2016-10-31', 'Redattrice e stilista per articoli su tendenze di moda e recensioni di collezioni.'),
(24, 39, 20, 'Gaming Streamer', 'current', '2018-09-01', NULL, 'Streamer nel settore gaming, con focus su giochi e tecnologie emergenti.');

-- --------------------------------------------------------

--
-- Table structure for table `job_offer`
--

CREATE TABLE `job_offer` (
  `id` int(10) UNSIGNED NOT NULL,
  `employer_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `salary` float UNSIGNED NOT NULL,
  `type` enum('Full time','Part time','Temporary','Freelance','Internship','Volunteer') NOT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_offer`
--

INSERT INTO `job_offer` (`id`, `employer_id`, `name`, `salary`, `type`, `language_id`, `quantity`, `description`, `date`) VALUES
(1, 2, 'Social Media Specialist', 40000, 'Full time', 2, 2, 'CNB Comunicazione cerca un content creator e social media specialist per sviluppare e gestire strategie di contenuto sui social media, creare post, articoli e campagne pubblicitarie. Il candidato ideale avrà esperienza nella creazione di contenuti coinvolgenti e una buona comprensione delle tendenze del settore.', '2024-08-21'),
(18, 21, 'Videomaker', 45000, 'Full time', 2, 4, 'La Rai cerca un videomaker e produttore di contenuti per creare e gestire contenuti video per programmi e documentari televisivi. Il candidato ideale avrà esperienza nella produzione di video di alta qualità e una forte comprensione delle tecniche di editing e post-produzione.', '2024-09-01'),
(19, 23, 'Social Media Manager', 55000, 'Temporary', 3, 1, 'L\'Oreal è alla ricerca di un Social Media Manager per gestire e ottimizzare le campagne pubblicitarie sui social media. Il candidato dovrà sviluppare strategie di contenuto e analizzare le performance delle campagne per promuovere i prodotti di bellezza.', '2024-08-19'),
(20, 25, 'Blogger', 70000, 'Full time', 1, 3, 'Il New York Times cerca content writer e blogger per scrivere articoli su cultura, arte e letteratura. Il ruolo include la creazione di contenuti originali, ricerca approfondita e collaborazione con il team editoriale.\r\n', '2024-06-11'),
(21, 29, 'Videomaker', 75000, 'Full time', 1, 2, 'National Geographic è in cerca di un documentarista e videomaker per lavorare su progetti riguardanti sostenibilità e ambiente. Il ruolo prevede la produzione di documentari e la gestione di contenuti visivi.', '2024-06-01'),
(22, 31, 'Fotografo', 55000, 'Freelance', 1, 1, 'Travel + Leisure cerca un travel fotografo per creare contenuti video e fotografici su destinazioni di viaggio. Il candidato ideale deve avere esperienza nella creazione di contenuti di alta qualità e una passione per i viaggi.', '2024-09-11'),
(23, 35, 'Content Creator', 60000, 'Full time', 1, 2, 'WebMD cerca un content creator per salute e benessere per gestire contenuti su social media e blog. Il candidato dovrà creare articoli informativi e coinvolgenti e gestire le interazioni con il pubblico.', '2024-07-01'),
(24, 34, 'Radio Host', 50000, 'Part time', 1, 1, 'Forbes cerca un radio host e producer per condurre e produrre programmi radiofonici su temi finanziari e di investimento. Il candidato ideale avrà esperienza nella produzione di contenuti radiofonici e una conoscenza approfondita del mercato finanziario', '2023-11-10');

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE `language` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`id`, `name`) VALUES
(7, 'Arabic'),
(1, 'English'),
(4, 'French'),
(5, 'German'),
(11, 'Hindi'),
(2, 'Italian'),
(6, 'Mandarin Chinese'),
(10, 'Russian'),
(3, 'Spanish');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `user_id`, `email`, `phone`, `description`) VALUES
(1, 2, 'mariorossi@gmail.com', '+39 345 1234567', 'Content creator specializzato in video educativi su ingegneria e tecnologia, con un canale YouTube di successo e una serie di podcast dedicati all\'innovazione.'),
(2, 3, 'info@cnbcomunicazione.it', '+39 06 8941 8960', 'CNB Comunicazione nasce a Roma nel 2009, sulla base di una pregressa e profonda formazione nel mondo pubblicitario che ha visto evolvere nel corso degli anni sotto la spinta dei grandi cambiamenti del mercato e della tecnologia. Come agenzia pubblicitaria e di web e digital marketing è in grado di rispondere a varie esigenze, grazie allo sviluppo di un’ampia rete di canali e formati pubblicitari. Al suo interno operano una serie di figure specializzate nella gestione, nella distribuzione e nella creazione di campagne pubblicitarie cinematografiche, radiofoniche, di affissioni statiche e dinamiche, web e social media marketing, con una particolare attenzione all’immagine e  all’identità attraverso lo studio della Brand Identity e la produzione di video e di servizi fotografici. CNB Comunicazione ha una consolidata esperienza nella pubblicità nelle sale cinematografiche attraverso spot pubblicitari. Attraverso il grande schermo puoi comunicare in modo incisivo, mirato ed efficace, grazie soprattutto a quelle pubblicità cinematografiche ad alto valore creativo ed estetico e far conoscere a tua azienda nelle sale cinematografiche del circuito Ferrero e in tutta Italia nel circuito Rai Cinema. Dal 2019 partner commerciale e creativo di Cinevillage Arena Parco Talenti, all’interno della rassegna Estate Romana del Comune di Roma.  Negli stessi anni, ha ampliato la gamma di servizi nel settore radiofonico.'),
(3, 4, 'mariagonzalez@gmail.com', '+34 612 234567', 'Social media manager e content strategist, crea campagne di marketing virali per marchi di moda e lifestyle, con una forte presenza su Instagram e TikTok.'),
(4, 5, 'pierredupont@gmail.com', '+33 654 987654', 'Blogger e scrittore freelance, con un sito dedicato alla cultura francese e articoli su arte, cinema e letteratura. Collabora con testate online e piattaforme di blogging.'),
(6, 7, 'annakovalenko@gmail.com', '+7 921 3456789', 'Graphic designer e illustratrice, crea contenuti visivi per YouTube e Instagram, concentrandosi su tutorial di design e progetti creativi.'),
(7, 8, 'johnsmith@gmail.com', '+44 7700 900123', 'Podcaster e content writer nel settore immobiliare, con un focus su investimenti e strategie finanziarie. Pubblica regolarmente articoli e guide online.'),
(16, 15, 'lucabianchi@gmail.com', '+39 349 7654321', 'Videomaker e regista freelance, produce documentari e cortometraggi per piattaforme digitali, concentrandosi su tematiche ambientali e sostenibilità.'),
(17, 17, 'emmaschmidt@gmail.com', '+49 171 2345678', 'Travel vlogger e fotografa, documenta le sue avventure in giro per il mondo attraverso vlog su YouTube e fotografie di viaggio su Instagram.'),
(18, 18, 'carolinamartinez@gmail.com', '+34 610 987654', 'Content creator nel settore della salute e benessere, crea video su YouTube e post su Instagram su temi di medicina preventiva, fitness e stili di vita sani.'),
(19, 19, 'sophieleblanc@gmail.com', '+33 678 123456', 'Influencer e fashion blogger, con un blog di moda di successo e una community attiva su Instagram dove condivide consigli di stile e tendenze.\r\n\r\n'),
(20, 20, 'marcomoretti@gmail.com', '+39 347 6543210', 'Video creator e streamer su Twitch, produce contenuti su tecnologia e gaming, recensendo le ultime novità del settore tech e interagendo con una community globale.'),
(21, 21, 'info@rai.it', '+39 06 361 21', 'Rai è la principale emittente televisiva pubblica italiana, operante anche nel settore della radio e dei media digitali.'),
(22, 22, 'info@mediaworld.it', '+39 02 2631 2631', 'MediaWorld è una delle principali catene di elettronica di consumo in Italia, specializzata in vendita al dettaglio di elettronica e elettrodomestici.'),
(23, 23, 'contact@loreal.com', '+33 147 56 7000', 'L\'Oreal è uno dei principali produttori mondiali di cosmetici e prodotti di bellezza, con una vasta gamma di marchi e prodotti.'),
(24, 24, 'info@gucci.com', '+39 055 7592 1', 'Gucci è una delle case di moda più prestigiose al mondo, nota per i suoi prodotti di alta moda e accessori di lusso.'),
(25, 25, 'info@nytimes.com', '+1 212-556-1234', 'The New York Times è uno dei principali quotidiani statunitensi, noto per la sua copertura approfondita di notizie e cultura.'),
(26, 26, 'info@theguardian.com', '+44 20 3353 2000', 'The Guardian è un\'importante testata giornalistica britannica, nota per la sua indipendenza editoriale e le sue analisi approfondite.'),
(27, 27, 'contact@adobe.com', '+1 408-536-6000', 'Adobe Systems è una delle principali aziende tecnologiche, specializzata in software per la creazione e la gestione di contenuti digitali.'),
(28, 28, 'info@pentagram.com', NULL, ' Pentagram è uno studio di design grafico di fama internazionale, noto per il suo lavoro innovativo e creativo in branding e design visivo.'),
(29, 29, 'info@ngs.org', '+1 202-857-7000', 'National Geographic è un\'agenzia di media globale che produce contenuti su scienza, esplorazione e cultura, famoso per le sue riviste e documentari.'),
(30, 30, 'info@bbc.com', NULL, 'BBC Earth è il canale di documentari della BBC che si concentra su contenuti di natura e scienza, producendo programmi di alta qualità.'),
(31, 31, 'info@travelandleisure.com', NULL, 'Travel + Leisure è una rivista e sito web dedicato ai viaggi e al turismo, offrendo suggerimenti e guide per le destinazioni globali.'),
(32, 32, 'info@lonelyplanet.com', NULL, 'Lonely Planet è un\'importante casa editrice di guide di viaggio, nota per le sue informazioni dettagliate e consigli utili per i viaggiatori.'),
(33, 33, 'info@webmd.com', '+1 212-838-3400', 'NPR è una rete di radiodiffusione pubblica degli Stati Uniti, fornendo notizie e programmi di alta qualità su temi nazionali e internazionali.'),
(34, 34, 'info@forbes.com', NULL, 'Forbes è una rivista economica e finanziaria che pubblica notizie e analisi su affari, economia, tecnologia e stile di vita.'),
(35, 35, ' info@webmd.com', NULL, 'WebMD è un sito web leader nella fornitura di informazioni sanitarie, offrendo contenuti educativi e risorse per la salute e il benessere.'),
(36, 36, 'contact@mayo.edu', NULL, 'Mayo Clinic è un istituto medico senza scopo di lucro, noto per l\'eccellenza nelle cure mediche e nella ricerca, con una vasta gamma di servizi clinici.'),
(37, 37, 'contact@vogue.com', NULL, 'Vogue è una delle principali riviste di moda e lifestyle al mondo, offrendo contenuti su tendenze di moda, bellezza e cultura'),
(38, 38, 'contact@elle.com', NULL, 'Elle è una rivista di moda internazionale, focalizzata su tendenze, bellezza e cultura femminile, con edizioni in tutto il mondo.'),
(39, 39, 'contact@twitch.tv', NULL, 'Twitch è una piattaforma di streaming video in diretta, principalmente focalizzata su giochi e contenuti interattivi.'),
(40, 40, 'contact@ign.com', NULL, 'IGN è un sito web dedicato ai giochi e all\'intrattenimento, offrendo recensioni, notizie e contenuti multimediali su videogiochi e cultura pop.');

-- --------------------------------------------------------

--
-- Table structure for table `profile_expertise`
--

CREATE TABLE `profile_expertise` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `expertise_id` int(10) UNSIGNED NOT NULL,
  `experience` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_expertise`
--

INSERT INTO `profile_expertise` (`profile_id`, `expertise_id`, `experience`) VALUES
(1, 2, 6),
(2, 1, 7),
(6, 14, 9),
(18, 2, 7),
(17, 13, 5),
(7, 11, 20),
(16, 2, 10),
(20, 12, 6),
(3, 9, 6),
(4, 10, 15),
(19, 10, 8),
(30, 15, NULL),
(34, 15, NULL),
(21, 15, NULL),
(26, 15, NULL),
(39, 12, NULL),
(38, 16, NULL),
(28, 14, NULL),
(25, 15, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `requirement`
--

CREATE TABLE `requirement` (
  `job_offer_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requirement`
--

INSERT INTO `requirement` (`job_offer_id`, `name`, `level`, `description`) VALUES
(1, 'Hootsuite', 90, 'Esperienza avanzata nella gestione e pianificazione di campagne sui social media utilizzando Hootsuite'),
(18, 'Adobe Premiere Pro', 90, 'Esperienza avanzata nell\'uso di Adobe Premiere Pro per l\'editing di video professionali.'),
(18, 'Camera RED', 80, 'Competenze nella gestione e utilizzo della camera RED per riprese ad alta risoluzione.'),
(19, 'Facebook Ads', 90, 'Esperienza avanzata nella creazione e ottimizzazione di campagne pubblicitarie su Facebook Ads.'),
(19, 'Google Analytics', 70, 'Capacità di utilizzare Google Analytics per monitorare e analizzare le performance delle campagne sui social media.'),
(20, 'SEO', 90, 'Esperienza nella scrittura di contenuti ottimizzati per i motori di ricerca (SEO) per migliorare la visibilità online'),
(20, 'Zotero', 90, 'Abilità nell\'utilizzo di Zotero per la gestione e citazione delle fonti durante la ricerca di contenuti.'),
(21, 'DaVinci Resolve', 80, 'Competenze avanzate nell\'uso di DaVinci Resolve per il color grading e l\'editing video.'),
(22, 'Adobe illustrator', 90, 'Abilità nell\'uso di Adobe illustrator per la post-produzione delle foto'),
(23, 'Hootsuite', 70, 'Competenze nell\'uso di Hootsuite per la gestione e pianificazione dei contenuti sui social media.'),
(23, 'Scrittura con CMS', 80, 'Esperienza nell\'uso di CMS come WordPress per la creazione e gestione di contenuti web.'),
(24, 'Abilità di Comunicazione', 90, 'Eccellenti capacità di comunicazione verbale e presentazione per condurre programmi radiofonici e interagire con gli ascoltatori.');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'Administrator', NULL),
(2, 'Candidate', NULL),
(3, 'Employer', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_service`
--

CREATE TABLE `role_service` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `service_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_service`
--

INSERT INTO `role_service` (`role_id`, `service_id`) VALUES
(1, 1),
(1, 23),
(2, 2),
(2, 3),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 13),
(2, 19),
(2, 21),
(2, 22),
(3, 3),
(3, 6),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(3, 20);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `script` varchar(255) DEFAULT NULL,
  `default` varchar(1) NOT NULL,
  `description` text DEFAULT NULL,
  `permission` varchar(1) NOT NULL,
  `entity` varchar(100) NOT NULL,
  `field` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`id`, `name`, `script`, `default`, `description`, `permission`, `entity`, `field`) VALUES
(1, 'Dashboard', 'dashboard.php', '', NULL, '', '', ''),
(2, 'Candidate profile', 'candidates_profile.php', '', NULL, '', '', ''),
(3, 'Candidate single', 'candidates_single.php', '', NULL, '', '', ''),
(4, 'Candidate resume', 'candidates_my_resume.php', '', NULL, '', '', ''),
(5, 'Candidate new resume', 'candidates_my_resume_add_new.php', '', NULL, '', '', ''),
(6, 'Candidate list', 'candidates_list.php', '', NULL, '', '', ''),
(7, 'Candidate job alert', 'candidates_job_alert.php', '', NULL, '', '', ''),
(8, 'Candidate cv', 'candidates_cv_cover_letter.php', '', NULL, '', '', ''),
(9, 'Candidate change password', 'candidates_change_password.php', '', NULL, '', '', ''),
(10, 'Candidate applied jobs', 'candidates_applied_jobs.php', '', NULL, '', '', ''),
(11, 'employer job alert', 'employer_job_alert.php', '', NULL, '', '', ''),
(12, 'employer change password', 'employer_change_password.php', '', NULL, '', '', ''),
(13, 'employer list', 'employer_list.php', '', NULL, '', '', ''),
(14, 'employer manage jobs', 'employer_manage_jobs.php', '', NULL, '', '', ''),
(15, 'employer packages', 'employer_packages.php', '', NULL, '', '', ''),
(16, 'employer post new', 'employer_post_new.php', '', NULL, '', '', ''),
(17, 'employer profile', 'employer_profile.php', '', NULL, '', '', ''),
(18, 'employer resume', 'employer_resume.php', '', NULL, '', '', ''),
(19, 'employer single', 'employer_single.php', '', NULL, '', '', ''),
(20, 'Employer Add Requirements', 'employer_add_requirements.php', '', NULL, '', '', ''),
(21, 'Candidates edit job', 'candidates_edit_job.php', '', NULL, '', '', ''),
(22, 'Candidates edit skill', 'candidates_edit_skill.php', '', NULL, '', '', ''),
(23, 'Content Menagment', 'content_menagment.php', '', NULL, '', '', ''),
(24, 'FAQ', 'faq.php', '', NULL, '', '', ''),
(25, 'How It Works', 'how_it_works.php', '', NULL, '', '', ''),
(26, 'Terms & Condition', 'terms_and_condition.php', '', NULL, '', '', ''),
(27, 'About Us', 'about.php', '', NULL, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `skill`
--

CREATE TABLE `skill` (
  `candidate_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`candidate_id`, `name`, `level`, `description`) VALUES
(1, 'Gestione di Community', 60, 'Buona capacità di interazione e coinvolgimento con il pubblico sui social media.'),
(1, 'Scriptwriting', 70, 'Abile nella scrittura di sceneggiature e testi per video educativi e informativi.'),
(1, 'SEO per YouTube', 80, 'Competente nella gestione delle parole chiave e ottimizzazione dei contenuti per aumentare la visibilità.'),
(1, 'Video Editing', 90, 'Eccellente nell\'editing video con esperienza in software come Adobe Premiere e Final Cut Pro.'),
(3, 'Content Creation', 80, 'Abile nella creazione di contenuti visivi e testuali per Instagram e TikTok.'),
(3, 'Graphic Design', 80, 'Ottima competenza nella progettazione grafica per campagne pubblicitarie.'),
(3, 'Social Media Strategy', 90, 'Esperta nella pianificazione e implementazione di strategie sui social media per diversi marchi.'),
(4, 'Content Management', 80, 'Esperto nella gestione e organizzazione dei contenuti su blog e altre piattaforme.'),
(4, 'Editing e Correzione', 80, 'Buona capacità di revisionare e correggere testi.'),
(4, 'Scrittura Creativa', 100, 'Eccellente nello scrivere articoli e post di alta qualità su cultura e arte.'),
(4, 'SEO per Blog', 60, 'Competente nell\'ottimizzazione dei contenuti per i motori di ricerca.'),
(6, 'Graphic Design', 90, 'Eccellente nella creazione di design visivi e illustrazioni per contenuti digitali.'),
(6, 'Illustration', 100, 'Abile nella realizzazione di illustrazioni originali per vari progetti.'),
(6, 'Video Editing', 60, 'Buona competenza nell\'editing video, utile per tutorial e contenuti grafici'),
(7, 'Analisi di Mercato', 80, 'Competente nell\'analisi delle tendenze e delle opportunità di mercato.'),
(7, 'Gestione di Progetti Editoriali', 80, 'Ottima capacità di organizzare e gestire progetti editoriali complessi.\r\n'),
(7, 'Podcasting', 100, 'Eccellente nella creazione e produzione di contenuti podcast per il settore immobiliare.'),
(7, 'Scrittura per Blog', 60, 'Abile nella scrittura di articoli informativi e approfonditi.'),
(16, 'Gestione Progetti', 70, 'Buona capacità di coordinare e gestire progetti di video e documentari.\r\n'),
(16, 'Produzione Audio', 70, 'Competente nella registrazione e editing dell\'audio per video.'),
(16, 'Storytelling', 90, 'Abile nel narrare storie attraverso immagini e video per un impatto visivo forte.'),
(16, 'Videomaking', 100, 'Eccellente nella produzione di video documentari e cortometraggi.\r\n'),
(17, 'Editing Fotografico', 90, 'Competente nell\'editing delle fotografie utilizzando software come Adobe Lightroom.'),
(17, 'Fotografia di Viaggio', 80, 'Abile nella cattura di immagini accattivanti di luoghi e paesaggi.'),
(17, 'Marketing sui Social Media', 50, 'un po\' competenza nella promozione dei contenuti di viaggio sui social media.'),
(17, 'Travel Vlogging', 90, 'Esperta nella creazione di vlog di viaggio coinvolgenti e di alta qualità.'),
(18, 'Creazione Contenuti Fit', 90, 'Esperta nella produzione di video e post informativi su salute e benessere.'),
(18, 'Educazione e Formazione', 80, 'Ottime capacità di educare e formare il pubblico su temi di salute.'),
(18, 'Marketing Digitale', 60, 'Competente nella creazione di campagne di marketing digitale per il settore della salute.'),
(19, 'Fashion Blogging', 90, 'Eccellente nella creazione di contenuti di moda e tendenze su blog e Instagram.'),
(19, 'Fotografia di Moda', 100, 'Alte competente nella cattura di immagini stilistiche e di alta moda.'),
(19, 'Gestione Contenuti Social', 70, 'Buona competenza nella pianificazione e gestione dei contenuti sui social media.'),
(20, 'Gaming Content Creation', 100, 'Eccellente nella creazione di contenuti per il settore del gaming su Twitch e YouTube.'),
(20, 'Recensioni Tecniche', 90, 'Abile nella realizzazione di recensioni e analisi di nuove tecnologie e giochi.'),
(20, 'Streaming Live', 90, 'Ottime competente nella gestione e ottimizzazione di stream live per una grande audience.');

-- --------------------------------------------------------

--
-- Table structure for table `social_account`
--

CREATE TABLE `social_account` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `name` enum('facebook','instagram','linkedin','website') NOT NULL,
  `uri` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_account`
--

INSERT INTO `social_account` (`profile_id`, `name`, `uri`) VALUES
(1, 'facebook', 'https://facebook.com/mariorossi'),
(1, 'instagram', 'https://instagram.com/mariorossi'),
(1, 'linkedin', 'https://linkedin.com/in/mariorossi'),
(1, 'website', 'https://mariorossi.com'),
(2, 'facebook', 'https://facebook.com/CNBcomunicazione'),
(2, 'instagram', 'https://instagram.com/CNBcomunicazione'),
(2, 'linkedin', 'https://Linkedin.com/CNBcomunicazione'),
(2, 'website', 'https://cnbcomunicazione.com'),
(3, 'facebook', 'https://facebook.com/mariagonzalez'),
(3, 'instagram', 'https://instagram.com/mariagonzalez'),
(3, 'linkedin', 'https://linkedin.com/in/mariagonzalez'),
(3, 'website', 'https://mariagonzalez.com/'),
(4, 'facebook', 'https://facebook.com/pierredupont'),
(4, 'instagram', 'https://instagram.com/pierredupont'),
(4, 'linkedin', 'https://linkedin.com/in/pierredupont'),
(4, 'website', 'https://pierredupont.com/'),
(6, 'facebook', 'https://facebook.com/annakovalenko'),
(6, 'instagram', 'https://instagram.com/annakovalenko'),
(6, 'linkedin', 'https://linkedin.com/in/annakovalenko'),
(6, 'website', 'https://annakovalenko.com/'),
(7, 'facebook', 'https://facebook.com/johnsmith'),
(7, 'instagram', 'https://instagram.com/johnsmith'),
(7, 'linkedin', 'https://linkedin.com/in/johnsmith'),
(7, 'website', 'https://johnsmith.com/'),
(16, 'facebook', 'https://facebook.com/lucabianchi'),
(16, 'instagram', 'https://instagram.com/lucabianchi'),
(16, 'linkedin', 'https://linkedin.com/in/lucabianchi'),
(16, 'website', 'https://lucabianchi.com/'),
(17, 'facebook', 'https://facebook.com/emmaschmidt'),
(17, 'instagram', 'https://instagram.com/emmaschmidt'),
(17, 'linkedin', 'https://linkedin.com/in/emmaschmidt'),
(17, 'website', 'https://emmaschmidt.com/'),
(18, 'facebook', 'https://facebook.com/carolinamartinez'),
(18, 'instagram', 'https://instagram.com/carolinamartinez'),
(18, 'linkedin', 'https://linkedin.com/in/carolinamartinez'),
(18, 'website', 'https://carolinamartinez.com/'),
(19, 'facebook', 'https://facebook.com/sophieleblanc'),
(19, 'instagram', 'https://instagram.com/sophieleblanc'),
(19, 'linkedin', 'https://linkedin.com/in/sophieleblanc'),
(19, 'website', 'https://sophieleblanc.com/'),
(20, 'facebook', 'https://facebook.com/marcomoretti'),
(20, 'instagram', 'https://instagram.com/marcomoretti'),
(20, 'linkedin', 'https://linkedin.com/in/marcomoretti'),
(20, 'website', 'https://marcomoretti.com/'),
(22, 'facebook', 'https://facebook.com/Mediaworld'),
(22, 'instagram', 'https://Instagram.com/Mediaworld'),
(22, 'linkedin', 'https://Linkedin.com/Mediaworld'),
(22, 'website', 'https://Mediaworld.com');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'admin', 'admin@example.com'),
(2, 'candidate', 'candidate', 'candidate@example.com'),
(3, 'employer', 'employer', 'employer@example.com'),
(4, 'maria', 'maria', 'maria@gmail.com'),
(5, 'pierre', 'pierre', 'pierre@gmail.com'),
(7, 'anna', 'anna', 'anna@gmail.com'),
(8, 'jhon', 'jhon', 'jhon@gmail.com'),
(15, 'luca', 'luca', 'luca@gmail.com'),
(17, 'emma', 'emma', 'emma@gmail.com'),
(18, 'carolina', 'carolina', 'carolina@gmail.com'),
(19, 'sophie', 'sophie', 'sophie@gmail.com'),
(20, 'Marco', 'Marco', 'Marco@gmail.com'),
(21, 'rai', 'rai', 'rai@gmail.com'),
(22, 'mediaworld', 'mediaworld', 'mediaworld@gmail.com'),
(23, 'oreal', 'oreal', 'oreal@gmail.com'),
(24, 'gucci', 'gucci', 'gucci@gmail.com'),
(25, 'thenewyorktimes', 'thenewyorktimes', 'thenewyorktimes@gmail.com'),
(26, 'theguardian', 'theguardian', 'theguardian@gmail.com'),
(27, 'adobe', 'adobe', 'adobe@gmail.com'),
(28, 'pentagram', 'pentagram', 'pentagram@gmail.com'),
(29, 'nationalgeographic', 'nationalgeographic', 'nationalgeographic@gmail.com'),
(30, 'bbc', 'bbc', 'bbc@gmail.com'),
(31, 'travelandleisure', 'travelandleisure', 'travelandleisure@gmail.com'),
(32, 'lonelyplanet', 'lonelyplanet', 'lonelyplanet@gmail.com'),
(33, 'npr', 'npr', 'npr@gmail.com'),
(34, 'forbes', 'forbes', 'forbes'),
(35, 'webmd', 'webmd', 'webmd'),
(36, 'mayoclinic', 'mayoclinic', 'mayoclinic@gmail.com'),
(37, 'vogue', 'vogue', 'vogue@gmail.com'),
(38, 'elle', 'elle', 'elle@gmail.com'),
(39, 'twitch', 'twitch', 'twitch@gmail.com'),
(40, 'ign', 'ign', 'ign@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `username` varchar(32) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`username`, `role_id`) VALUES
('admin', 1),
('adobe', 3),
('anna', 2),
('bbc', 3),
('candidate', 2),
('carolina', 2),
('elle', 3),
('emma', 2),
('employer', 3),
('forbes', 3),
('gucci', 3),
('ign', 3),
('jhon', 2),
('lonelyplanet', 3),
('luca', 2),
('marco', 2),
('maria', 2),
('mayoclinic', 3),
('mediaworld', 3),
('nationalgeographic', 3),
('npr', 3),
('oreal', 3),
('pentagram', 3),
('pierre', 2),
('rai', 3),
('sophie', 2),
('theguardian', 3),
('thenewyorktimes', 3),
('travelandleisure', 3),
('twitch', 3),
('vogue', 3),
('webmd', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD UNIQUE KEY `foreign_profile_id` (`profile_id`) USING BTREE;

--
-- Indexes for table `application`
--
ALTER TABLE `application`
  ADD UNIQUE KEY `unique_application` (`candidate_id`,`job_offer_id`),
  ADD KEY `foreign_job_offer_id` (`job_offer_id`) USING BTREE,
  ADD KEY `foreign_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_language_id` (`language_id`) USING BTREE;

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD UNIQUE KEY `service_id` (`service_id`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `expertise`
--
ALTER TABLE `expertise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_title` (`title`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `path` (`path`),
  ADD KEY `foreign_profile_id` (`profile_id`) USING BTREE;

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreign_employer_key` (`employer_id`) USING BTREE,
  ADD KEY `foreign_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `job_offer`
--
ALTER TABLE `job_offer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_job_offer` (`employer_id`,`name`) USING BTREE,
  ADD KEY `job_offer_ibfk_2` (`language_id`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `foreign_user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `profile_expertise`
--
ALTER TABLE `profile_expertise`
  ADD KEY `foreign_profile_id` (`profile_id`) USING BTREE,
  ADD KEY `foreign_expertise_id` (`expertise_id`) USING BTREE;

--
-- Indexes for table `requirement`
--
ALTER TABLE `requirement`
  ADD UNIQUE KEY `unique_requirement` (`job_offer_id`,`name`) USING BTREE,
  ADD KEY `foreign_job_offer_id` (`job_offer_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`) USING BTREE;

--
-- Indexes for table `role_service`
--
ALTER TABLE `role_service`
  ADD UNIQUE KEY `unique_role_service` (`role_id`,`service_id`),
  ADD KEY `foreign_role_id` (`role_id`) USING BTREE,
  ADD KEY `foreign_service_id` (`service_id`) USING BTREE;

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD UNIQUE KEY `unique_skill` (`candidate_id`,`name`),
  ADD KEY `foreign_candidate_id` (`candidate_id`) USING BTREE;

--
-- Indexes for table `social_account`
--
ALTER TABLE `social_account`
  ADD UNIQUE KEY `unique_link` (`profile_id`,`name`),
  ADD KEY `foreign_profile_id` (`profile_id`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_username` (`username`) USING BTREE,
  ADD UNIQUE KEY `unique_email` (`email`) USING BTREE;

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD UNIQUE KEY `unique_user_role` (`username`,`role_id`),
  ADD KEY `foreign_user_id` (`username`) USING BTREE,
  ADD KEY `foreign_role_id` (`role_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expertise`
--
ALTER TABLE `expertise`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `job_offer`
--
ALTER TABLE `job_offer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `application_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `application_ibfk_2` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `candidate`
--
ALTER TABLE `candidate`
  ADD CONSTRAINT `candidate_ibfk_1` FOREIGN KEY (`id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `candidate_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `content_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `employer`
--
ALTER TABLE `employer`
  ADD CONSTRAINT `employer_ibfk_1` FOREIGN KEY (`id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job`
--
ALTER TABLE `job`
  ADD CONSTRAINT `job_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employer` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `job_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_offer`
--
ALTER TABLE `job_offer`
  ADD CONSTRAINT `job_offer_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `employer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_offer_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profile_expertise`
--
ALTER TABLE `profile_expertise`
  ADD CONSTRAINT `profile_expertise_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profile_expertise_ibfk_2` FOREIGN KEY (`expertise_id`) REFERENCES `expertise` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `requirement_ibfk_1` FOREIGN KEY (`job_offer_id`) REFERENCES `job_offer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_service`
--
ALTER TABLE `role_service`
  ADD CONSTRAINT `role_service_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `role_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `skill`
--
ALTER TABLE `skill`
  ADD CONSTRAINT `skill_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `social_account`
--
ALTER TABLE `social_account`
  ADD CONSTRAINT `social_account_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
