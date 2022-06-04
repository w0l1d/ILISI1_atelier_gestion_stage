-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2022 at 07:07 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ilisi1_atelier1_gestion_stages`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`) VALUES
(13);

-- --------------------------------------------------------

--
-- Table structure for table `candidature`
--

CREATE TABLE `candidature` (
  `id` int(11) NOT NULL,
  `created_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `status` enum('APPLIED','CANCELED','NACCEPTED','ACCEPTED','AGREED','NAGREED','WAITING') NOT NULL DEFAULT 'APPLIED',
  `updated_date` datetime(6) DEFAULT NULL,
  `etudiant_id` int(11) NOT NULL,
  `offre_id` int(11) NOT NULL,
  `position` tinyint(4) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `candidature`
--

INSERT INTO `candidature` (`id`, `created_date`, `status`, `updated_date`, `etudiant_id`, `offre_id`, `position`) VALUES
(1, '2022-06-02 20:25:20.000000', 'APPLIED', '2022-06-02 20:25:20.000000', 9, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `path` varchar(1024) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `type` varchar(30) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `stage_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `doc_categorie`
--

CREATE TABLE `doc_categorie` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `enseignant`
--

CREATE TABLE `enseignant` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `enseignant`
--

INSERT INTO `enseignant` (`id`) VALUES
(6),
(14);

-- --------------------------------------------------------

--
-- Table structure for table `entreprise`
--

CREATE TABLE `entreprise` (
  `id` int(11) NOT NULL,
  `domaine` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `short_name` varchar(15) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `web_site` varchar(250) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `entreprise`
--

INSERT INTO `entreprise` (`id`, `domaine`, `email`, `logo`, `short_name`, `name`, `phone`, `web_site`, `description`) VALUES
(1, 'IT', 'Involys@test.com', NULL, 'Involys', 'Involys entreprise', '06586584252', 'Involys.com', 'this is a random entreprise in IT'),
(2, 'Corrupti voluptatem sed nihil.', 'your.email+fakedata98995@gmail.com', NULL, '', 'Deondre Wehner', '', '', ''),
(3, 'Dolorem aut voluptatem eligendi vero.', 'your.email+fakedata60477@gmail.com', NULL, 'Courtney McDerm', 'Pink Rogahn', '387-883-1918', 'Missouri', 'Eos praesentium rerum ipsa consequatur inventore assumenda occaecati architecto.'),
(4, 'Dolorem aut voluptatem eligendi vero.', 'your.email+fakedata60477@gmail.com', NULL, 'Courtney McDerm', 'Pink Rogahn', '387-883-1918', 'Missouri', 'Eos praesentium rerum ipsa consequatur inventore assumenda occaecati architecto.'),
(19, 'Laudantium ut aut dicta perspiciatis vel ipsa quae', 'your.email+fakedata72521@gmail.com', 'wP4L2y0CT1-LabVantage.jpg', 'Helene Ortiz', 'Chesley Torp', '760-044-5052', 'South Dakota', 'Natus incidunt exercitationem cupiditate perferendis sed delectus.'),
(20, 'Laudantium ut aut dicta perspiciatis vel ipsa quae', 'your.email+fakedata72521@gmail.com', 'mkzDwZLttp-LabVantage.jpg', 'Helene Ortiz', 'Chesley Torp', '760-044-5052', 'South Dakota', 'Natus incidunt exercitationem cupiditate perferendis sed delectus.'),
(21, 'Dolorem illo consequatur a iste accusantium minus ', 'your.email+fakedata82965@gmail.com', 'rYIzxrIET0-800px-ALTEN_logo.svg.png', 'Lon Kris', 'Christian O\'Keefe', '480-838-5618', 'Connecticut', 'Incidunt quia corrupti tenetur eum unde repudiandae.');

-- --------------------------------------------------------

--
-- Table structure for table `etudiant`
--

CREATE TABLE `etudiant` (
  `cne` varchar(30) NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `promotion` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `formation_id` int(11) NOT NULL,
  `IsValidated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `etudiant`
--

INSERT INTO `etudiant` (`cne`, `cv`, `promotion`, `id`, `formation_id`, `IsValidated`) VALUES
('526-016-1684', NULL, 613, 9, 1, 1),
('055-157-1450', NULL, 2021, 10, 1, 0),
('799-942-2995', NULL, 2021, 12, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `formation`
--

CREATE TABLE `formation` (
  `id` int(11) NOT NULL,
  `short_title` varchar(10) NOT NULL,
  `title` varchar(150) NOT NULL,
  `type` enum('LST','MST','MS','LP','FI') NOT NULL,
  `responsable_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `formation`
--

INSERT INTO `formation` (`id`, `short_title`, `title`, `type`, `responsable_id`) VALUES
(1, 'ilisi', 'integration des sys', 'FI', 6),
(5, 'gmi', 'Regional Operations Agent', 'FI', 14);

-- --------------------------------------------------------

--
-- Table structure for table `note_jury`
--

CREATE TABLE `note_jury` (
  `note` float NOT NULL,
  `jury_id` int(11) NOT NULL,
  `stage_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `offre`
--

CREATE TABLE `offre` (
  `id` int(11) NOT NULL,
  `created_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `delai_offre` date NOT NULL,
  `description` varchar(255) NOT NULL,
  `duree_stage` int(11) NOT NULL,
  `end_stage` date NOT NULL,
  `nbr_stagiaire` tinyint(5) UNSIGNED NOT NULL,
  `start_stage` date NOT NULL,
  `statue` enum('NEW','CLOSED','CANCELED','FULL') NOT NULL,
  `title` varchar(150) NOT NULL,
  `type_stage` enum('PFE','PFA','INIT','SUMMER') NOT NULL,
  `updated_date` datetime(6) DEFAULT NULL,
  `entreprise_id` int(11) NOT NULL,
  `formation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offre`
--

INSERT INTO `offre` (`id`, `created_date`, `delai_offre`, `description`, `duree_stage`, `end_stage`, `nbr_stagiaire`, `start_stage`, `statue`, `title`, `type_stage`, `updated_date`, `entreprise_id`, `formation_id`) VALUES
(1, '2022-05-11 00:00:00.000000', '2022-05-12', 'Molestias minima aspernatur qui aut aliquam distinctio officia quia.', 445, '2022-05-05', 5, '2022-05-11', 'NEW', 'Veritatis et est laboriosam.', 'PFE', '2022-05-12 00:00:00.000000', 1, 1),
(2, '0000-00-00 00:00:00.000000', '2022-07-10', 'Aut nisi aliquid dolores unde alias accusamus animi.', 604, '2021-10-27', 5, '2022-11-18', 'NEW', 'Investor Solutions Associate', 'PFE', '2022-06-01 20:31:07.000000', 4, 1),
(3, '2022-05-30 12:25:02.000000', '2021-06-08', 'Qui dolorem aut quas non ut dolore facilis sint.', 461, '2022-09-01', 255, '2022-10-07', 'CLOSED', 'Dynamic Tactics Producer', 'INIT', '2022-05-30 12:25:02.000000', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `cin` varchar(15) NOT NULL,
  `date_naiss` datetime(6) NOT NULL,
  `email` varchar(80) NOT NULL,
  `fname` varchar(35) NOT NULL,
  `lname` varchar(35) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `person_type` enum('etudiant','enseignant','admin') NOT NULL DEFAULT 'etudiant'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `cin`, `date_naiss`, `email`, `fname`, `lname`, `password`, `phone`, `person_type`) VALUES
(6, 'C4582562', '2022-05-05 00:00:00.000000', 'responsable@test.com', 'test name', 'test lname', 'password', '062548515', 'enseignant'),
(9, 'Lake Kathryn', '2022-06-12 00:00:00.000000', 'test@test.com', 'Eusebio Howe', 'Frida Gutmann', 'password', '500-811-1784', 'etudiant'),
(10, 'Christafurt', '2022-02-15 00:00:00.000000', 'your.email+fakedata73288@gmail.com', 'Cora Beier', 'Maya Gleason', 'RsyiMTReBCQfpeg', '065-907-0059', 'etudiant'),
(11, 'North Chelseabo', '2023-01-29 00:00:00.000000', 'test123@gmail.com', 'Antwon Wolf', 'Arno Rowe', 'test123', '960-206-7465', 'etudiant'),
(12, 'Port Eliseoside', '2022-11-07 00:00:00.000000', 'test@gmail.com', 'Jocelyn Batz', 'Ruthie Medhurst', 'password', '626-366-6311', 'etudiant'),
(13, 'Metastad', '2023-02-19 00:00:00.000000', 'admin@test.com', 'Maurice Bailey', 'Josiah Lebsack', 'password', '060-526-8261', 'etudiant'),
(14, 'Champlinberg', '2022-07-21 00:00:00.000000', 'gmi_resp@test.com', 'Vicente Orn', 'Dario Auer', 'Champlinberg', '157-642-8171', 'enseignant');

-- --------------------------------------------------------

--
-- Table structure for table `stage`
--

CREATE TABLE `stage` (
  `id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `end` date DEFAULT NULL,
  `start` date DEFAULT NULL,
  `statue` enum('IN_PROGRESS','FINISHED','CANCELED','DRAFT') NOT NULL DEFAULT 'DRAFT',
  `created_date` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `updated_date` datetime(6) DEFAULT NULL,
  `entreprise_id` int(11) NOT NULL,
  `stagiaire_id` int(11) NOT NULL,
  `encardant_note` float UNSIGNED DEFAULT 0,
  `encadrant_ext_note` float UNSIGNED DEFAULT 0,
  `encadrant_id` int(11) NOT NULL,
  `candidature_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag_stages`
--

CREATE TABLE `tag_stages` (
  `tags_name` varchar(40) NOT NULL,
  `stages_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidature`
--
ALTER TABLE `candidature`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK10ncm6vkow64cljoui4qc1woh` (`etudiant_id`),
  ADD KEY `FKreoctvslgrncreex2k469kb0g` (`offre_id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKh9ydbngk2dd5pg13ju8g0yjd4` (`categorie_id`),
  ADD KEY `FKq4v7pdqj86nuemlxncd69atdb` (`stage_id`);

--
-- Indexes for table `doc_categorie`
--
ALTER TABLE `doc_categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_etudiant_formation_id` (`formation_id`);

--
-- Indexes for table `formation`
--
ALTER TABLE `formation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UK_ql8cgle8j958dpwuo8g34q5y2` (`responsable_id`),
  ADD UNIQUE KEY `short_title` (`short_title`);

--
-- Indexes for table `note_jury`
--
ALTER TABLE `note_jury`
  ADD KEY `fk_Note_Jury_enseignant1_idx` (`jury_id`),
  ADD KEY `fk_Note_Jury_stage1_idx` (`stage_id`);

--
-- Indexes for table `offre`
--
ALTER TABLE `offre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK4q1eotvo4vb82khw8yij9751f` (`entreprise_id`),
  ADD KEY `FKnjsprgi9302fr93vqnpse06ha` (`formation_id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKsvioihwipr9wd4pkdmww3rbmh` (`entreprise_id`),
  ADD KEY `FKhbveabnr5e0enhqsdd4yritvh` (`stagiaire_id`),
  ADD KEY `fk_stage_enseignant1_idx` (`encadrant_id`),
  ADD KEY `fk_stage_candidature1_idx` (`candidature_id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `tag_stages`
--
ALTER TABLE `tag_stages`
  ADD PRIMARY KEY (`tags_name`,`stages_id`),
  ADD KEY `FKak8shw26c6bbt6w9kggje9uon` (`stages_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidature`
--
ALTER TABLE `candidature`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doc_categorie`
--
ALTER TABLE `doc_categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `formation`
--
ALTER TABLE `formation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `offre`
--
ALTER TABLE `offre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stage`
--
ALTER TABLE `stage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FKsplda61kmlib6vk6qmwfv08dh` FOREIGN KEY (`id`) REFERENCES `person` (`id`);

--
-- Constraints for table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `FK10ncm6vkow64cljoui4qc1woh` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`),
  ADD CONSTRAINT `FKreoctvslgrncreex2k469kb0g` FOREIGN KEY (`offre_id`) REFERENCES `offre` (`id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `FKh9ydbngk2dd5pg13ju8g0yjd4` FOREIGN KEY (`categorie_id`) REFERENCES `doc_categorie` (`id`),
  ADD CONSTRAINT `FKq4v7pdqj86nuemlxncd69atdb` FOREIGN KEY (`stage_id`) REFERENCES `stage` (`id`);

--
-- Constraints for table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `FKql68g3mvgc0veiwb0xs5i3kvs` FOREIGN KEY (`id`) REFERENCES `person` (`id`);

--
-- Constraints for table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `FK8a7f4p5onc7xfu1wdgwvqhjtb` FOREIGN KEY (`id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `FK_etudiant_formation_id` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `formation`
--
ALTER TABLE `formation`
  ADD CONSTRAINT `FKqht2ol4jpiv26do8oxeomowqn` FOREIGN KEY (`responsable_id`) REFERENCES `enseignant` (`id`);

--
-- Constraints for table `note_jury`
--
ALTER TABLE `note_jury`
  ADD CONSTRAINT `fk_Note_Jury_enseignant1` FOREIGN KEY (`jury_id`) REFERENCES `enseignant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Note_Jury_stage1` FOREIGN KEY (`stage_id`) REFERENCES `stage` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `offre`
--
ALTER TABLE `offre`
  ADD CONSTRAINT `FK4q1eotvo4vb82khw8yij9751f` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprise` (`id`),
  ADD CONSTRAINT `FKnjsprgi9302fr93vqnpse06ha` FOREIGN KEY (`formation_id`) REFERENCES `formation` (`id`);

--
-- Constraints for table `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `FKhbveabnr5e0enhqsdd4yritvh` FOREIGN KEY (`stagiaire_id`) REFERENCES `etudiant` (`id`),
  ADD CONSTRAINT `FKsvioihwipr9wd4pkdmww3rbmh` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprise` (`id`),
  ADD CONSTRAINT `fk_stage_candidature1` FOREIGN KEY (`candidature_id`) REFERENCES `candidature` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_stage_enseignant1` FOREIGN KEY (`encadrant_id`) REFERENCES `enseignant` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tag_stages`
--
ALTER TABLE `tag_stages`
  ADD CONSTRAINT `FK_Tag_name_Tags` FOREIGN KEY (`tags_name`) REFERENCES `tag` (`name`),
  ADD CONSTRAINT `FKak8shw26c6bbt6w9kggje9uon` FOREIGN KEY (`stages_id`) REFERENCES `stage` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_offre_status_daily` ON SCHEDULE EVERY 1 DAY STARTS '2022-06-03 01:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE offre o SET o.statue = 'CLOSED' WHERE o.delai_offre >= cast(NOW() as DATE)$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
