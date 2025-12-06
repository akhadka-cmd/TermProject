-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2025 at 03:13 AM
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
-- Database: `computer_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `order_date`) VALUES
(10, 2, 662.99, '2025-12-05 23:57:55'),
(11, 2, 6494.94, '2025-12-06 01:57:02');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT 'img/placeholder.jpg',
  `category` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category`, `stock`) VALUES
(1, 'NVIDIA RTX 4080 16GB', 'High-end GPU for 4K gaming and professional rendering.', 1199.99, 'img/rtx4080_16GB.jpg', 'Graphics Card', 15),
(2, 'NVIDIA RTX 4070 12GB', 'Excellent performance for 1440p gaming.', 599.99, 'img/rtx4070_12GB.jpg', 'Graphics Card', 22),
(3, 'AMD Radeon RX 6700 XT 12GB', 'Great value card for smooth high-refresh 1080p and 1440p.', 329.50, 'img/rx6700xt_12GB.jpg', 'Graphics Card', 18),
(4, 'NVIDIA RTX 5090 32GB Flagship', 'Extreme performance GPU with 32GB GDDR7, 512-bit interface, 4th Gen Ray Tracing, DLSS 4.0 support, and triple-fan cooling. Designed for 8K gaming and professional AI workloads.', 2499.00, 'img/rtx5090_32GB.jpg', 'Graphics Card', 5),
(5, 'NVIDIA RTX 5060 Ti 16GB Gaming', 'High-performance GPU featuring 16GB GDDR6X, 192-bit interface, and 4th Gen Tensor Cores. Ideal for 1440p high-refresh gaming and content creation.', 499.99, 'img/rtx5060Ti_16GB.jpg', 'Graphics Card', 28),
(6, 'NVIDIA GeForce GTX 1080 Ti 11GB Classic', 'Classic high-end GPU with 11GB GDDR5X, 352-bit bus, and G-SYNC support. Great value for competitive 1080p and entry-level 1440p gaming.', 249.99, 'img/rtx1080ti_11GB.jpg', 'Graphics Card', 10),
(7, 'AMD Radeon RX 580 8GB Gaming', 'Budget-friendly GPU with 8GB GDDR5 256bit, DP, HDMI, DVI ports and Dual Cooling Fans. Supports 4K for entry-level gaming and home office use.', 129.99, 'img/rx580_8GB.jpg', 'Graphics Card', 45),
(8, 'AMD Radeon RX 7900 XTX 24GB Pro', 'High-end competitor GPU with 24GB GDDR6, 384-bit memory bus, AMD RDNA 3 architecture, and advanced ray tracing. Excellent performance for 4K gaming.', 999.99, 'img/rx7900xtx_24GB.jpg', 'Graphics Card', 17),
(9, 'AMD Radeon RX 9070 XT 16GB Value', 'Mid-to-high-end GPU offering great value with 16GB GDDR6, high boost clock speeds, and FSR 3 technology. Strong performance for 1440p ultra settings.', 649.00, 'img/rx9070xt_16GB.jpg', 'Graphics Card', 20),
(10, 'Intel Arc B570 10GB', 'Dedicated graphics card from Intel.', 299.00, 'img/IntelArcB570_10GB.jpg', 'Graphics Card', 10),
(11, 'MSI Trident X-Plus (Tower) Gaming Desktop', 'Core i9-14900K, GeForce RTX 4080 16GB, 64GB DDR5 Memory (2x32GB), 4TB M.2 NVMe SSD, Liquid Cool, Wi-Fi 7, Windows 11 Pro.', 2899.00, 'img/msiGaming.jpg', 'Gaming PC', 9),
(12, 'ASUS ROG Strix Compact Desktop', 'Ryzen 7 7800X3D, GeForce RTX 4070 12GB, 32GB DDR5 Memory (2x16GB), 1TB Gen4 NVMe SSD, Air Cooled, Aura Sync RGB, Windows 11 Home.', 1799.00, 'img/asusgaming.jpg', 'Gaming PC', 8),
(13, 'ASUS ROG Zenith GT-R Mid-Tower', 'Ryzen 9 7950X, Radeon RX 7900 XTX 24GB, 64GB DDR5 Memory, 2TB M.2 SSD + 4TB HDD, 360mm AIO Cooler, USB 4.0, Windows 11 Pro.', 2499.00, 'img/asusgaming2.jpg', 'Gaming PC', 6),
(14, 'CyberPowerPC Gamer Xtreme VR', 'Core i7-14700F, GeForce RTX 5060 Ti 16GB, 32GB DDR5 Memory, 1TB NVMe SSD, Air Cool, Tempered Glass Side Panel, VR-Ready, Windows 11 Home.', 1499.00, 'img/cyberpowergaming.jpg', 'Gaming PC', 11),
(15, 'Apex Predator T-800', 'Core i5-13400F, Radeon RX 6700 XT 12GB, 16GB DDR4 Memory, 500GB NVMe SSD, Standard Air Cool, Mesh Front Panel, Windows 11 Home.', 899.00, 'img/gamingpc2.jpg', 'Gaming PC', 15),
(16, 'Velocity V3 Gaming System', 'Ryzen 5 7600, GeForce RTX 4070 12GB, 32GB DDR5 Memory, 1TB NVMe SSD, RGB Fans, High Airflow Chassis, Windows 11 Home.', 1199.00, 'img/gamingpc_3.jpg', 'Gaming PC', 14),
(17, 'Quantum Q5 Ultra Gaming Rig', 'Core i7-14700K, GeForce RTX 4080 16GB, 64GB DDR5 Memory, 2TB Gen4 SSD, 240mm AIO Cooler, USB-C 3.2, Windows 11 Pro.', 1999.00, 'img/gamingpc_4.jpg', 'Gaming PC', 7),
(18, 'Black Hole BH9 Gaming PC', 'Core i9-14900K, GeForce RTX 5090 32GB, 128GB DDR5 ECC Memory, 4TB Gen4 SSD, Custom Liquid Loop, Silent Operation, Windows 11 Pro.', 3299.00, 'img/gamingpc_5.jpg', 'Gaming PC', 5),
(19, 'Spectre S-20 Esports PC', 'Ryzen 7 7800X3D, Radeon RX 7900 XTX 24GB, 32GB DDR5 Memory, 1TB NVMe SSD, High-Speed Wi-Fi 6E, Zero Latency Design, Windows 11 Home.', 2399.00, 'img/gamingpc_7.jpg', 'Gaming PC', 5),
(20, 'Phantom P1 Value Build', 'Core i5-12400F, Radeon RX 6700 XT 12GB, 16GB DDR4 Memory, 1TB NVMe SSD, Compact Case, Integrated Audio, Windows 11 Home.', 1550.00, 'img/gamingpc_8.jpg', 'Gaming PC', 10),
(21, 'Acer EK271 Gbi 27-inch Monitor', '27-inch Full HD monitor, great for casual use and gaming.', 149.99, 'img/Acer EK271 Gbi 27-in.jpg', 'Monitor', 30),
(22, 'MSI Optix MAG342CQR Monitor', '34-inch UWQHD (3440x1440) Curved Gaming Monitor. Features a fast 144Hz refresh rate, 1ms response time, and a wide 1500R curvature for immersive, panoramic gameplay. HDR ready and height adjustable.', 499.00, 'img/MSI Optix MAG342CQR.jpg', 'Monitor', 12),
(23, 'EPOMAKER x Aula F99 Wireless Keyboard', '99-key Triple-Mode Mechanical Keyboard (Wired, Bluetooth, 2.4GHz) with hot-swappable switches, sound-dampening foam, and full RGB backlighting. Features long-lasting 4000mAh battery.', 109.99, 'img/EPOMAKER x Aula F99 Wireless.jpg', 'Keyboard', 25),
(24, 'Logitech K120 Wired Keyboard', 'Reliable, full-sized spill-resistant wired office keyboard. Features low-profile keys, a comfortable typing experience, and durable construction for everyday use. Plug and play via USB.', 19.99, 'img/Logitech K120 Wired Keyboard.jpg', 'Keyboard', 75),
(25, 'Razer Basilisk V3 Wired Gaming Mouse', 'Ergonomic wired gaming mouse with a 26K DPI optical sensor and 11 programmable buttons. Features a multi-function paddle and Razer Chroma RGB lighting for deep customization.', 69.99, 'img/RazerBasiliskV3WiredGamingMouse.jpg', 'Mouse', 40),
(26, 'Razer Naga V2 HyperSpeed Wireless Mouse', 'Wireless MMO Gaming Mouse with 20 programmable buttons, including a 12-button thumb grid. Features HyperSpeed Wireless technology and a 30K DPI optical sensor for lag-free performance.', 149.99, 'img/Razer Naga V2 HyperSpeed Wireless.jpg', 'Mouse', 25),
(27, 'Razer Viper V3 HyperSpeed Wireless Mouse', 'Ultra-lightweight wireless esports mouse designed for claw and fingertip grip. Features a 30K DPI optical sensor and HyperSpeed technology for competitive play. Weighs just 75g.', 119.99, 'img/RazerViperV3HyperSpeedWireless.jpg', 'Mouse', 35),
(28, 'Sony WH-1000XM4 Wireless Headphones', 'Industry-leading Noise-Canceling wireless over-ear headphones. Features up to 30 hours of battery life, superb call quality, and DSEE Extreme audio upscaling. Compatible with Alexa/Google Assistant.', 279.00, 'img/Sony WH-1000XM4 Wireless.jpg', 'Headset', 50),
(29, 'JBL Tune 520BT Wireless Earbuds', 'Lightweight wireless on-ear headphones delivering JBL Pure Bass Sound. Features up to 57 hours of playtime, Speed Charge support, and hands-free calls via Bluetooth 5.3.', 49.99, 'img/JBL Tune 520BT.jpg', 'Headset', 60);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `address`, `city`, `province`, `postal_code`, `is_admin`, `created_at`) VALUES
(2, 'Aayush', 'Khadka', 'akhadka', 'akhadka@alogmau.ca', '$2y$10$eXzkwCvQ7ERAX/8kGMOfB.UCQpJwGRl3UCBtWbpvjwgozL98cgQEC', '13 Fallharvest Avenue', 'Brampton', 'ON', 'L6Y 0P2', 0, '2025-12-05 05:19:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
