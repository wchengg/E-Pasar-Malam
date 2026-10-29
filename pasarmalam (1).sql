-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306:3306
-- Generation Time: Sep 24, 2024 at 08:42 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pasarmalam`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(200) NOT NULL,
  `fullname` varchar(40) NOT NULL,
  `contact` text NOT NULL,
  `email` varchar(40) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `agent` text NOT NULL,
  `date_created` timestamp(6) NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `fullname`, `contact`, `email`, `username`, `password`, `agent`, `date_created`) VALUES
(1, 'YAP WAN CHENG', '01121318867', 'yapwancheng@gmail.com', 'yapwancheng', '12', '', '2024-08-12 04:33:07.665844'),
(2, 'Chew Testing', '01121318867', 'testing@gmail.com', 'testing', '0958921', '1', '2024-08-21 01:15:58.000000'),
(3, 'WanQi', '01121318867', 'wanqi@gmail.com', 'wanqi', '0958921', '2', '2024-08-22 16:04:43.879563');

-- --------------------------------------------------------

--
-- Table structure for table `deliveryman`
--

CREATE TABLE `deliveryman` (
  `deliveryman_id` int(100) NOT NULL,
  `fullname` text NOT NULL,
  `contact` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` text NOT NULL,
  `password` varchar(50) NOT NULL,
  `agent` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `deliveryman`
--

INSERT INTO `deliveryman` (`deliveryman_id`, `fullname`, `contact`, `email`, `username`, `password`, `agent`, `date_created`) VALUES
(1, 'Delivery 1', '01121318867', 'example@gmail.com', 'example1', '0958921', 1, '2024-09-04 04:37:29'),
(2, 'Example 2', '01121318867', 'example2@gmail.com', 'example2', '0958921', 2, '2024-09-05 15:33:11');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `itempic` text NOT NULL,
  `itemname` text NOT NULL,
  `itemprice` text NOT NULL,
  `itemcategory` text NOT NULL,
  `itemdescription` text NOT NULL,
  `sold` int(11) NOT NULL,
  `agent` text NOT NULL,
  `created time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `shop_id`, `itempic`, `itemname`, `itemprice`, `itemcategory`, `itemdescription`, `sold`, `agent`, `created time`) VALUES
(1, 2, 'https://i.imgur.com/KHkRWLC.jpeg', 'Original Chili Sauce - 5pcs', '9.5', 'Food & Beverages', 'Original Tofu', 413, '1', '2024-08-14 07:17:49'),
(2, 2, 'https://i.imgur.com/KHkRWLC.jpeg', 'Vegetarian Chili Sauce - 4pcs', '9.50', 'Food & Beverages', 'Vegetarian Tofu', 69, '1', '2024-08-19 06:02:52'),
(3, 2, 'https://i.imgur.com/XxCo07Q.jpeg', 'Thai Style - 4pcs', '10.50', 'Food & Beverages', 'Thai Tofu', 40, '1', '2024-08-19 06:03:48'),
(4, 2, 'https://i.imgur.com/HOoIt1e.jpeg', 'Italian Cheese - 4pcs', '10.50', 'Food & Beverages', 'Italian Tofu', 15, '1', '2024-08-19 06:04:53'),
(5, 1, 'https://i.imgur.com/i1eLtFG.jpeg', 'Crispy - 5pcs', '17', 'Food & Beverages', 'Crispy', 33, '1', '2024-08-19 06:06:28'),
(6, 1, 'https://i.imgur.com/FybHZLT.jpeg', 'Peanut - 5pcs', '17', 'Food & Beverages', 'Peanut', 48, '1', '2024-08-19 06:07:05'),
(7, 1, 'https://i.imgur.com/Xm1rEh6.jpeg', 'Peanut Corn - 5pcs', '18.00', 'Food & Beverages', 'Peanut', 200, '1', '2024-08-20 20:00:11'),
(8, 3, 'https://imgtr.ee/images/2024/09/16/ae6d3c55dc0932ab04914fbbe594a13f.png', 'Honey Jackfruit 400g', '8.90', 'Fresh Items', '', 33, '1', '2024-08-20 20:27:35'),
(9, 3, 'https://i.imgur.com/rKjHg2p.png', 'Black Seedless Grape 500g', '17.50', 'Fresh Items', 'Grape', 53, '1', '2024-08-20 20:03:18'),
(10, 3, 'https://i.imgur.com/et5DRlC.jpeg', 'Waterapple 1kg', '26', 'Fresh Items', 'Waterapple', 45, '1', '2024-08-19 06:20:00'),
(11, 3, 'https://imgtr.ee/images/2024/09/16/a89fef8a17eb0ae306db7acd952ee21b.png', 'Nova Tangerine 800g', '15.90', 'Fresh Items', 'Tangerine', 21, '1', '2024-08-19 06:21:49'),
(12, 3, 'https://imgtr.ee/images/2024/09/16/563cec9e144d0834334ae74e90ce87e1.jpeg', 'Cherries 250g', '35.90', 'Fresh Items', 'Cherry', 13, '1', '2024-08-19 06:24:08'),
(13, 3, 'https://imgtr.ee/images/2024/09/16/ffed049561fd785e93e7bf0bfdec4f7d.jpeg', 'Pineapple 1pcs', '12.50', 'Fresh Items', 'Pineapple', 32, '1', '2024-08-19 06:26:22'),
(14, 3, 'https://imgtr.ee/images/2024/09/16/a690f5e1afa2818e3d230d8444aa207d.jpeg', 'Passion fruit 500g', '7.90', 'Fresh Items', 'Passion', 9, '1', '2024-08-19 06:28:21'),
(15, 3, 'https://imgtr.ee/images/2024/09/16/ff3356cb7ee672107e171272f72a3a94.jpeg', 'Banana Berangan 1kg', '8.50', 'Fresh Items', 'Banana', 11, '1', '2024-08-19 06:31:39'),
(16, 3, 'https://imgtr.ee/images/2024/09/16/b1890cba0efc806df1fa4c11e180588a.jpeg', 'Blueberries 125g', '18', 'Fresh Items', 'Blueberry', 31, '1', '2024-08-19 06:33:38'),
(18, 4, 'https://imgtr.ee/images/2024/09/16/5eca9772ba45c141a46c91c4761152b3.jpeg', 'Peace Lily', '22.90', 'Flowers & Plants', 'Lily', 7, '1', '2024-08-19 06:52:36'),
(19, 4, 'https://imgtr.ee/images/2024/09/16/743cc0c4deb44e334262f1858c38df70.jpeg', 'Terrarium 1', '108', 'Flowers & Plants', 'Terra', 9, '1', '2024-08-19 06:55:59'),
(20, 4, 'https://imgtr.ee/images/2024/09/16/82e80f2ace3fddbabeeec474865648dc.jpeg', 'Moss Terrarium ', '129', 'Flowers & Plants', 'Moss', 5, '1', '2024-08-19 06:58:21'),
(21, 4, 'https://imgtr.ee/images/2024/09/16/dc94e8694dc0228b2f1ff9a79d33d6a8.jpeg', 'Calathea Medallion', '68.90', 'Flowers & Plants', 'Calathea', 19, '1', '2024-08-19 07:00:00'),
(22, 4, 'https://imgtr.ee/images/2024/09/16/76a05693c85f5317cbca7a0dbd7586c5.jpeg', 'Ficus Lyrata', '28.90', 'Flowers & Plants', 'Ficus', 30, '1', '2024-08-19 07:01:45'),
(23, 4, 'https://imgtr.ee/images/2024/09/16/0e9d1d879fa47a3cbec7fd79f6b4c8c0.jpeg', 'Peat Moss Soil 500g', '15.50', 'Flowers & Plants', 'Soil', 13, '1', '2024-08-19 07:03:31'),
(24, 4, 'https://imgtr.ee/images/2024/09/16/4246325d7b1c6871202ab6ab058ff9c6.jpeg', 'Pink Rose', '20', 'Flowers & Plants', 'Rose', 24, '1', '2024-08-19 07:05:59'),
(26, 5, 'https://imgtr.ee/images/2024/09/16/0844427d2f3d9d717af1d9e8adacc292.jpeg', 'Original bagel', '5.30', 'Bread & Pastries', 'Ori', 5, '1', '2024-08-19 07:16:13'),
(27, 5, 'https://imgtr.ee/images/2024/09/16/5132559eafff833bf1773ade48236a58.jpeg', 'Parmesan cheese bagel', '5.90', 'Bread & Pastries', 'Parmesan', 15, '1', '2024-08-19 07:17:22'),
(28, 5, 'https://imgtr.ee/images/2024/09/16/6a778db9a5de0f04c56c48347ac19703.jpeg', 'Cinnamon raisin bagel', '5.90', 'Bread & Pastries', 'Raisin', 11, '1', '2024-08-19 07:18:55'),
(29, 5, 'https://imgtr.ee/images/2024/09/16/554d442611c0354aff765453ab0c676d.jpeg', 'Cocoa bagel', '6.00', 'Bread & Pastries', 'Cocoa', 24, '1', '2024-08-19 07:20:06'),
(30, 5, 'https://imgtr.ee/images/2024/09/16/30e5c9ec8604b6580c9b56d97d257e4f.jpeg', 'Wholemeal bagel', '6.00', 'Bread & Pastries', 'Wholemeal', 3, '1', '2024-08-19 07:21:21'),
(31, 5, 'https://imgtr.ee/images/2024/09/16/5d8b917a5d55f7421523ad305914f523.jpeg', 'Cranberries bagel', '6.00', 'Bread & Pastries', 'Cranberries', 21, '1', '2024-08-19 07:22:56');

-- --------------------------------------------------------

--
-- Table structure for table `merchant`
--

CREATE TABLE `merchant` (
  `merchant_id` int(200) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `contact` text NOT NULL,
  `email` varchar(40) NOT NULL,
  `businesslicenseno` text NOT NULL,
  `shopname` varchar(40) NOT NULL,
  `shopcategory1` text NOT NULL,
  `shopcategory2` text NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `merchant`
--

INSERT INTO `merchant` (`merchant_id`, `fullname`, `username`, `password`, `contact`, `email`, `businesslicenseno`, `shopname`, `shopcategory1`, `shopcategory2`, `date_created`) VALUES
(1, 'Soon ', 'soon', '0958921', '01121318867', 'soon@gmail.com', '2024-01-000000', 'Soon Apam Balik', 'Food & Beverages', '', '2024-08-19 05:24:55.841392'),
(2, 'Chew', 'chew', '0958921', '01121318867', 'chew@gmail.com', '2022-01-036900', 'Chew Chew Chow Tofu', 'Food & Beverages', '', '2024-08-19 05:29:47.683803'),
(3, 'Fruit', 'fruit', '0958921', '01121318867', 'fruit@gmail.com', '2023-01-100000', 'Fruit.co', 'Fresh Items', '', '2024-08-19 06:46:08.337737'),
(4, 'Lee', 'lee', '0958921', '01121318867', 'lee@gmail.com', '2022-01-136999', 'Lee Florist', 'Flowers & Plants', '', '2024-08-19 06:48:28.492130'),
(5, 'Bagel', 'bagel', '0958921', '01121318867', 'bagel@gmail.com', '2023-01-999999', 'The Bagels', 'Bread & Pastries', '', '2024-08-19 07:11:56.600795');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pickup_time` time NOT NULL,
  `payment_method` enum('Credit-Card','COD') NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Waiting for Deliveryman','Order Ready','Ready to Pickup','Completed','Decline','Order Accepted','In Progress') NOT NULL DEFAULT 'Waiting for Deliveryman',
  `deliveryman_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `pickup_time`, `payment_method`, `name`, `contact_number`, `order_date`, `total`, `status`, `deliveryman_id`) VALUES
(8, 1, '12:10:00', 'COD', 'Yap Wan Cheng', '01121318867', '2024-09-12 10:05:51', '27.50', 'Completed', 1),
(9, 1, '23:30:00', 'COD', 'Yap Wan Cheng', '01121318867', '2024-09-16 09:53:23', '6.00', 'Completed', 1),
(10, 1, '17:45:00', 'COD', 'Yap Wan Cheng', '01121318867', '2024-09-17 08:43:58', '14.20', 'Waiting for Deliveryman', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `item_id` int(11) NOT NULL,
  `remarks` varchar(20) NOT NULL,
  `status` enum('In Progress','Order Ready','Decline') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `title`, `price`, `quantity`, `image`, `item_id`, `remarks`, `status`) VALUES
(17, 8, 'Peanut Corn - 5pcs', '18.00', 1, 'https://i.imgur.com/Xm1rEh6.jpeg', 7, 'More corn', 'Order Ready'),
(18, 8, 'Original Chili Sauce - 4pcs', '9.50', 1, 'https://i.imgur.com/KHkRWLC.jpeg', 1, '', 'Order Ready'),
(19, 9, 'Wholemeal bagel', '6.00', 1, 'https://imgtr.ee/images/2024/09/16/30e5c9ec8604b6580c9b56d97d257e4f.jpeg', 30, '', 'Order Ready'),
(20, 10, 'Original bagel', '5.30', 1, 'https://imgtr.ee/images/2024/09/16/0844427d2f3d9d717af1d9e8adacc292.jpeg', 26, '', 'Order Ready'),
(21, 10, 'Honey Jackfruit 400g', '8.90', 1, 'https://imgtr.ee/images/2024/09/16/ae6d3c55dc0932ab04914fbbe594a13f.png', 8, '', 'Decline');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `shop_id` int(11) NOT NULL,
  `shopname` text NOT NULL,
  `shopcategory` text NOT NULL,
  `shopdescription` text DEFAULT NULL,
  `shoprating` text DEFAULT NULL,
  `shoppic` text NOT NULL,
  `merchant_id` int(11) NOT NULL,
  `shoptime` int(11) DEFAULT NULL,
  `created time` timestamp NOT NULL DEFAULT current_timestamp(),
  `agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`shop_id`, `shopname`, `shopcategory`, `shopdescription`, `shoprating`, `shoppic`, `merchant_id`, `shoptime`, `created time`, `agent`) VALUES
(1, 'Soon Apam ', 'Food & Beverages', 'Best Apam Balik in Town. Must Try!!!!!!!!!!!!!!', '4.7', 'https://imgtr.ee/images/2024/09/16/d9923437fc7deef4180304ce95b3b836.jpeg', 1, 8, '2024-08-19 05:26:23', '1'),
(2, 'Chew Chew Chow Tofu', 'Food & Beverages', 'Chow Tofu', '4.6', 'https://imgtr.ee/images/2024/09/16/68f081b2c986ad06c5b8ed31cb46253f.png', 2, 12, '2024-08-19 05:31:01', '1'),
(3, 'Fruit.co', 'Fresh Items', 'Fruit Shop', '4.0', 'https://imgtr.ee/images/2024/09/16/9ea1afd751ab38d9c55491e4fe92bc5e.png', 3, 10, '2024-08-19 05:50:26', '1'),
(4, 'Lee Florist', 'Flowers & Plants', 'Florist', '3.9', 'https://imgtr.ee/images/2024/09/16/2cb823159c258f59b83103f710e73f59.jpeg', 4, 15, '2024-08-19 06:50:08', '1'),
(5, 'The Bagels', 'Bread & Pastries', 'Bagels', '4.8', 'https://imgtr.ee/images/2024/09/16/39ce183c00114c8f3baeb6f2f1e825bf.jpeg', 5, 7, '2024-08-19 07:14:35', '1');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(200) NOT NULL,
  `fullname` varchar(30) NOT NULL,
  `contact` text NOT NULL,
  `email` varchar(40) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `date_created` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fullname`, `contact`, `email`, `username`, `password`, `dob`, `date_created`) VALUES
(1, 'Yap Wan Cheng', '01121318867', 'yapwancheng@gmail.com', 'yapwancheng', '0958921', '2024-08-12', '2024-08-11 16:51:32.139422'),
(2, 'Chew Jenny', '0162400551', 'jenny@gmail.com', 'jenny', '0958921', '2004-02-18', '2024-09-03 09:07:51.519612');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `deliveryman`
--
ALTER TABLE `deliveryman`
  ADD PRIMARY KEY (`deliveryman_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `merchant`
--
ALTER TABLE `merchant`
  ADD PRIMARY KEY (`merchant_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_items_ibfk_1` (`order_id`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`shop_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deliveryman`
--
ALTER TABLE `deliveryman`
  MODIFY `deliveryman_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `merchant`
--
ALTER TABLE `merchant`
  MODIFY `merchant_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `shop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
