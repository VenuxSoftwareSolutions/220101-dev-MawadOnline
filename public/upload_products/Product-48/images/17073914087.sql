-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 jan. 2024 à 15:29
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mawadonline`
--

-- --------------------------------------------------------

--
-- Structure de la table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_products` bigint(20) UNSIGNED NOT NULL,
  `id_attribute` bigint(20) UNSIGNED NOT NULL,
  `id_units` bigint(20) UNSIGNED DEFAULT NULL,
  `id_values` bigint(20) UNSIGNED DEFAULT NULL,
  `id_colors` bigint(20) UNSIGNED DEFAULT NULL,
  `value` varchar(191) NOT NULL,
  `color_name_en` varchar(191) NOT NULL,
  `color_name_ar` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_id_products_foreign` (`id_products`),
  ADD KEY `product_attribute_values_id_attribute_foreign` (`id_attribute`),
  ADD KEY `product_attribute_values_id_units_foreign` (`id_units`),
  ADD KEY `product_attribute_values_id_values_foreign` (`id_values`),
  ADD KEY `product_attribute_values_id_colors_foreign` (`id_colors`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_id_attribute_foreign` FOREIGN KEY (`id_attribute`) REFERENCES `attributes` (`id`),
  ADD CONSTRAINT `product_attribute_values_id_colors_foreign` FOREIGN KEY (`id_colors`) REFERENCES `colors` (`id`),
  ADD CONSTRAINT `product_attribute_values_id_products_foreign` FOREIGN KEY (`id_products`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_attribute_values_id_units_foreign` FOREIGN KEY (`id_units`) REFERENCES `unites` (`id`),
  ADD CONSTRAINT `product_attribute_values_id_values_foreign` FOREIGN KEY (`id_values`) REFERENCES `attribute_values` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
