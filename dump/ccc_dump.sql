-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2024 at 01:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
(1, 'France', NULL, 'Roma', NULL, NULL),
(2, 'Italy', NULL, 'Rome', NULL, NULL);

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
(1, 17, '2024-09-07'),
(3, 1, '2024-09-04');

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(16) NOT NULL,
  `surname` varchar(16) NOT NULL,
  `age` tinyint(2) DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `about` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidate`
--

INSERT INTO `candidate` (`id`, `name`, `surname`, `age`, `language_id`, `about`) VALUES
(1, 'Mario', 'Rosso', 33, 6, NULL),
(3, 'Luigi', 'Luigini', NULL, NULL, NULL),
(4, 'Valerio', 'Valeri', NULL, NULL, NULL),
(6, 'Alessio', 'Alessi', NULL, NULL, NULL),
(7, 'Giulia', 'Giuliani', NULL, NULL, NULL),
(13, 'fabio', 'volo', NULL, NULL, NULL);

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
(24, 'What is a dummy text?', 'Why another dummy text generator?', 'Why doesn’t the generator put as many characters as I told him?', 'What means “Print special chars as HTML entities”?', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', 'Designer at work who don’t have any content for their product yet have the possibility to insert a dummy text into their design to judge on the arrangement of text on their site, on readability or on fonts and sizes. A dummy text is also helpful to present a design without content to a client to show how the text is going to look like without irritating the client by real texts.', '', NULL, NULL),
(25, 'Register an account', 'Specify & Search Your Job', 'Apply For Job', NULL, 'inJob is the leading and longest-running online recruitment in Turkey. We understand that job-seekers come to us not only for a job, but for an pportunity to realize their professional.', 'You’ll receive applications via email. You can also manage jobs and candidates from your Indeed dashboard. Review applications, Schedule interviews and view recommended candidates all from one place.', 'inJob is the leading and longest-running online recruitment in Turkey. We understand that job-seekers come to us not only for a job, but for an pportunity to realize their professional.', NULL, '', '', ''),
(26, '1. Terms', '2. Limitations', '3. Revisions and Errata', '4. Site Terms of Use Modifications', 'By accessing this web site, you are agreeing to be bound by these web site Terms and Conditions of Use, all applicable laws and regulations, and agree that you are responsible for compliance with any applicable local laws. If you do not agree with any of these terms, you are prohibited from using or accessing this site. The materials contained in this web site are protected by applicable copyright and trade mark law.', 'Whilst we try to ensure that the standard of the Website remains high and to maintain the continuity of it, the internet is not an inherently stable medium, and errors, omissions, interruptions of service and delays may occur at any time. We do not accept any liability arising from any such errors, omissions, interruptions or delays or any ongoing obligation or responsibility to operate the Website (or any particular part of it) or to provide the service offered on the Website. We may vary the specification of this site from time to time without notice', 'You may only use the Website for lawful purposes when seeking employment or help with your career, when purchasing training courses or when recruiting staff. You must not under any circumstances seek to undermine the security of the Website or any information submitted to or available through it. In particular, but without limitation, you must not seek to access, alter or delete any information to which you do not have authorised access, seek to overload the system via spamming or flooding, take any action or use any device, routine or software to crash, delay, damage or otherwise interfere with the operation of the Website or attempt to decipher, disassemble or modify any of the software, coding or information comprised in the Website.', 'Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally wow absolutely crud meretriciously hastily dalmatian a glowered inset one echidna cassowary some parrot and much as goodness some froze the sullen much connected bat wonderfully on instantaneously. Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally.', NULL, NULL, NULL),
(27, 'About Job Hunt\r\n', '', 'Our Service\r\n', NULL, 'Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally wow absolutely crud meretriciously hastily dalmatian a glowered inset one echidna cassowary some parrot and much as goodness some froze the sullen much connected bat wonderfully on instantaneously eel valiantly petted this along across highhandedly much.Repeatedly dreamed alas opossum but dramatically despite expeditiously that jeepers loosely yikes that as or eel underneath kept and slept compactly far purred sure abidingly up above fitting to strident wiped set waywardly far the and pangolin horse approving paid chuckled cassowary oh above a much opposite far much hypnotically more therefore wasp less that hey apart well like while superbly orca and far hence one.Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy.', 'Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy penguin insect additionally wow absolutely crud meretriciously hastily dalmatian a glowered inset one echidna cassowary some parrot and much as goodness some froze the sullen much connected bat wonderfully on instantaneously eel valiantly petted this along across highhandedly much.Repeatedly dreamed alas opossum but dramatically despite expeditiously that jeepers loosely yikes that as or eel underneath kept and slept compactly far purred sure abidingly up above fitting to strident wiped set waywardly far the and pangolin horse approving paid chuckled cassowary oh above a much opposite far much hypnotically more therefore wasp less that hey apart well like while superbly orca and far hence one.Far much that one rank beheld bluebird after outside ignobly allegedly more when oh arrogantly vehement irresistibly fussy.', NULL, NULL, NULL, NULL, NULL);

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
(12, 'Amazon', NULL);

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
(2, 'Sasso carta forbice');

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
(1, 2, 'banner', 'https://media.licdn.com/dms/image/v2/C560BAQGDaVoOAasXWg/company-logo_200_200/company-logo_200_200/0/1631374809829?e=2147483647&v=beta&t=O6nWNnMZdJD-bkk7bHCk1Jy-Qz2xCrCTHBmP7SqL_0I'),
(2, 1, 'profilo', 'uploads/profile_images/resized_66db5135010ba.jpg'),
(6, 2, 'profilo', 'uploads/profile_images/resized_66db44acb3498.jpg'),
(7, 1, 'portfolio', 'https://media.licdn.com/dms/image/v2/C560BAQGDaVoOAasXWg/company-logo_200_200/company-logo_200_200/0/1631374809829?e=2147483647&v=beta&t=O6nWNnMZdJD-bkk7bHCk1Jy-Qz2xCrCTHBmP7SqL_0I');

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
(4, 12, 1, 'Social Media Manager', 'current', '2024-09-01', NULL, 'jdiofeduy'),
(6, 12, 1, 'Graphic Editor', 'past', '2024-06-06', '2024-07-31', ''),
(7, 2, 1, '1234', 'current', '2024-09-02', NULL, NULL),
(8, 2, 1, '5678', 'past', '2024-09-04', '2024-09-05', NULL);

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
(1, 2, 'Visual Designer', 1800, 'Part time', 2, 2, 'Visual design focuses on enhancing the aesthetic and usability of a digital product. It is the strategic implementation of images, colors, fonts, and layouts. Although many visual design elements deal with the look of a product, the feel of the product is equally important. The goal of visual design is to create an interface that provides users with the optimal experience. ', '2024-08-21'),
(17, 2, 'Spazzino', 1, 'Freelance', 6, 1, 'Pulire, TAAC', '2024-09-07');

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
(1, 2, 'mariorossi@gmail.com', '+393333333333', 'ojfiwaiiafwkugx5x'),
(2, 3, 'contact@cnb.com', '+390862111111', 'CNB Comunicazione nasce a Roma nel 2009, sulla base di una pregressa e profonda formazione nel mondo pubblicitario che ha visto evolvere nel corso degli anni sotto la spinta dei grandi cambiamenti del mercato e della tecnologia. Come agenzia pubblicitaria e di web e digital marketing è in grado di rispondere a varie esigenze, grazie allo sviluppo di un’ampia rete di canali e formati pubblicitari. Al suo interno operano una serie di figure specializzate nella gestione, nella distribuzione e nella creazione di campagne pubblicitarie cinematografiche, radiofoniche, di affissioni statiche e dinamiche, web e social media marketing, con una particolare attenzione all’immagine e  all’identità attraverso lo studio della Brand Identity e la produzione di video e di servizi fotografici. CNB Comunicazione ha una consolidata esperienza nella pubblicità nelle sale cinematografiche attraverso spot pubblicitari. Attraverso il grande schermo puoi comunicare in modo incisivo, mirato ed efficace, grazie soprattutto a quelle pubblicità cinematografiche ad alto valore creativo ed estetico e far conoscere a tua azienda nelle sale cinematografiche del circuito Ferrero e in tutta Italia nel circuito Rai Cinema. Dal 2019 partner commerciale e creativo di Cinevillage Arena Parco Talenti, all’interno della rassegna Estate Romana del Comune di Roma.  Negli stessi anni, ha ampliato la gamma di servizi nel settore radiofonico.'),
(3, 4, NULL, NULL, NULL),
(4, 5, NULL, NULL, NULL),
(6, 7, NULL, NULL, NULL),
(7, 8, NULL, NULL, NULL),
(12, 13, NULL, NULL, NULL),
(13, 14, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `profile_expertise`
--

CREATE TABLE `profile_expertise` (
  `profile_id` int(10) UNSIGNED NOT NULL,
  `expertise_id` int(10) UNSIGNED NOT NULL,
  `experience` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile_expertise`
--

INSERT INTO `profile_expertise` (`profile_id`, `expertise_id`, `experience`) VALUES
(1, 2, 5),
(2, 1, 7);

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
(1, 'Adobe Photoshop', 60, 'Basic knowledge of the tool'),
(17, 'Maneggiare la scopa', 80, 'Buona padronanza dello strumento'),
(17, 'Maneggiare lo straccio', 70, 'Lu paviment t\\\'à splenn');

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
  `description` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skill`
--

INSERT INTO `skill` (`candidate_id`, `name`, `level`, `description`) VALUES
(1, 'Maneggio della scopa', 100, 'AAAA'),
(1, 'Teamwork', 40, 'AAA');

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
(1, 'facebook', 'facebook.com'),
(1, 'instagram', 'instagram.com'),
(1, 'website', 'mariorossi.it'),
(2, 'facebook', 'https://www.facebook.com/CNBcomunicazione'),
(2, 'website', 'https://cnbcomunicazione.com');

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
(3, 'employer', 'sas', 'employer@example.com'),
(4, 'luigi', 'luigi', 'luigi@gmail.com'),
(5, 'valerio', 'valerio', 'valerio@gmail.com'),
(7, 'alessio', 'alessio', 'alessio@gmail.com'),
(8, 'giulia', 'giulia', 'giulia@gmail.com'),
(13, 'amazon', 'amazon', 'amazon@amazon.com'),
(14, 'Fabioooooo86', 'volo', 'pswrd.volo@alto.com');

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
('alessio', 2),
('amazon', 3),
('candidate', 2),
('employer', 3),
('Fabioooooo86', 2),
('giulia', 2),
('luigi', 2),
('valerio', 2);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `job`
--
ALTER TABLE `job`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `job_offer`
--
ALTER TABLE `job_offer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
