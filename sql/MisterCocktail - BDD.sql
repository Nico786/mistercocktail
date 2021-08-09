-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 11 fév. 2021 à 08:59
-- Version du serveur :  5.7.32
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données : `MisterCocktail`
--

-- --------------------------------------------------------

--
-- Structure de la table `Cocktail`
--

CREATE TABLE `Cocktail` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `urlPhoto` varchar(255) NOT NULL,
  `dateConception` date NOT NULL,
  `prixMoyen` float NOT NULL,
  `idFamille` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Cocktail`
--

INSERT INTO `Cocktail` (`id`, `nom`, `description`, `urlPhoto`, `dateConception`, `prixMoyen`, `idFamille`) VALUES
(1, 'Aperol Spritz', 'Le Spritz est un cocktail datant du siècle dernier. Il aurait été inventé par des soldats autrichiens qui trouvaient le vin italien trop chargé en alcool.<br><br>L’auriez-vous deviné ?', 'aperol-spritz.jpg', '1938-01-01', 9.75, 6),
(2, 'Mojito', 'La création du Mojito remonte au XVIe siècle lorsque Francis Draque, le célèbre corsaire anglais, avait pour habitude de célébrer ses pillages en sirotant à La Havane, du tafia (l’ancêtre du rhum), aromatisé de quelques feuilles de menthe et de citron.', 'mojito.jpg', '1583-01-01', 10, 2),
(3, 'Piña Colada', 'Le cocktail Piña Colada puise ses origines à Puerto Rico où il a été inventé par un barman de l’hôtel Caribe Hilton en 1954. Décrétée 30 ans plus tard boisson nationale, cet élixir doux et fruité concentre dans le verre toutes les saveurs ensoleillées des Caraïbes.', 'pina-colada.jpg', '1954-01-01', 8.85, 2),
(6, 'Punch', 'Historiquement, le punch date du XVIe siècle. Il aurait été créé par des marins britanniques en mélangeant du Tafia (un genre de rhum brut) qui était embarqué sur les navires, avec d’autres ingrédients.', 'punch.jpg', '1532-01-01', 9, 2),
(7, 'Punch Exotique', 'Historiquement, le punch date du XVIe siècle. Il aurait été créé par des marins britanniques en mélangeant du Tafia (un genre de rhum brut) qui était embarqué sur les navires, avec d’autres ingrédients.', 'punch-exotique.jpg', '1532-01-01', 10.55, 2),
(9, 'Caipirinha', 'La caïpirinha est un cocktail brésilien préparé à base de cachaça, de sucre de canne et de citron vert. Créé par les paysans dont il tirerait l\'origine de son nom, ce cocktail est très populaire et largement consommé dans les restaurants, bars et boîtes de nuit.', 'caipirinha.webp', '1918-01-01', 10, 7),
(10, 'Blue Lagoon ', 'Le Blue Lagoon est un cocktail à base de vodka, de curaçao bleu et de jus de citron. Il est aussi appelé le « lagon bleu » par sa traduction. Il fut créé par Andy MacElhone au Harry\'s New York Bar à Paris, en 1960.', 'blue_lagoon.webp', '1960-01-01', 11, 1),
(11, 'Vodka martini', 'Ce cocktail est réalisé en combinant vodka, vermouth sec, et glace, dans un shaker, ou dans un verre mélangeur, avec deux dosages répandus : quatre doses, ou six doses de vodka, pour une de vermouth.', 'vodka_martini.webp', '1933-01-01', 14, 1),
(12, 'Moscow Mule', 'La Mule de Moscou (en anglais : Moscow mule) est un cocktail à base de vodka, de bière de gingembre épicée et de jus de citron vert, accompagné d\'une rondelle de citron.D\'après un article du New York Herald Tribune datant de 1948, le cocktail serait né à Manhattan à New-York.', '6022b396ec21dmosco.jpeg', '1948-01-01', 7.5, 1),
(13, 'Gin Tonic', 'Le cocktail est soi-disant introduit par la Compagnie anglaise des Indes orientales en Inde au xviiie siècle.\r\nEn réalité, c\'est l\'eau tonique, initialement découverte au milieu du XVIIIe siècle, qui, contenant de la quinine, était utilisée pour combattre la malaria dans les régions tropicales .', '6022b5a6611edgintonic.jpeg', '1890-01-01', 8.4, 8),
(14, 'Daiquiri', 'Le cocktail aurait été inventé par un ingénieur américain, Jennings Cox, qui était à Cuba au moment de la guerre hispano-américaine. Il est aussi possible que William A. Chanler, un élu américain qui acquit les mines de fer de Santiago en 1902, importât la recette du cocktail dans les boîtes de nuit new-yorkaises la même année3', '6022b6e1e8819daiquiri.jpeg', '1902-01-01', 9, 2);

-- --------------------------------------------------------

--
-- Structure de la table `Famille`
--

CREATE TABLE `Famille` (
  `id` int(11) NOT NULL,
  `nomFamille` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `Famille`
--

INSERT INTO `Famille` (`id`, `nomFamille`) VALUES
(1, 'Vodka'),
(2, 'Rhum'),
(3, 'Whisky'),
(4, 'Champagne'),
(5, 'Sans alcool'),
(6, 'Aperol'),
(7, 'Cachaça'),
(8, 'Gin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Cocktail`
--
ALTER TABLE `Cocktail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idFamille` (`idFamille`);

--
-- Index pour la table `Famille`
--
ALTER TABLE `Famille`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Cocktail`
--
ALTER TABLE `Cocktail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `Famille`
--
ALTER TABLE `Famille`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Cocktail`
--
ALTER TABLE `Cocktail`
  ADD CONSTRAINT `cocktail_ibfk_1` FOREIGN KEY (`idFamille`) REFERENCES `Famille` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
