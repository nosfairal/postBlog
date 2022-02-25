-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 25 fév. 2022 à 17:40
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `postblog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `commentId` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `commentStatus` tinyint(1) NOT NULL DEFAULT 0,
  `author` int(11) NOT NULL,
  `content` text NOT NULL,
  `creationDate` datetime NOT NULL DEFAULT current_timestamp(),
  `publicationDate` datetime DEFAULT NULL,
  `lastUpdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`commentId`, `post`, `commentStatus`, `author`, `content`, `creationDate`, `publicationDate`, `lastUpdate`) VALUES
(9, 49, 1, 19, 'C\'est Benoit!', '2022-01-25 10:24:58', NULL, NULL),
(10, 50, 1, 19, 'Ce post est un peu mieux !', '2022-01-25 13:35:12', NULL, NULL),
(13, 49, 1, 21, 'Pas top ce post!\r\n', '2022-01-28 13:47:18', NULL, NULL),
(14, 52, 1, 20, 'Intéressant!', '2022-02-10 13:40:29', NULL, NULL),
(15, 55, 1, 20, 'Pas très pertinent ce post!', '2022-02-17 13:19:56', NULL, NULL),
(16, 55, 0, 20, 'ce commentaire ne devrait pas apparaitre\r\n', '2022-02-17 13:26:37', NULL, NULL),
(17, 52, 0, 20, 'test commentiD', '2022-02-21 10:02:04', NULL, NULL),
(18, 52, 0, 19, 'Voyons voir ça!', '2022-02-21 14:10:36', NULL, NULL),
(19, 55, 0, 20, 'Pas très développé!', '2022-02-22 09:59:18', NULL, NULL),
(20, 56, 0, 20, 'Très pertinent!', '2022-02-22 18:56:20', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `postId` int(11) NOT NULL,
  `author` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `slug` varchar(30) NOT NULL,
  `intro` text NOT NULL,
  `content` text NOT NULL,
  `creationDate` datetime NOT NULL DEFAULT current_timestamp(),
  `publicationDate` datetime DEFAULT NULL,
  `lastUpdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`postId`, `author`, `title`, `slug`, `intro`, `content`, `creationDate`, `publicationDate`, `lastUpdate`) VALUES
(50, 19, '2ème post', 'deux', 'Le 2ème post du blog!', 'Ceci est mon deuxième post sur ce blog', '2022-01-25 13:20:32', NULL, NULL),
(52, 20, '3ème post', 'trois', 'Le 3ème post du site !', 'Pellentesque in orci metus. Nullam aliquam nunc ligula, dignissim consectetur diam pulvinar nec. Mauris et elit tincidunt, vehicula massa sed, bibendum lectus. Proin volutpat rutrum felis, vitae molestie lorem blandit sed. Nunc eu massa odio. Vivamus hendrerit, arcu placerat vulputate facilisis, urna nisl molestie nisl, eget scelerisque arcu erat sed elit. Suspendisse potenti. Quisque bibendum dapibus cursus. Maecenas sed bibendum nunc. Ut massa nunc, pharetra sed mi vitae, vehicula rhoncus libero. Fusce vulputate magna sed mauris viverra, et aliquam lorem ultrices. Fusce erat metus, condimentum vulputate leo vel, dignissim lobortis nisl. Duis justo velit, malesuada eu pretium in, lobortis ac massa. Maecenas rhoncus sem sed augue euismod, in volutpat ante ornare.\r\n\r\nPellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Sed sit amet gravida lorem. Sed non odio a sapien sagittis imperdiet. Integer porta quam tortor, vitae accumsan quam porttitor sed. Nam consectetur augue sit amet leo semper elementum. Duis sollicitudin efficitur erat sed tempus. Curabitur quis nisi velit.', '2022-02-10 10:11:02', NULL, NULL),
(55, 20, '4ème post', 'quatre', 'Avant dernier post crée', 'VVVVVVVVVVVVVVVVV', '2022-02-17 10:58:18', NULL, '2022-02-22 18:32:18'),
(56, 20, 'Dernier post crée', '5ème', 'Ceci est le cinquième post du blog!', 'Nulla enim enim, mollis sed dignissim a, posuere lacinia lorem. Phasellus pretium tellus posuere laoreet fringilla. Vivamus et venenatis nisi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Donec quis diam mi. Integer diam lorem, vehicula ac facilisis sit amet, aliquet ac quam. Integer eleifend lectus ligula, sit amet posuere sapien ultrices et. Suspendisse eros mauris, iaculis sed condimentum quis, bibendum eu enim. Proin sit amet sodales justo. Aliquam nec accumsan felis. Praesent pharetra enim orci, quis rhoncus ligula dapibus a. Nullam sed consequat neque, nec egestas augue. Nullam imperdiet mauris sed sem malesuada, vitae suscipit nunc elementum. In pulvinar ex vel congue efficitur.\r\n\r\nMaecenas ac tortor non est posuere commodo vulputate non eros. Integer sit amet auctor lacus, sed vestibulum arcu. Praesent non accumsan quam. Quisque malesuada augue et dui auctor, id vehicula turpis vehicula. In varius euismod sapien. Nam aliquam vehicula arcu, ac volutpat dui dignissim at. In ut venenatis est. Suspendisse potenti. Ut et libero nec diam dictum auctor. Nullam tempor massa eget sollicitudin fermentum. Nunc lacinia leo quam, vel ultrices velit maximus at. Mauris eros turpis, elementum eget nunc ac, euismod consectetur urna. Duis ac dictum mi. Duis in auctor nibh. Fusce vulputate mattis nulla, at cursus orci pharetra eu.', '2022-02-22 18:31:43', NULL, '2022-02-22 18:32:40');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `publicName` varchar(50) NOT NULL,
  `emailAddress` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `userStatus` set('approved','to validate') NOT NULL DEFAULT 'to validate',
  `userRole` set('admin','moderator','member') NOT NULL,
  `creationDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`userId`, `lastName`, `firstName`, `publicName`, `emailAddress`, `password`, `userStatus`, `userRole`, `creationDate`) VALUES
(12, 'Thialon', 'François', 'Thialon', 'francois@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$V21zd25NUDUyNzZHLlV4', 'approved', 'member', '2022-01-18'),
(13, 'Pele', 'Tondu', 'Pele', 'peletondu@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$a3RLaTRleHF3UWtDSFNT', 'approved', 'member', '2022-01-18'),
(19, 'Benoit', 'Tourn', 'Benoit', 'benoitt@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$MVZOZ0F6YjNuRmhzTkZ2Uw$63nTdU5YJnt5dHItXzXMDlg/I42TH5L39O2sEm8yWck', 'approved', 'moderator', '2022-01-25'),
(20, 'admin', 'admin', 'admin', 'admin@admin.com', '$argon2i$v=19$m=65536,t=4,p=1$VnRKWWZJVjVOOWJKUXREYQ$AeOohyWNk3qljOMcW8xZgp/Zqh1d3Hwy+JJPGieiPNY', 'approved', 'admin', '2022-01-25'),
(21, 'Admin2', 'Admin2', 'Admin2', 'admin2@admin.fr', '$argon2i$v=19$m=65536,t=4,p=1$STBVclAyL1BrbFYvalVOSw$2tGYaVfzo22nrAVUi/Sf5J2PnCixW1xpMb4qViWpYoc', 'approved', 'moderator', '2022-01-28'),
(22, 'Chance', 'David', 'DaveC', 'davec@gmail.fr', '$argon2i$v=19$m=65536,t=4,p=1$STExb1ptWGU5YWlvTi44Wg$apdvkYbBKqZVvlosdPWs5OkZED0SjIJdIPi1Gj5zD1E', 'approved', 'member', '2022-02-21');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `FK_Comment_Post` (`post`),
  ADD KEY `author` (`author`) USING BTREE;

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`postId`),
  ADD KEY `author` (`author`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`author`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
