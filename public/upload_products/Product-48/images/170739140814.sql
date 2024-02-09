-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 29 jan. 2024 à 14:39
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
-- Base de données : `mawad_badis`
--

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `added_by` varchar(6) NOT NULL DEFAULT 'admin',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `photos` varchar(2000) DEFAULT NULL,
  `thumbnail_img` varchar(100) DEFAULT NULL,
  `video_provider` varchar(20) DEFAULT NULL,
  `video_link` varchar(100) DEFAULT NULL,
  `tags` varchar(500) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `unit_price` double(20,2) NOT NULL,
  `purchase_price` double(20,2) DEFAULT NULL,
  `variant_product` int(11) NOT NULL DEFAULT 0,
  `attributes` varchar(1000) NOT NULL DEFAULT '[]',
  `choice_options` mediumtext DEFAULT NULL,
  `colors` mediumtext DEFAULT NULL,
  `variations` text DEFAULT NULL,
  `todays_deal` int(11) NOT NULL DEFAULT 0,
  `published` int(11) NOT NULL DEFAULT 1,
  `approved` tinyint(1) NOT NULL DEFAULT 1,
  `stock_visibility_state` varchar(10) NOT NULL DEFAULT 'quantity',
  `cash_on_delivery` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = On, 0 = Off',
  `featured` int(11) NOT NULL DEFAULT 0,
  `seller_featured` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(20) DEFAULT NULL,
  `weight` double(8,2) NOT NULL DEFAULT 0.00,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `low_stock_quantity` int(11) DEFAULT NULL,
  `discount` double(20,2) DEFAULT NULL,
  `discount_type` varchar(10) DEFAULT NULL,
  `discount_start_date` int(11) DEFAULT NULL,
  `discount_end_date` int(11) DEFAULT NULL,
  `tax` double(20,2) DEFAULT NULL,
  `tax_type` varchar(10) DEFAULT NULL,
  `shipping_type` varchar(20) DEFAULT 'flat_rate',
  `shipping_cost` double(20,2) NOT NULL DEFAULT 0.00,
  `is_quantity_multiplied` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Mutiplied with shipping cost',
  `est_shipping_days` int(11) DEFAULT NULL,
  `num_of_sale` int(11) NOT NULL DEFAULT 0,
  `meta_title` mediumtext DEFAULT NULL,
  `meta_description` longtext DEFAULT NULL,
  `meta_img` varchar(255) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `slug` mediumtext NOT NULL,
  `refundable` int(11) NOT NULL DEFAULT 0,
  `earn_point` double(8,2) NOT NULL DEFAULT 0.00,
  `rating` double(8,2) NOT NULL DEFAULT 0.00,
  `barcode` varchar(255) DEFAULT NULL,
  `digital` int(11) NOT NULL DEFAULT 0,
  `auction_product` int(11) NOT NULL DEFAULT 0,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `external_link` varchar(500) DEFAULT NULL,
  `external_link_btn` varchar(255) DEFAULT 'Buy Now',
  `wholesale_product` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `country_code` varchar(191) NOT NULL,
  `manufacturer` varchar(191) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `added_by`, `user_id`, `category_id`, `brand_id`, `photos`, `thumbnail_img`, `video_provider`, `video_link`, `tags`, `description`, `unit_price`, `purchase_price`, `variant_product`, `attributes`, `choice_options`, `colors`, `variations`, `todays_deal`, `published`, `approved`, `stock_visibility_state`, `cash_on_delivery`, `featured`, `seller_featured`, `current_stock`, `unit`, `weight`, `min_qty`, `low_stock_quantity`, `discount`, `discount_type`, `discount_start_date`, `discount_end_date`, `tax`, `tax_type`, `shipping_type`, `shipping_cost`, `is_quantity_multiplied`, `est_shipping_days`, `num_of_sale`, `meta_title`, `meta_description`, `meta_img`, `pdf`, `slug`, `refundable`, `earn_point`, `rating`, `barcode`, `digital`, `auction_product`, `file_name`, `file_path`, `external_link`, `external_link_btn`, `wholesale_product`, `created_at`, `updated_at`, `country_code`, `manufacturer`, `parent_id`) VALUES
(1, 'https://www.google.com/', 'seller', 16, 1, 1, '28', '29', 'youtube', NULL, 'https://www.google.com/', '<p><span style=\"font-size: 12px;\">https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/</span><br></p>', 10.00, NULL, 1, '[]', '[]', '[\"#FAEBD7\",\"#00FFFF\",\"#7FFFD4\"]', NULL, 0, 1, 1, 'quantity', 1, 0, 0, 0, '1', 1.00, 1, 1, 0.00, 'amount', NULL, NULL, NULL, NULL, 'free', 0.00, 0, 3, 0, 'https://www.google.com/', 'https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/https://www.google.com/', '29', '31', 'httpswwwgooglecom', 1, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2023-12-04 19:33:33', '2024-01-02 04:59:20', '', '', 0),
(2, 'Super Mixure', 'seller', 3, 1, 2, '34,33', NULL, 'youtube', NULL, 'substance: super,crystal,filtered: true', NULL, 100.00, NULL, 1, '[\"3\"]', '[{\"attribute_id\":\"3\",\"values\":[\"Medium\"]}]', '[]', NULL, 0, 1, 1, 'quantity', 1, 0, 0, 50, 'KG', 0.00, 5, 1, 0.00, 'amount', NULL, NULL, NULL, NULL, 'free', 0.00, 0, NULL, 0, 'Super Mixure', '', NULL, '36', 'super-mixure', 1, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2023-12-09 02:08:58', '2024-01-02 04:59:24', '', '', 0),
(3, 'iPhone12', 'seller', 3, 7, 2, '39', NULL, 'youtube', NULL, '', '<p>بطيخ</p>', 2000.00, NULL, 1, '[\"3\"]', '[{\"attribute_id\":\"3\",\"values\":[\"Small\",\"Medium\"]}]', '[]', NULL, 0, 1, 1, 'quantity', 1, 0, 0, 10, 'Pc', 0.00, 1, 1, 0.00, 'amount', NULL, NULL, NULL, NULL, 'free', 0.00, 0, NULL, 4, 'iPhone12', 'بطيخ', NULL, NULL, 'iphone12', 1, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2023-12-09 17:39:52', '2024-01-02 04:59:45', '', '', 0),
(4, 'PlusProduct', 'seller', 19, 7, 3, '46,44,45,43,47,48,49,50,51,52', '43', 'youtube', NULL, '', NULL, 100.00, NULL, 1, '[\"3\",\"6\"]', '[{\"attribute_id\":\"3\",\"values\":[\"Small\",\"Medium\"]},{\"attribute_id\":\"6\",\"values\":[\"Copper\",\"Plastic\"]}]', '[\"#0000FF\",\"#FF0000\"]', NULL, 0, 1, 1, 'quantity', 1, 0, 0, 10, 'Pc', 12.00, 1, 100, 0.00, 'amount', NULL, NULL, NULL, NULL, 'free', 0.00, 0, 1, 6, 'PlusProduct', '', '45', NULL, 'plusproduct', 1, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2023-12-11 01:59:56', '2024-01-02 16:07:25', '', '', 0),
(5, '<alaaa', 'seller', 26, 1, 1, NULL, NULL, 'youtube', NULL, '', NULL, 5.00, NULL, 0, '[]', '[]', '[]', NULL, 0, 1, 1, 'quantity', 1, 0, 0, 0, '1 <alaa', 0.00, 1, 1, 1.00, 'amount', NULL, NULL, NULL, NULL, 'free', 0.00, 0, NULL, 0, '<ala', 'xss', NULL, NULL, 'ala-3', 1, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, NULL, 0, '2023-12-28 18:10:42', '2024-01-05 20:07:35', '', '', 0),
(8, 'Demo Product 22', 'seller', 26, 2, 1, '76,77,78', '75', 'youtube', NULL, 'Best Selling,demo Product,Baby product', '<p>Demo Product Description<a href=\"&quot;><script>alert(document.cookie)</script>&quot;>\">\"&gt;<script>alert(document.cookie)</script>\"&gt;</a></p>', 55.00, NULL, 0, '[]', '[]', '[]', '[]', 0, 1, 1, 'quantity', 0, 0, 0, 9, 'pc', 0.00, 1, NULL, 1.00, 'amount', NULL, NULL, NULL, NULL, 'flat_rate', 0.00, 0, 5, 1, 'Demo Product 22', 'aaa', '75', NULL, 'demo-product-22-s301x', 0, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, 'Buy Now', 0, '2024-01-02 05:06:30', '2024-01-05 19:54:20', '', '', 0),
(9, 'test  unique name for color', 'admin', 157, 0, 1, NULL, NULL, 'youtube', NULL, 'bdsd', '<p>sdsqdqssqdde\'rrtrtrtrt</p>', 0.00, NULL, 0, '[]', NULL, '[]', NULL, 0, 1, 1, 'quantity', 1, 0, 0, 0, '444', 0.00, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'free', 0.00, 0, NULL, 0, 'farefe', 'fdsfdqsfdqsfdq', NULL, NULL, 'test-unique-name-for-color', 1, 0.00, 0.00, NULL, 0, 0, NULL, NULL, NULL, 'Buy Now', 0, '2024-01-16 12:55:02', '2024-01-16 12:55:02', 'ae', 'appel', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `tags` (`tags`(255)),
  ADD KEY `unit_price` (`unit_price`),
  ADD KEY `created_at` (`created_at`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
