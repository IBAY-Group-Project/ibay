-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 06, 2026 at 09:41 AM
-- Server version: 5.5.68-MariaDB
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `group01`
--

-- --------------------------------------------------------

--
-- Table structure for table `iBayBasket`
--

CREATE TABLE `iBayBasket` (
  `basketId` int(11) NOT NULL,
  `userId` varchar(20) NOT NULL,
  `itemId` int(11) NOT NULL,
  `quantity` int(11) DEFAULT '1',
  `addedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iBayBasket`
--

INSERT INTO `iBayBasket` (`basketId`, `userId`, `itemId`, `quantity`, `addedAt`) VALUES
(31, '22', 2, 1, '2026-05-06 09:16:40'),
(32, '11', 4, 1, '2026-05-06 09:17:04');

-- --------------------------------------------------------

--
-- Table structure for table `iBayBids`
--

CREATE TABLE `iBayBids` (
  `bidId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `userId` varchar(11) NOT NULL,
  `bidAmount` decimal(11,0) NOT NULL,
  `bidTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `iBayImages`
--

CREATE TABLE `iBayImages` (
  `imageId` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `mimeType` varchar(40) NOT NULL,
  `imageSize` int(11) NOT NULL,
  `itemId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iBayImages`
--

INSERT INTO `iBayImages` (`imageId`, `image`, `mimeType`, `imageSize`, `itemId`) VALUES
(4, 'iphoneback.jpeg', 'image/jpeg', 50000, 1),
(5, 'iphoneside.jpeg', 'image/jpeg', 50000, 1),
(6, 'iphonefront.jpeg', 'image/jpeg', 50000, 1),
(7, '69f508b99ee2e_7f701896c3.jpeg', 'image/avif', 17820, 21);

-- --------------------------------------------------------

--
-- Table structure for table `iBayItems`
--

CREATE TABLE `iBayItems` (
  `itemId` int(11) NOT NULL,
  `userId` varchar(20) NOT NULL,
  `title` varchar(40) NOT NULL,
  `category` varchar(40) NOT NULL,
  `description` longtext NOT NULL,
  `price` decimal(6,0) NOT NULL,
  `postage` varchar(40) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `condition` varchar(40) NOT NULL,
  `sold` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iBayItems`
--

INSERT INTO `iBayItems` (`itemId`, `userId`, `title`, `category`, `description`, `price`, `postage`, `start`, `condition`, `sold`) VALUES
(1, '1', 'iPhone 11 64GB Black', 'Technology', 'Great condition iPhone 11, minor scratches on back. Battery health 87%. Comes with original charger.', 180, 'Free postage', '2026-05-04 13:25:47', 'Used - Good', 0),
(2, '3', 'Vintage Casio F-91W Watch', 'Technology', 'Classic digital watch from the 90s. Still keeping perfect time. Original strap slightly worn but functional.', 18, '£1.99', '2026-05-01 19:52:55', 'Used - Good', 0),
(3, '2', 'Lego Star Wars Millennium Falcon 2008', 'Collectables', 'Complete set, all pieces present. Box is tatty but instructions included. Some sticker wear on panels.', 95, '£4.99', '2026-05-01 19:52:55', 'Used - Good', 0),
(4, '5', 'Bread Maker Machine Panasonic SD-2501', 'Home', 'Used maybe 10 times. Makes a perfect loaf every time. All paddles and measuring cups included.', 35, '£4.99', '2026-05-01 19:52:55', 'Used - Good', 0),
(5, '4', 'Pokémon Ruby GBA Cartridge Authentic', 'Collectables', 'Genuine cartridge, label has a small tear. Save battery may need replacing. Tested and works fine.', 28, '£1.99', '2026-05-01 19:52:55', 'Used - Acceptable', 0),
(6, '6', 'Barbour Wax Jacket Navy Medium', 'Clothing', 'Genuine Barbour, worn two winters. Some wax wear on elbows but nothing major. Recently re-waxed.', 72, '£2.99', '2026-05-01 19:52:55', 'Used - Good', 0),
(7, '7', 'Polaroid Now Camera Pink', 'Technology', 'Used twice, comes with half a pack of film remaining. Strap and case included. No scratches.', 55, '£2.99', '2026-05-01 19:52:55', 'Like New', 0),
(8, '8', 'Bag of Mixed Lego Bricks 2kg', 'Collectables', 'Huge bag of genuine Lego, all sorted by colour. No Duplo or knock offs. Great for MOC builders.', 22, '£4.99', '2026-05-01 19:52:55', 'Used - Good', 0),
(9, '9', 'Antique Copper Watering Can', 'Gardening', 'Solid copper, no leaks. Has developed a natural patina over the years. Makes a great display piece too.', 14, '£4.99', '2026-05-01 19:52:55', 'Used - Acceptable', 0),
(10, '10', 'Signed Manchester United Programme 1999', 'Sports', 'Match programme from the 1999 Champions League final. Signed on the cover, unverified but looks genuine.', 45, '£1.99', '2026-05-01 19:52:55', 'Used - Good', 0),
(11, '2', 'Wooden Chess Set Handcarved', 'Collectables', 'Beautiful handcarved wooden chess set. All 32 pieces present. Board has minor scratches from use.', 32, '£2.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(12, '4', 'North Face Puffer Jacket Black XL', 'Clothing', 'Genuine North Face, worn one winter. No rips or stains. Zip works perfectly. Warm and lightweight.', 55, '£2.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(13, '6', 'Telescope Celestron 70mm Refractor', 'Technology', 'Great starter telescope. Used a handful of times. Tripod included, all lenses present and clean.', 48, '£4.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(14, '3', 'Vintage Penguin Classic Book Collection', 'Books', 'Bundle of 12 vintage Penguin classic paperbacks. Various authors. Some yellowing but all readable.', 18, '£2.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(15, '7', 'Skateboard Complete Setup', 'Sports', 'Full setup ready to ride. Deck has some graphic wear. Trucks and wheels in good working condition.', 35, '£4.99', '2026-05-01 19:58:22', 'Used - Acceptable', 0),
(16, '5', 'Air Fryer Ninja 4.7L', 'Home', 'Used regularly for 6 months. Thoroughly cleaned before listing. All accessories included. Works perfectly.', 58, '£4.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(17, '8', 'Acoustic Guitar Yamaha F310', 'Collectables', 'Great beginner guitar. Plays well, recently restrung. Minor buckle rash on back. Comes with carry bag.', 75, '£4.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(18, '9', 'Vintage Tin Toy Robot 1960s', 'Collectables', 'Original 1960s Japanese tin toy robot. Battery compartment has some rust but display piece only. Very rare.', 120, '£2.99', '2026-05-04 19:21:53', 'Used - Acceptable', 1),
(19, '10', 'Running Shoes Brooks Ghost 14 Size 8', 'Sports', 'Worn for around 100 miles. Plenty of life left. No odour, cleaned before listing. Great cushioning.', 42, '£2.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(20, '1', 'Instant Pot Duo 7-in-1 6L', 'Home', 'Used weekly for a year. All seals in good condition. Every function works perfectly. Recipe book included.', 45, '£4.99', '2026-05-01 19:58:22', 'Used - Good', 0),
(21, '1', 'Manchester City 25/26 football shirt ', 'Technology', 'New home shirt. Unworn with label on ', 30, 'Free postage', '2026-05-05 14:58:16', '', 1),
(22, '11', 'test', 'Technology', '', 0, '', '2026-05-05 15:06:50', 'New', 1),
(23, '11', 'test2', 'Technology', '1', 29, 'Free postage', '2026-05-05 15:07:05', 'New', 1),
(24, '11', 'test3', 'Collectables', '1', 22, 'Free postage', '2026-05-05 13:11:08', 'New', 0);

-- --------------------------------------------------------

--
-- Table structure for table `iBayMembers`
--

CREATE TABLE `iBayMembers` (
  `userId` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `email` varchar(40) NOT NULL,
  `address` mediumtext NOT NULL,
  `postcode` varchar(20) NOT NULL,
  `rating` int(11) NOT NULL,
  `surname` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `iBayBalance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iBayMembers`
--

INSERT INTO `iBayMembers` (`userId`, `username`, `password`, `firstname`, `email`, `address`, `postcode`, `rating`, `surname`, `phone_number`, `iBayBalance`, `is_admin`) VALUES
(1, '', '$2y$10$q3UXh8bB1Xm09sVamE.X.e0xa4qfbcqJS6rmLF4YwlfiZGKdRw292', 'testuser', 'testemail@email.com', 'tes address', 'LE11 TEA', 4, 'test', '', 162.00, 0),
(2, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'James', 'james.smith@email.com', '', '', 0, 'Smith', '', 0.00, 0),
(3, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Sarah', 'sarah.johnson@email.com', '', '', 0, 'Johnson', '', 212.31, 0),
(4, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Mike', 'mike.williams@email.com', '', '', 0, 'Williams', '', 0.00, 0),
(5, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Emma', 'emma.brown@email.com', '', '', 0, 'Brown', '', 31.50, 0),
(6, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Oliver', 'oliver.jones@email.com', '', '', 0, 'Jones', '', 67.79, 0),
(7, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Chloe', 'chloe.taylor@email.com', '', '', 0, 'Taylor', '', 0.00, 0),
(8, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Harry', 'harry.davies@email.com', '', '', 0, 'Davies', '', 0.00, 0),
(9, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Lily', 'lily.wilson@email.com', '', '', 1, 'Wilson', '', 0.00, 0),
(10, '', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uSsRVNlWu', 'Jack', 'jack.moore@email.com', '', '', 0, 'Moore', '', 75.60, 0),
(11, 'ma25', '$2y$10$wVuqTBB/dASEwXEtiqhnQuYfVqh4Ndp8O1aP8Fr3Un1YkN46hXJru', 'Mohamed', 'mabshir2525@gmail.com', '1', 'LE11', 5, 'Abshir', '0', -646.94, 0),
(18, '', '$2y$10$wpDNhES1jWVZ0roOSF52p.tDkszrJf6rO43CU.LHJb27ynU1ATvlW', 'Jeoff ', 'JeoffMerry899@gmail.com', '', '', 0, 'Merry', '', 0.00, 0),
(19, '1', '$2y$10$TnNgrvpRmmMg1joaifz/2.G.vvS85B0UldLaY1J5T/eSAdwmecwGC', 'test', 'test@testuser.com', '', '', 0, 'user', '', 0.00, 0),
(21, 'admin', '$2y$10$as9lQyRydlfV1ghS5LX1JO.ki1iUu79kZXdbU2hs7FvtVAgEuMjaS', 'Ibay', 'admin1', '', '', 0, 'Admin', '', 65.60, 1),
(22, 'mabshir2535', '', 'Mohamed', 'mabshir2535@gmail.com', '', '', 0, '', '', 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `iBayOrders`
--

CREATE TABLE `iBayOrders` (
  `orderId` int(11) NOT NULL,
  `buyerId` int(11) NOT NULL,
  `orderTotal` decimal(10,2) NOT NULL,
  `totalPostage` decimal(10,2) NOT NULL,
  `paymentMethod` varchar(50) DEFAULT NULL,
  `orderDate` datetime NOT NULL,
  `shippingAddress` mediumtext DEFAULT NULL,
  `postcode` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `deliveryDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iBayOrders`
--

INSERT INTO `iBayOrders` (`orderId`, `buyerId`, `orderTotal`, `totalPostage`, `paymentMethod`, `orderDate`, `shippingAddress`, `postcode`, `phone`, `deliveryDate`) VALUES
(1778015480, 11, 72.00, 2.99, '', '2026-05-05 22:38:46', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `iBaySales`
--

CREATE TABLE `iBaySales` (
  `saleId` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `itemId` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `buyerId` varchar(40) NOT NULL,
  `sellerId` varchar(40) NOT NULL,
  `finalPrice` decimal(10,2) NOT NULL,
  `itemPostage` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `iBaySales`
--

INSERT INTO `iBaySales` (`saleId`, `orderId`, `itemId`, `quantity`, `buyerId`, `sellerId`, `finalPrice`, `itemPostage`) VALUES
(24, 1778015480, 6, 1, '11', '6', 72.00, 2.99);

-- --------------------------------------------------------

--
-- Table structure for table `iBayWatchlist`
--

CREATE TABLE `iBayWatchlist` (
  `watchId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `iBayBasket`
--
ALTER TABLE `iBayBasket`
  ADD PRIMARY KEY (`basketId`),
  ADD UNIQUE KEY `userId` (`userId`,`itemId`);

--
-- Indexes for table `iBayBids`
--
ALTER TABLE `iBayBids`
  ADD PRIMARY KEY (`bidId`);

--
-- Indexes for table `iBayImages`
--
ALTER TABLE `iBayImages`
  ADD PRIMARY KEY (`imageId`);

--
-- Indexes for table `iBayItems`
--
ALTER TABLE `iBayItems`
  ADD PRIMARY KEY (`itemId`);

--
-- Indexes for table `iBayMembers`
--
ALTER TABLE `iBayMembers`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `iBayOrders`
--
ALTER TABLE `iBayOrders`
  ADD PRIMARY KEY (`orderId`);

--
-- Indexes for table `iBaySales`
--
ALTER TABLE `iBaySales`
  ADD PRIMARY KEY (`saleId`);

--
-- Indexes for table `iBayWatchlist`
--
ALTER TABLE `iBayWatchlist`
  ADD PRIMARY KEY (`watchId`),
  ADD UNIQUE KEY `userId` (`userId`,`itemId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iBayBasket`
--
ALTER TABLE `iBayBasket`
  MODIFY `basketId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `iBayBids`
--
ALTER TABLE `iBayBids`
  MODIFY `bidId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iBayImages`
--
ALTER TABLE `iBayImages`
  MODIFY `imageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `iBayItems`
--
ALTER TABLE `iBayItems`
  MODIFY `itemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `iBayMembers`
--
ALTER TABLE `iBayMembers`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `iBayOrders`
--
ALTER TABLE `iBayOrders`
  MODIFY `orderId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1778015481;

--
-- AUTO_INCREMENT for table `iBaySales`
--
ALTER TABLE `iBaySales`
  MODIFY `saleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `iBayWatchlist`
--
ALTER TABLE `iBayWatchlist`
  MODIFY `watchId` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
