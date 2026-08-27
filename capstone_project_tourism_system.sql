-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 26, 2025 at 08:08 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `capstone_project_tourism_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `adminID` int NOT NULL AUTO_INCREMENT,
  `adminUsername` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `adminPassword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`adminID`),
  UNIQUE KEY `adminUsername` (`adminUsername`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminID`, `adminUsername`, `adminPassword`) VALUES
(1, 'Elli', 'Elli');

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

DROP TABLE IF EXISTS `agency`;
CREATE TABLE IF NOT EXISTS `agency` (
  `agencyID` int NOT NULL AUTO_INCREMENT,
  `agencyName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agencyDescription` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agencyPhone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agencyEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agencyAddress` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`agencyID`),
  UNIQUE KEY `agencyName` (`agencyName`),
  UNIQUE KEY `agencyEmail` (`agencyEmail`),
  UNIQUE KEY `agencyPhone` (`agencyPhone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agency`
--

INSERT INTO `agency` (`agencyID`, `agencyName`, `agencyDescription`, `agencyPhone`, `agencyEmail`, `agencyAddress`) VALUES
(1, 'Jet2', 'Holiday', '01232345454', 'jet2@holiday', 'KL, Indonesia'),
(3, 'Agency 2', 'Test for Agent Panel', '0192894890', 'agency2@mail.com', 'Genting Highland, Singapore');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `bookingID` int NOT NULL AUTO_INCREMENT,
  `packageID` int DEFAULT NULL,
  `packageName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `packagehotelName` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `packageflightRoute` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotelID` int DEFAULT NULL,
  `flightRouteID` int DEFAULT NULL,
  `userID` int NOT NULL,
  `bookingDate` date NOT NULL,
  `bookingTime` time(6) NOT NULL,
  `numberOfPax` int NOT NULL,
  `departureDate` date NOT NULL,
  `returnDate` date NOT NULL,
  `amountDue` int NOT NULL,
  `rating` int DEFAULT NULL,
  `paymentID` int DEFAULT NULL,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`bookingID`),
  KEY `idx_packageID` (`packageID`),
  KEY `idx_userID` (`userID`),
  KEY `idx_invoiceID` (`paymentID`),
  KEY `hotelID` (`hotelID`),
  KEY `flightRouteID` (`flightRouteID`),
  KEY `paymentID` (`paymentID`),
  KEY `paymentID_2` (`paymentID`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`bookingID`, `packageID`, `packageName`, `packagehotelName`, `packageflightRoute`, `hotelID`, `flightRouteID`, `userID`, `bookingDate`, `bookingTime`, `numberOfPax`, `departureDate`, `returnDate`, `amountDue`, `rating`, `paymentID`, `status`) VALUES
(1, 1, 'Package Test', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 1, '2025-07-01', '23:46:05.000000', 5, '2025-07-02', '2025-07-11', 1000000000, 5, NULL, 'pending'),
(8, 1, 'Package Test', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 2, '2025-07-31', '11:11:08.000000', 3, '2025-08-13', '2025-08-16', 1500, NULL, NULL, 'pending'),
(9, 5, 'Trail Having Fun', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 2, '2025-07-31', '11:59:46.000000', 1, '2025-08-23', '2025-08-30', 700, NULL, NULL, 'pending'),
(10, NULL, NULL, NULL, NULL, 1, NULL, 2, '2025-07-31', '12:05:04.000000', 1, '2025-08-01', '2025-09-07', 7400, NULL, NULL, 'pending'),
(11, NULL, NULL, NULL, NULL, 1, NULL, 2, '2025-07-31', '12:31:58.000000', 1, '2025-08-22', '2025-08-28', 1200, NULL, NULL, 'pending'),
(12, NULL, NULL, NULL, NULL, NULL, 1, 2, '2025-07-31', '12:51:51.000000', 3, '2025-08-09', '2025-08-21', 1050, NULL, NULL, 'pending'),
(13, 5, 'Trail Having Fun', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 1, '2025-08-15', '05:29:09.000000', 1, '2025-08-31', '2025-09-07', 700, NULL, NULL, 'pending'),
(14, 6, 'Penang Package', 'Trivago', 'Kuala Lumpur → Penang', NULL, NULL, 1, '2025-08-15', '05:41:14.000000', 1, '2025-08-17', '2025-08-19', 350, NULL, 4, 'paid'),
(15, 1, 'Package Test', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 2, '2025-08-18', '11:01:17.000000', 2, '2025-08-23', '2025-08-26', 1000, NULL, 5, 'paid'),
(16, 5, 'Trail Having Fun', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 2, '2025-08-18', '11:02:21.000000', 3, '2025-08-28', '2025-09-04', 2100, NULL, 8, 'paid'),
(17, 6, 'Penang Package', 'Trivago', 'Kuala Lumpur → Penang', NULL, NULL, 2, '2025-08-18', '11:08:00.000000', 3, '2025-08-21', '2025-08-23', 1050, NULL, 9, 'paid'),
(18, NULL, NULL, NULL, NULL, 2, NULL, 2, '2025-08-20', '19:34:59.000000', 3, '2025-08-21', '2025-08-22', 300, NULL, NULL, 'pending'),
(19, NULL, NULL, NULL, NULL, 2, NULL, 2, '2025-08-20', '19:42:57.000000', 1, '2025-08-23', '2025-08-24', 100, NULL, NULL, 'pending'),
(20, NULL, NULL, NULL, NULL, 3, NULL, 2, '2025-08-20', '19:46:11.000000', 2, '2025-08-22', '2025-08-24', 600, NULL, NULL, 'pending'),
(21, NULL, NULL, NULL, NULL, 4, NULL, 2, '2025-08-20', '19:51:06.000000', 10, '2025-08-23', '2025-08-31', 16800, NULL, 10, 'paid'),
(22, NULL, NULL, NULL, NULL, NULL, 1, 2, '2025-08-20', '19:55:58.000000', 2, '2025-08-30', '2025-09-05', 700, NULL, 11, 'paid'),
(23, NULL, NULL, NULL, NULL, NULL, 3, 1, '2025-08-20', '20:00:26.000000', 1, '2025-08-23', '2025-08-29', 150, NULL, 14, 'paid'),
(24, 1, 'Package Test', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 1, '2025-08-20', '20:01:49.000000', 2, '2025-08-23', '2025-08-26', 1000, NULL, 16, 'paid'),
(25, 5, 'Trail Having Fun', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 1, '2025-08-20', '20:15:21.000000', 1, '2025-08-31', '2025-09-07', 700, NULL, 17, 'paid'),
(30, NULL, NULL, NULL, NULL, 4, NULL, 1, '2025-08-21', '08:12:28.000000', 6, '2025-08-30', '2025-09-01', 4200, NULL, 20, 'paid'),
(31, 6, 'Penang Package', 'Trivago', 'Kuala Lumpur → Penang', NULL, NULL, 1, '2025-08-21', '08:12:54.000000', 2, '2025-08-30', '2025-09-01', 700, NULL, 21, 'paid'),
(33, NULL, NULL, NULL, NULL, 2, NULL, 1, '2025-08-21', '08:22:28.000000', 1, '2025-08-22', '2025-08-24', 200, NULL, 23, 'paid'),
(34, 6, 'Penang Package', 'Trivago', 'Kuala Lumpur → Penang', NULL, NULL, 1, '2025-08-21', '08:22:58.000000', 2, '2025-08-23', '2025-08-25', 700, 5, 24, 'paid'),
(35, NULL, NULL, NULL, NULL, 3, NULL, 1, '2025-08-21', '08:23:24.000000', 2, '2025-08-22', '2025-08-25', 900, NULL, 25, 'paid'),
(36, NULL, NULL, NULL, NULL, NULL, 3, 1, '2025-08-21', '08:23:47.000000', 2, '2025-08-22', '2025-08-26', 300, NULL, 26, 'paid'),
(37, NULL, NULL, NULL, NULL, NULL, 1, 1, '2025-08-21', '08:34:52.000000', 3, '2025-08-22', '2025-08-28', 1050, NULL, 28, 'paid'),
(38, NULL, NULL, NULL, NULL, NULL, 1, 1, '2025-08-21', '08:48:00.000000', 1, '2025-08-29', '2025-08-30', 350, NULL, 29, 'paid'),
(39, NULL, NULL, NULL, NULL, NULL, 1, 1, '2025-08-21', '08:48:33.000000', 1, '2025-08-29', '2025-08-30', 350, NULL, 30, 'paid'),
(40, NULL, NULL, NULL, NULL, 2, NULL, 1, '2025-08-21', '08:49:01.000000', 1, '2025-09-20', '2025-09-21', 100, 5, 31, 'paid'),
(41, NULL, NULL, NULL, NULL, 3, NULL, 1, '2025-08-21', '09:12:40.000000', 2, '2025-08-23', '2025-08-30', 2100, NULL, 32, 'paid'),
(42, NULL, NULL, NULL, NULL, 3, NULL, 1, '2025-08-21', '09:33:22.000000', 1, '2025-08-22', '2025-08-23', 300, NULL, 33, 'paid'),
(43, NULL, NULL, NULL, NULL, 3, NULL, 2, '2025-08-21', '09:34:05.000000', 1, '2025-08-23', '2025-08-24', 300, NULL, 34, 'paid'),
(44, NULL, NULL, NULL, NULL, NULL, 1, 2, '2025-08-21', '09:35:16.000000', 1, '2025-08-24', '2025-08-25', 350, NULL, 35, 'paid'),
(45, NULL, NULL, NULL, NULL, NULL, 1, 1, '2025-08-21', '07:43:37.000000', 1, '2025-08-29', '2025-08-30', 350, NULL, 36, 'paid'),
(46, NULL, NULL, NULL, NULL, 4, NULL, 1, '2025-08-21', '07:44:04.000000', 1, '2025-08-28', '2025-08-29', 2100, NULL, 37, 'paid'),
(47, NULL, NULL, NULL, NULL, 3, NULL, 1, '2025-08-21', '08:07:49.000000', 1, '2025-08-23', '2025-08-24', 300, NULL, 38, 'paid'),
(48, NULL, NULL, NULL, NULL, 4, NULL, 1, '2025-08-21', '08:15:16.000000', 10, '2025-08-31', '2025-09-01', 2100, NULL, 39, 'paid'),
(50, 7, 'kelantan', 'Hotel', 'Melaka → Kuala Lumpur\r\n', NULL, NULL, 1, '2025-08-21', '08:19:28.000000', 100, '2025-08-31', '2025-09-02', 40000, NULL, 41, 'paid'),
(51, NULL, NULL, NULL, NULL, 1, NULL, 1, '2025-08-21', '08:23:27.000000', 1, '2025-08-31', '2026-01-31', 30600, NULL, 42, 'paid'),
(53, 6, 'Penang Package', 'Trivago', 'Burger King', NULL, NULL, 1, '2025-08-22', '00:00:12.000000', 1, '2025-08-29', '2025-08-31', 350, NULL, 44, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `card_details`
--

DROP TABLE IF EXISTS `card_details`;
CREATE TABLE IF NOT EXISTS `card_details` (
  `cardID` int NOT NULL AUTO_INCREMENT,
  `userID` int NOT NULL,
  `cardNumber` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cardDate` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cardName` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`cardID`),
  KEY `UserID` (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `card_details`
--

INSERT INTO `card_details` (`cardID`, `userID`, `cardNumber`, `cardDate`, `cardName`) VALUES
(3, 1, '1233323223323213', '12/31', 'test'),
(5, 2, '1231412423943994', '07/28', 'Bobby master');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
CREATE TABLE IF NOT EXISTS `destinations` (
  `destinationID` int NOT NULL AUTO_INCREMENT,
  `destinationName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `destinationDescription` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `destinationFacts` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `featuredPackages` int DEFAULT NULL,
  PRIMARY KEY (`destinationID`),
  UNIQUE KEY `destinationName` (`destinationName`),
  KEY `fk_destinations_featuredPackages` (`featuredPackages`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`destinationID`, `destinationName`, `destinationDescription`, `destinationFacts`, `featuredPackages`) VALUES
(1, 'Kuala Lumpur', 'Malaysia\'s vibrant capital dazzles with its perfect blend of modern skyscrapers and historic charm. Marvel at the iconic Petronas Twin Towers, then dive into bustling street food markets where Malay, Chinese, and Indian flavors collide deliciously.\r\nVisit historical landmarks, shop in world-class malls, and wander through bustling marketplaces like Chinatown and Little India. From the historic Sultan Abdul Samad Building to cutting-edge rooftop bars, KL offers incredible diversity, authentic experiences, and some of Southeast Asia\'s best value for money.\r\nA dynamic city that rewards the curious traveler with unforgettable sights, sounds, and tastes.', '* KL is home to some of the world\'s tallest buildings! This includes the Merdeka 118 tower and Petronas Twin Towers.* The city\'s name literally means \"muddy confluence\" in Malay, referring to where two muddy rivers meet.* In 1998, Kuala Lumpur became the first Asian city to host the Commonwealth Games in its 68-year history.* Kuala Lumpur was officially granted city status in 1972 and became a Federal Territory in 1974, making 2024 its 50th anniversary as a Federal Territory.* Despite its urban setting, KL actually houses several parks within it such as the KL Bird Park and the Perdana Botanical Garden. ', NULL),
(2, 'Johor', 'Malaysia\'s southernmost state offers the perfect blend of modern attractions and natural beauty. Legoland Malaysia delights families, while Johor Bahru\'s bustling streets serve up incredible street food and duty-free shopping. Escape to Desaru\'s pristine beaches on the east coast or explore Pulau Kukup\'s mangrove wetlands. The state\'s rich Malay heritage shines through royal palaces and traditional villages, making it an ideal introduction to Malaysian culture.', '* The first Legoland park in Asia was opened in Johor in 2012.* Johor has its own independent military force, the Royal Johor Military Force (RJMF).* Johor Bahru is the second largest city in Malaysia, with more than 1,065,341 inhabitants.', NULL),
(3, 'Kedah', 'Known as Malaysia\'s \"Rice Bowl,\" Kedah stretches from emerald paddy fields to the legendary Langkawi archipelago. UNESCO World Heritage Langkawi captivates with pristine beaches, the breathtaking Sky Bridge, and duty-free shopping paradise. On the mainland, Alor Setar\'s royal heritage and ancient Bujang Valley archaeological sites reveal centuries of history, while traditional villages showcase authentic Malay rural life.', '* Kedah is home to one of Malaysia\'s oldest archeological sites - Lembah Bujang.* Mount Jerai was used as a navigation point by ancient Arab and Indian traders.* Langkawi has exactly 99 islands at high tide and 104 at low tide.* Despite its small size, Kedah alone produces around 37% of Malaysia\'s rice.', NULL),
(4, 'Kelantan', 'Malaysia\'s most traditional state pulses with authentic Malay culture and artisan crafts. Kota Bharu\'s vibrant markets overflow with batik, silverware, and local delicacies, while traditional shadow puppet shows (wayang kulit) enchant evening audiences. The state\'s deep Islamic heritage creates a unique atmosphere where ancient customs thrive alongside modern life, offering visitors an authentic glimpse into Malaysia\'s cultural soul.', '* The state is famous for their Malay handicrafts which is Batik and Songket, a silky garment woven with intricate patterns.* Tumpat is home to the Wat Photivihan Sleeping Buddha, one of the largest reclining Buddha statues in Southeast Asia (≈40 m long).* Kelantan is one of the few Malaysian states where the weekend falls on Friday and Saturday, instead of Saturday and Sunday.* The country\'s largest national park, Taman Negara, is partially located in Kelantan.', NULL),
(5, 'Melaka', 'This UNESCO World Heritage state tells Malaysia\'s colonial story through perfectly preserved Portuguese, Dutch, and British architecture. Wander the historic Stadthuys, climb St. Paul\'s Hill, and cruise the Melaka River past colorful shophouses. Peranakan culture flourishes here with unique Nyonya cuisine, intricate beadwork, and the famous chicken rice balls that have become a pilgrimage for food lovers across Southeast Asia.', '* The Stadthuys, constructed in the 1650s, is believed to be the oldest Dutch building in the East.* According to architectural records, the ceiling beams of Christ Church are carved from single tree trunks.* The state is so small you can drive across it in 2 hours.* Baba Nyonya heritage combines Chinese and Malay cultures in a way found nowhere else in the world.', NULL),
(6, 'Negeri Sembilan', 'Distinguished by its striking Minangkabau architecture with distinctive curved roofs resembling buffalo horns, this state showcases Malaysia\'s diverse cultural influences. Seremban, the royal capital, blends modern amenities with traditional crafts markets and the famous siew pau. The state\'s nine historical districts each tell unique stories of royal heritage and ancient customs passed down through matrilineal traditions.', '* Negeri Sembilan is famous for its siew pau, a baked pastry with char siew filling.* Adat Perpatih, the state’s traditional law, is still practiced alongside modern law.* Traditional Minangkabau houses are built entirely without nails, using a complex interlocking system.* The Negeri Sembilan State Museum (Teratak Perpatih Building) is designed in Minangkabau architectural style, with the buffalo-horn roof motif typical of a Rumah Gadang — giving it the appearance of a palace.', NULL),
(7, 'Pahang', 'Malaysia\'s largest state is an adventurer\'s paradise spanning from cool highland retreats to pristine islands. Cameron Highlands\' rolling tea plantations and strawberry farms offer respite from tropical heat, while Taman Negara\'s ancient rainforest provides world-class jungle trekking. Off the east coast, Pulau Tioman\'s crystal-clear waters and coral reefs create a diving and snorkeling wonderland that rivals any tropical destination.', '* BOH Plantations, based in the Cameron Highlands, produces approximately 2.8 million kg of tea annually, which translates to about 5 million cups per day!* The state is larger than Switzerland but has a smaller population than Zurich.* Fraser’s Hill was named after a mysterious Scottish adventurer who disappeared without a trace.* Genting Highlands houses a theme park as well as the only legal casino in Malaysia.', NULL),
(8, 'Penang', 'This island state perfectly balances UNESCO World Heritage charm with culinary excellence that\'s legendary throughout Asia. George Town\'s colorful colonial architecture houses incredible street art, while hawker centers serve arguably Malaysia\'s best street food. From clan houses to spice gardens, trishaw rides to hilltop temples, Penang offers cultural immersion alongside modern shopping and vibrant nightlife that keeps visitors returning year after year.', '* The state consists of Penang Island and a mainland portion called Seberang Perai.* The Penang Hill funicular railway spans approximately 1.99 km, making it the longest funicular railway in Asia.* Char kway teow originated in Penang, brought by Teochew immigrants in the early 20th century, and became a beloved energy-rich laborers\' dish.* The Snake Temple houses live venomous pit vipers that are supposedly docile due to incense smoke.* The Penang Bridge is among the longest bridges in Southeast Asia.', NULL),
(9, 'Perak', 'Ipoh, the state capital, combines stunning limestone cave temples with old-world colonial architecture and Malaysia\'s famous white coffee culture. The royal town of Kuala Kangsar showcases elegant palaces and traditional crafts, while Taiping\'s Lake Gardens provide tranquil respite. Don\'t miss the iconic bean sprout chicken rice and the mystical cave temples carved into dramatic limestone cliffs that dot the landscape.', '* Tempurung Cave in Perak is one of the largest and longest limestone caves in Peninsular Malaysia—spanning around 1.9 km.* The name Perak literally means “silver” in Malay. The most commonly accepted origin links it to the state’s historical tin-mining wealth — tin ore often looks silvery when smelted* Taiping was Malaysia\'s first hill station and has the country\'s oldest museum, zoo, and public garden* Royal Belum State Park contains the world\'s oldest rainforest—even older than Amazon and Congo* Kellie\'s Castle remains unfinished because its Scottish owner died during a trip to Portugal in 1926.', NULL),
(10, 'Perlis', 'Malaysia\'s smallest state may be tiny, but it packs genuine charm into every corner. The border town of Kangar offers authentic local life away from tourist crowds, while the unique Gua Kelam limestone tunnel provides an unforgettable walking adventure through an underground river cave. Perlis\'s laid-back atmosphere and friendly locals create an intimate travel experience that feels like discovering Malaysia\'s best-kept secret.', '* Perlis is Malaysia\'s smallest state. It is smaller than Singapore and has fewer people than a typical KL suburb.* Perlis is the only Malaysian state that borders only one other state (Kedah).* The Wang Kelian border market is well-known for Thai goods. In the past, Malaysians could even cross briefly into Thailand without passports (policy now restricted).* Gua Kelam (“Cave of Darkness”) was once a tin mining tunnel. Today, it’s a tourist attraction with a wooden walkway through the limestone cave.', NULL),
(11, 'Sabah', 'This East Malaysian state delivers raw natural beauty and incredible biodiversity. Climb Mount Kinabalu, Southeast Asia\'s highest peak, then descend to encounter orangutans in Sepilok Sanctuary. The Kinabatangan River\'s wildlife cruises reveal proboscis monkeys and pygmy elephants, while Sipadan Island offers world-renowned diving with hammerhead sharks and sea turtles. Sabah combines adventure with indigenous culture in an unforgettable Borneo experience.', '* Mount Kinabalu grows 5mm taller each year due to tectonic activity.* Sepilok Orangutan Rehabilitation Centre is one of the most famous and largest rehabilitation centers in the world.* The state is home to the world\'s largest flower - the Rafflesia, which can grow up to 1 meter in diameter.* Sipadan Island rises 600 meters from the sea floor, making it Malaysia\'s only oceanic island. It was formed by corals growing on an extinct volcanic cone that rose from the seabed. It’s Malaysia’s only true oceanic island (not connected to the continental shelf).* The Bornean pygmy elephant (Elephas maximus borneensis) is the smallest Asian elephant subspecies, found only in Sabah and northern Kalimantan (Indonesia).', NULL),
(12, 'Sarawak', 'Malaysia\'s largest state showcases incredible cultural diversity through longhouse visits with indigenous Dayak communities and the charming riverside city of Kuching. Mulu National Park\'s massive limestone caves and razor-sharp pinnacles create otherworldly landscapes, while Bako National Park offers proboscis monkey encounters. Traditional crafts, river journeys, and jungle adventures make Sarawak an authentic window into Borneo\'s fascinating heritage.', '* Sarawak Chamber (in Gua Nasib Bagus, Gunung Mulu NP) is the largest known cave chamber in the world by area (≈600m × 415m × 80m).* Kuching is one of the few cities named after an animal (kuching means \'cat\' in Malay).* The state has over 40 ethnic groups (Iban, Bidayuh, Melanau, Orang Ulu, Penan, etc.) speaking more than 60 languages.* Niah Caves contain 40,000-year-old human remains - some of the oldest in Southeast Asia* The Rajang River is Malaysia\'s longest river and was once the main highway through Borneo. It was historically vital for trade and transport deep into Sarawak.', NULL),
(13, 'Selangor', 'Surrounding Kuala Lumpur, this prosperous state combines spiritual sites with modern attractions. The mystical Batu Caves draw pilgrims and tourists to their dramatic limestone chambers, while Shah Alam\'s magnificent Blue Mosque showcases contemporary Islamic architecture. Historic Klang serves up Malaysia\'s best bak kut teh (herbal pork soup), and Putrajaya\'s futuristic government buildings create a striking contrast to traditional kampung villages.', '* Batu Caves\' Lord Murugan statue is the tallest Hindu deity statue in Malaysia at 42.7 meters.* The state surrounds both Kuala Lumpur and Putrajaya but governs neither.* Shah Alam\'s Blue Mosque can accommodate 24,000 worshippers - one of the largest in Southeast Asia.* Selangor is Malaysia\'s wealthiest state and contributes 25% of the country\'s GDP.* The state has Malaysia\'s busiest port (Port Klang) and busiest airports (KLIA & KLIA2).* Selangor has hosted the Formula One Malaysian Grand Prix between 1999 and 2017 at the Sepang International Circuit.', NULL),
(14, 'Terengganu', 'This traditional east coast state offers pristine beaches, crystal-clear waters, and authentic Malay maritime culture. Kuala Terengganu\'s stunning Crystal Mosque and Islamic Heritage Park celebrate the state\'s spiritual heritage, while nearby islands provide world-class snorkeling and turtle watching. Traditional boat builders still craft wooden vessels by hand, and local markets overflow with fresh seafood and traditional kuih desserts that define Malaysia\'s coastal cuisine.', '* The Crystal Mosque is made entirely of steel, glass, and crystal, and can accommodate 1,500 worshippers.* The Pulau Redang Marine Park is a rich biodiversity hotspot, with studies confirming 500+ fish species and around 60 coral species.* Terengganu’s Pulau Duyong boat builders are famous for crafting boats without nails, using ancient techniques passed through generations.* The state\'s turtle sanctuaries protect 4 of the world\'s 7 sea turtle species.* Terengganu has Malaysia\'s longest coastline stretching 244 kilometers.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `flight_routes`
--

DROP TABLE IF EXISTS `flight_routes`;
CREATE TABLE IF NOT EXISTS `flight_routes` (
  `flightRouteID` int NOT NULL AUTO_INCREMENT,
  `flightDuration` int NOT NULL,
  `seatType` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `seatPrice` int NOT NULL,
  `airlineProvide` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `routeDeparturePoint` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `routeArrivalPoint` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `managingAgency` int DEFAULT NULL,
  PRIMARY KEY (`flightRouteID`),
  KEY `fk_flight_routes_managingAgency` (`managingAgency`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flight_routes`
--

INSERT INTO `flight_routes` (`flightRouteID`, `flightDuration`, `seatType`, `seatPrice`, `airlineProvide`, `routeDeparturePoint`, `routeArrivalPoint`, `managingAgency`) VALUES
(1, 3, 'Economy', 350, 'GroundAsia', 'Melaka', 'Kuala Lumpur\r\n', 1),
(3, 2, 'Economy', 150, 'Burger King', 'Kuala Lumpur', 'Penang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
CREATE TABLE IF NOT EXISTS `hotels` (
  `hotelID` int NOT NULL AUTO_INCREMENT,
  `hotelName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hotelDescription` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hotelRoomTypes` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pricePerNight` int NOT NULL,
  `hotelPhone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hotelEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hotelAddress` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `hotelImage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`hotelID`),
  UNIQUE KEY `hotelName` (`hotelName`),
  UNIQUE KEY `hotelEmail` (`hotelEmail`),
  UNIQUE KEY `hotelAddress` (`hotelAddress`),
  UNIQUE KEY `hotelPhone` (`hotelPhone`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`hotelID`, `hotelName`, `hotelDescription`, `hotelRoomTypes`, `pricePerNight`, `hotelPhone`, `hotelEmail`, `hotelAddress`, `hotelImage`) VALUES
(1, 'Hotel', 'hotel test', 'Single', 200, '01987494883', 'hotel@hotelmail.com', 'Kuala Lumpur', ''),
(2, 'Trivago', 'Hotel?', 'Single', 100, '0102993752', 'trivago@hotel.com', 'Penang', ''),
(3, 'Hotel Double', 'double double double lift', 'Double', 300, '012202020202', 'double@double.com', 'double', ''),
(4, 'President Hotel', 'bal ablablalabladsdf', 'Presidential Suite', 2100, '0202002020', 'presidentcecece@gmail.com', 'presissisiisis', '');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `invoiceID` int NOT NULL AUTO_INCREMENT,
  `bookingID` int DEFAULT NULL,
  `paymentID` int NOT NULL,
  `userID` int NOT NULL,
  `packageID` int DEFAULT NULL,
  `numberOfPax` int NOT NULL,
  `totalPaid` int NOT NULL,
  `tripDuration` int NOT NULL,
  `flightRouteID` int DEFAULT NULL,
  `hotelID` int DEFAULT NULL,
  `booking_type` enum('package','flight','hotel','unknown') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unknown',
  PRIMARY KEY (`invoiceID`),
  UNIQUE KEY `paymentID` (`paymentID`),
  KEY `idx_userID` (`userID`),
  KEY `idx_packageID` (`packageID`),
  KEY `idx_bookingID` (`bookingID`),
  KEY `fk_invoices_flight` (`flightRouteID`),
  KEY `fk_invoices_hotel` (`hotelID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoiceID`, `bookingID`, `paymentID`, `userID`, `packageID`, `numberOfPax`, `totalPaid`, `tripDuration`, `flightRouteID`, `hotelID`, `booking_type`) VALUES
(1, 15, 5, 2, 1, 2, 1000, 3, NULL, NULL, 'unknown'),
(2, 16, 8, 2, NULL, 3, 2100, 7, NULL, NULL, 'unknown'),
(3, 17, 9, 2, 6, 3, 1050, 2, NULL, NULL, 'unknown'),
(9, 25, 17, 1, NULL, 1, 700, 7, NULL, NULL, 'unknown'),
(13, 31, 21, 1, 6, 2, 700, 2, NULL, NULL, 'unknown'),
(14, 30, 20, 1, NULL, 6, 12600, 2, NULL, 4, 'unknown'),
(15, 33, 23, 1, NULL, 1, 200, 2, NULL, 2, 'unknown'),
(16, 34, 24, 1, 6, 2, 700, 2, NULL, NULL, 'unknown'),
(17, 35, 25, 1, NULL, 2, 1800, 3, NULL, 3, 'unknown'),
(18, 40, 31, 1, NULL, 1, 100, 1, NULL, 2, 'unknown'),
(19, 41, 32, 1, NULL, 2, 4200, 7, NULL, 3, 'unknown'),
(20, 42, 33, 1, NULL, 1, 300, 1, NULL, 3, 'unknown'),
(21, 43, 34, 2, NULL, 1, 300, 1, NULL, 3, 'unknown'),
(22, 48, 39, 1, NULL, 10, 2100, 0, NULL, 4, 'hotel'),
(25, 51, 42, 1, NULL, 1, 30600, 0, NULL, 1, 'hotel'),
(27, 53, 44, 1, 6, 1, 350, 2, NULL, NULL, 'package');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `packageID` int NOT NULL AUTO_INCREMENT,
  `agencyID` int NOT NULL,
  `destinationID` int NOT NULL,
  `hotelID` int NOT NULL,
  `flightRouteID` int NOT NULL,
  `packageName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `packageDescription` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `packagePrice` int NOT NULL,
  `packageDuration` int NOT NULL,
  PRIMARY KEY (`packageID`),
  KEY `idx_agencyID` (`agencyID`),
  KEY `idx_destinationID` (`destinationID`),
  KEY `idx_hotelID` (`hotelID`),
  KEY `idx_flightRouteID` (`flightRouteID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`packageID`, `agencyID`, `destinationID`, `hotelID`, `flightRouteID`, `packageName`, `packageDescription`, `packagePrice`, `packageDuration`) VALUES
(1, 1, 1, 1, 1, 'Package Test', '50 character package description testaklsdjasdljkasdljkasdljkasdjklasdjklasdlkjasd', 500, 3),
(6, 3, 3, 2, 3, 'Penang Package', 'go penang do what oh diu', 350, 2),
(7, 1, 4, 1, 1, 'kelantan', '10 characters long', 400, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `paymentID` int NOT NULL AUTO_INCREMENT,
  `bookingID` int NOT NULL,
  `userID` int NOT NULL,
  `paymentDate` date NOT NULL,
  `paymentTime` time(6) NOT NULL,
  `cardID` int DEFAULT NULL,
  PRIMARY KEY (`paymentID`),
  UNIQUE KEY `bookingID` (`bookingID`),
  KEY `payments_ibfk_2` (`userID`),
  KEY `fk_cardID` (`cardID`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentID`, `bookingID`, `userID`, `paymentDate`, `paymentTime`, `cardID`) VALUES
(4, 14, 1, '2025-08-15', '05:50:50.000000', 3),
(5, 15, 2, '2025-08-18', '11:01:30.000000', 5),
(8, 16, 2, '2025-08-18', '11:02:24.000000', 5),
(9, 17, 2, '2025-08-18', '11:08:09.000000', 5),
(10, 21, 2, '2025-08-20', '19:51:10.000000', 5),
(11, 22, 2, '2025-08-20', '19:56:01.000000', 5),
(14, 23, 1, '2025-08-20', '20:00:30.000000', 3),
(16, 24, 1, '2025-08-20', '20:01:53.000000', 4),
(17, 25, 1, '2025-08-20', '20:15:24.000000', 4),
(18, 26, 1, '2025-08-20', '21:15:41.000000', 3),
(19, 29, 1, '2025-08-21', '08:10:48.000000', 4),
(20, 30, 1, '2025-08-21', '08:12:31.000000', 3),
(21, 31, 1, '2025-08-21', '08:12:56.000000', 3),
(22, 32, 1, '2025-08-21', '08:17:39.000000', 3),
(23, 33, 1, '2025-08-21', '08:22:32.000000', 3),
(24, 34, 1, '2025-08-21', '08:23:00.000000', 3),
(25, 35, 1, '2025-08-21', '08:23:27.000000', 3),
(26, 36, 1, '2025-08-21', '08:23:54.000000', 3),
(28, 37, 1, '2025-08-21', '08:35:22.000000', 3),
(29, 38, 1, '2025-08-21', '08:48:03.000000', 3),
(30, 39, 1, '2025-08-21', '08:48:36.000000', 3),
(31, 40, 1, '2025-08-21', '08:49:04.000000', 3),
(32, 41, 1, '2025-08-21', '09:12:43.000000', 3),
(33, 42, 1, '2025-08-21', '09:33:27.000000', 4),
(34, 43, 2, '2025-08-21', '09:34:11.000000', 5),
(35, 44, 2, '2025-08-21', '09:35:20.000000', 5),
(36, 45, 1, '2025-08-21', '07:43:41.000000', 3),
(37, 46, 1, '2025-08-21', '07:44:07.000000', 3),
(38, 47, 1, '2025-08-21', '08:07:52.000000', 3),
(39, 48, 1, '2025-08-21', '08:15:21.000000', 3),
(40, 49, 1, '2025-08-21', '08:17:44.000000', 3),
(41, 50, 1, '2025-08-21', '08:19:36.000000', 4),
(42, 51, 1, '2025-08-21', '08:23:30.000000', 3),
(43, 52, 1, '2025-08-22', '11:51:43.000000', 3),
(44, 53, 1, '2025-08-22', '12:06:44.000000', 4);

-- --------------------------------------------------------

--
-- Table structure for table `travel_agents`
--

DROP TABLE IF EXISTS `travel_agents`;
CREATE TABLE IF NOT EXISTS `travel_agents` (
  `agentID` int NOT NULL AUTO_INCREMENT,
  `agentUsername` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agentPassword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `agentEmployer` int NOT NULL,
  PRIMARY KEY (`agentID`),
  UNIQUE KEY `agentID` (`agentID`,`agentUsername`),
  KEY `agentEmployer` (`agentEmployer`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_agents`
--

INSERT INTO `travel_agents` (`agentID`, `agentUsername`, `agentPassword`, `agentEmployer`) VALUES
(1, 'agent1', 'agent1', 1),
(2, 'Jimmy', 'agent2', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userPassword` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userPhone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `userGender` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `userDOB` date NOT NULL,
  `userEmail` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `userPhone` (`userPhone`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `username`, `userPassword`, `userPhone`, `userGender`, `userDOB`, `userEmail`) VALUES
(1, 'bob', 'bob123', '012345678912', 'o', '2025-07-15', 'bob@mails'),
(2, 'bobby', 'bobby123', '0122299900', 'm', '2005-07-07', 'bobby@gmail.com');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_payment` FOREIGN KEY (`paymentID`) REFERENCES `payments` (`paymentID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `card_details`
--
ALTER TABLE `card_details`
  ADD CONSTRAINT `card_details_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `destinations`
--
ALTER TABLE `destinations`
  ADD CONSTRAINT `fk_destinations_featuredPackages` FOREIGN KEY (`featuredPackages`) REFERENCES `packages` (`packageID`);

--
-- Constraints for table `flight_routes`
--
ALTER TABLE `flight_routes`
  ADD CONSTRAINT `fk_flight_routes_managingAgency` FOREIGN KEY (`managingAgency`) REFERENCES `agency` (`agencyID`);

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_booking` FOREIGN KEY (`bookingID`) REFERENCES `bookings` (`bookingID`),
  ADD CONSTRAINT `fk_invoices_flight` FOREIGN KEY (`flightRouteID`) REFERENCES `flight_routes` (`flightRouteID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_invoices_hotel` FOREIGN KEY (`hotelID`) REFERENCES `hotels` (`hotelID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_invoices_package` FOREIGN KEY (`packageID`) REFERENCES `packages` (`packageID`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_invoices_payment` FOREIGN KEY (`paymentID`) REFERENCES `payments` (`paymentID`),
  ADD CONSTRAINT `fk_invoices_user` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`);

--
-- Constraints for table `travel_agents`
--
ALTER TABLE `travel_agents`
  ADD CONSTRAINT `travel_agents_ibfk_1` FOREIGN KEY (`agentEmployer`) REFERENCES `agency` (`agencyID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
