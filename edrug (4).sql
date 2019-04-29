-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2019 at 02:43 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `edrug`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `BnkNam` varchar(100) NOT NULL,
  `BnkAcc` varchar(100) NOT NULL,
  `BnkPin` varchar(100) NOT NULL,
  `BnkBal` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`BnkNam`, `BnkAcc`, `BnkPin`, `BnkBal`) VALUES
('Standard Chatered', '12345678', '1234', '4725.499999999999'),
('Barclays', '87654321', '8765', '4957.490000000001'),
('Stanbic Bank', '10024689', '1111', '195.91');

-- --------------------------------------------------------

--
-- Table structure for table `cartinfor`
--

CREATE TABLE `cartinfor` (
  `RegDat` varchar(100) NOT NULL,
  `NatID` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Alias` varchar(100) NOT NULL,
  `ProID` varchar(100) NOT NULL,
  `Product` varchar(200) NOT NULL,
  `ItemCost` varchar(100) NOT NULL,
  `NumTaken` varchar(100) NOT NULL,
  `TotCost` float NOT NULL,
  `CartPin` varchar(100) NOT NULL,
  `Paid` varchar(100) NOT NULL,
  `RecNum` varchar(100) NOT NULL,
  `PharmID` varchar(100) NOT NULL,
  `FinalAmount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cartinfor`
--

INSERT INTO `cartinfor` (`RegDat`, `NatID`, `Phone`, `Alias`, `ProID`, `Product`, `ItemCost`, `NumTaken`, `TotCost`, `CartPin`, `Paid`, `RecNum`, `PharmID`, `FinalAmount`) VALUES
('16/04/2019', '11-068109C71', '0773228829', 'Kelvin Mubatapasango', '1236', 'flour 2 Kgs', '3.89', '2', 7.78, 'Emp', 'No', 'Emp', '12356GBD56', 7),
('16/04/2019', '11-068109C71', '0773228829', 'Kelvin Mubatapasango', '1297', 'Rice 2 Litres', '4.5', '1', 4.5, 'Emp', 'No', 'Emp', '12356GBD56', 4.05),
('17/04/2019', '11-068109C71', '0773228829', 'Kelvin Mubatapasango', '1234', 'Mazowe 2 Kgs', '2.75', '2', 5.5, 'Emp', 'No', 'Emp', '3', 4.96),
('19/04/2019', '11-098765T56', '0778777654', 'kelvin munhu', '1306', 'png drug 22 Kgs', '44', '1', 44, '12359', 'Yes', '12359', '1', 39.6),
('19/04/2019', '11-098765T56', '0778777654', 'kelvin munhu', '1305', 'Amarula 100 Mills', '56', '3', 168, '12359', 'Yes', '12359', '1', 151.2),
('19/04/2019', '11-098787T56', '0778777654', 'Tafara Munhu', '1234', 'Mazowe 2 Kgs', '2.75', '3', 8.25, '12360', 'Yes', '12360', '3', 7.44),
('19/04/2019', '11-098787T56', '0778777654', 'Tafara Munhu', '1237', 'Buttercup Margarine 2 Kgs', '2.3', '1', 2.3, '12360', 'Yes', '12360', '3', 2.07),
('19/04/2019', '11-999876T56', '0773228829', 'tafara munhu', '1234', 'Mazowe 2 Kgs', '2.75', '1', 2.75, 'Emp', 'No', 'Emp', '3', 2.48),
('19/04/2019', '55-876567T56', '0778344666', 'Tarafa munhu', '1234', 'Mazowe 2 Kgs', '2.75', '5', 13.75, '12361', 'Yes', '12361', '3', 12.4),
('19/04/2019', '66-987654R45', '0774333543', 'tafara Munhu', '1236', 'flour 2 Kgs', '3.89', '1', 3.89, 'Emp', 'No', 'Emp', '3', 3.5);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `SystID` int(100) NOT NULL,
  `RegDat` varchar(100) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `ID` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Fax` varchar(100) NOT NULL,
  `BuisAddress` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Blocked` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`SystID`, `RegDat`, `FullName`, `ID`, `Phone`, `Email`, `Fax`, `BuisAddress`, `Username`, `Password`, `Blocked`) VALUES
(0, '12/12/2018', 'Dr Chen Chimutengwende', '2343876F45', '0774353543', 'echims@rocketmail.com', 'No Details', '100 George Silundika Harare', 'doctor', 'doctor', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `increments`
--

CREATE TABLE `increments` (
  `RecNumber` varchar(100) NOT NULL,
  `ProductID` varchar(100) NOT NULL,
  `SystID` int(100) NOT NULL,
  `PhSystID` int(100) NOT NULL,
  `PrescID` int(100) NOT NULL,
  `PreKey` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `increments`
--

INSERT INTO `increments` (`RecNumber`, `ProductID`, `SystID`, `PhSystID`, `PrescID`, `PreKey`) VALUES
('12362', '1309', 2, 5, 14, 1752380);

-- --------------------------------------------------------

--
-- Table structure for table `pharmacy`
--

CREATE TABLE `pharmacy` (
  `SystID` int(100) NOT NULL,
  `RegDat` varchar(100) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Company` varchar(100) NOT NULL,
  `ID` varchar(100) NOT NULL,
  `Phone` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PurchaseShare` varchar(100) NOT NULL,
  `BuisAddress` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Blocked` varchar(100) NOT NULL,
  `LocID` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pharmacy`
--

INSERT INTO `pharmacy` (`SystID`, `RegDat`, `FullName`, `Company`, `ID`, `Phone`, `Email`, `PurchaseShare`, `BuisAddress`, `Username`, `Password`, `Blocked`, `LocID`) VALUES
(0, '09/04/2019', 'Oval Therapy', 'Oasis Hotel', '536367H67', '0773424321', 'sales@ovaltherapy.com', '0', '34 George Silundika', 'user2345', 'pswd2345', 'No', ''),
(1, '12/04/2019', 'Gone Truth', 'farai.com', '3434', '0775435432', 'gonetru@gmail.com', '0', '45 Heavy', 'myusername', 'mypassword', 'No', ''),
(3, '16/04/2019', 'Far Away', 'Far Away', '345637G56', '0774545432', 'faraway@gmail.com', '0', '34 Reileigh Harare', 'pharmacy', 'pharmacy', 'No', 'L3.jpg'),
(4, '19/04/2019', 'New Pharmacy', 'Spar Zimbabwe', '552626H562', '0776666543', 'newpharm@gmail.com', '0', '34 Leopold Takawira', 'newpharmacy', 'newpharmacy', 'No', 'L4.png');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `ID` int(100) NOT NULL,
  `Pin` int(10) NOT NULL,
  `PatientName` varchar(100) NOT NULL,
  `Disease` varchar(100) NOT NULL,
  `Date` varchar(100) NOT NULL,
  `DrugNam` varchar(100) NOT NULL,
  `DrugSI` varchar(100) NOT NULL,
  `SIVal` varchar(100) NOT NULL,
  `DrugNum` varchar(100) NOT NULL,
  `Stat` varchar(100) NOT NULL,
  `Purchased` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`ID`, `Pin`, `PatientName`, `Disease`, `Date`, `DrugNam`, `DrugSI`, `SIVal`, `DrugNum`, `Stat`, `Purchased`) VALUES
(12, 2490540, 'Farai Mhute', 'Farao', '12/04/2019', 'Mazowe', 'Grams', '3', '5', 'Saved', ''),
(12, 2490540, 'Farai Mhute', 'Farao', '12/04/2019', 'co', 'Grams', '1', '2', 'Saved', ''),
(13, 1316040, 'Tafara Munhu', 'Bilharzia', '19/04/2019', 'Cerevita', 'Grams', '500', '2', 'Saved', 'No'),
(13, 1316040, 'Tafara Munhu', 'Bilharzia', '19/04/2019', 'Mazowe', 'Mills', '2000', '1', 'Saved', 'Yes Paid');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `RegDat` varchar(100) NOT NULL,
  `ID` varchar(100) NOT NULL,
  `Category` varchar(100) NOT NULL,
  `Alias` varchar(100) NOT NULL,
  `SiUnit` varchar(100) NOT NULL,
  `SIUVal` varchar(100) NOT NULL,
  `Cost` float NOT NULL,
  `ImgPath` varchar(100) NOT NULL,
  `InStk` int(100) NOT NULL,
  `PharmID` varchar(100) NOT NULL,
  `PharmNam` varchar(100) NOT NULL,
  `Location` varchar(100) NOT NULL,
  `Prescribed` varchar(100) NOT NULL,
  `NeedPresc` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`RegDat`, `ID`, `Category`, `Alias`, `SiUnit`, `SIUVal`, `Cost`, `ImgPath`, `InStk`, `PharmID`, `PharmNam`, `Location`, `Prescribed`, `NeedPresc`) VALUES
('', '1234', 'Other', 'Mazowe', 'Kgs', '2', 2.75, '1234.jpg', 989, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'Yes', 'No'),
('', '1236', 'Other', 'flour', 'Kgs', '2', 3.89, '1236.jpg', 998, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1237', 'Other', 'Buttercup Margarine', 'Kgs', '2', 2.3, '1237.jpg', 999, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1239', 'Other', 'Cerevita', 'Kgs', '2', 4.1, '1239.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'Yes', 'No'),
('', '1240', 'Grocery', 'Roller Meal', 'Litres', '2', 6.5, '1240.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1241', 'Grocery', 'rice', 'Litres', '2', 3.45, '1241.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1242', 'Grocery', 'cooking oil', 'Litres', '2', 15, '1242.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1262', 'Capsules', 'irvines chicken', 'Kgs', '1', 9.99, '1262.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1263', 'Capsules', 'fish', 'Kgs', '1', 12.99, '1263.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1264', 'Capsules', 'beef', 'Kgs', '1', 7, '1264.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1265', 'Capsules', 'goat meat', 'Kgs', '1', 5.6, '1265.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1266', 'Capsules', 'polony', 'Kgs', '1', 8.89, '1266.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1267', 'Capsules', 'pork meat', 'Kgs', '1', 7.5, '1267.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1268', 'Capsules', 'beef sausage', 'Kgs', '1', 6.79, '1268.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1269', 'Capsules', 'back bacon', 'Kgs', '1', 3.55, '1269.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1270', 'Pills', 'chibukr', 'Litres', '2', 1.5, '1270.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1271', 'Pills', 'amarula', 'Litres', '2', 12.5, '1271.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1272', 'Pills', 'hunters', 'Litres', '2', 2.5, '1272.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1273', 'Pills', 'red wine', 'Litres', '2', 22.78, '1273.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1274', 'Pills', 'cascel lager', 'Litres', '2', 12, '1274.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1275', 'Pills', 'cascel lite', 'Litres', '2', 15, '1275.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1276', 'Pills', 'Redd apple ale', 'Litres', '2', 24, '1276.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1277', 'Pills', 'two keys', 'Litres', '2', 7.5, '1277.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1278', 'Pills', 'savanna dry', 'Litres', '2', 18.88, '1278.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1279', 'Other', 'preminium bakers bread', '1 loaf ', '', 1.1, '1279.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1280', 'Other', 'slice black forest cake', '1 slice', '', 2.3, '1280.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1281', 'Other', 'proton buns', '500g', '', 1.2, '1281.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1282', 'Other', 'ctream doughu', '1 each', '', 1, '1282.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1283', 'Other', 'meat pies', '20g', '', 1.75, '1283.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1284', 'Other', 'fruit cake', 'medium', '', 15, '1284.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1285', 'Other', 'coconut pies', '20g', '', 0.75, '1285.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1286', 'Other', 'lobels bread', '1 loaf', '', 1.2, '1286.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1288', 'Grocery', 'sugar', 'Litres', '2', 2.09, '1288.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1289', 'Grocery', 'Nesle everyday', 'Litres', '2', 4.5, '1289.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1290', 'Bottled', 'mixed vegetables', '500g', '', 2, '1290.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1291', 'Bottled', 'oranges', 'i pocket', '', 5, '1291.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1292', 'Bottled', 'water mellon', 'i head', '', 1.25, '1292.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1293', 'Bottled', 'potatoes', '1 pocket', '', 14, '1293.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1294', 'Bottled', 'fresh tomatoes', '1kg', '', 2.5, '1294.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1295', 'Bottled', 'onion', '1 pocket', '', 1, '1295.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1296', 'Bottled', 'spinach', '1 bundle', '', 0.5, '1296.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('', '1297', 'Grocery', 'Rice', 'Litres', '2', 4.5, '1297.jpg', 1000, '3', 'Green World Pharmacy', '100 Leopold Takawira Harare', 'No', 'No'),
('09/04/2019', '1299', '', 'HALA', 'Grams', '22', 2, 'blank.jpg', 222, '3', 'Oval Therapy', '34 George Silundika', 'No', 'No'),
('09/04/2019', '1300', 'Pills', 'Varichem Pills', 'Tablets', '20', 2, 'blank.jpg', 1000, '3', 'Oval Therapy', '34 George Silundika', 'No', 'No'),
('09/04/2019', '1301', 'Capsules', 'Farex Pills', 'Tablets', '100', 7, '1301.jpg', 1000, '3', 'Oval Therapy', '34 George Silundika', 'No', 'No'),
('09/04/2019', '1302', 'Bottled', 'Woods Syrup', 'Mills', '100', 3, '1302.jpg', 500, '3', 'Oval Therapy', '34 George Silundika', 'No', 'No'),
('09/04/2019', '1303', 'Bottled', '67 trempies', 'Grams', '3', 6, '1303.ico', 666, '3', 'Oval Therapy', '34 George Silundika', 'No', 'No'),
('13/04/2019', '1304', 'Pills', 'gtel', 'Kgs', '1', 56, 'blank.jpg', 2, '3', 'Oval Therapy', '34 George Silundika', 'No', 'No'),
('19/04/2019', '1305', 'Bottled', 'Amarula', 'Mills', '100', 56, 'blank.jpg', 997, '1', 'Gone Truth', '45 Heavy', 'No', 'No'),
('19/04/2019', '1306', 'Capsules', 'png drug', 'Kgs', '22', 44, '1306.png', 2999, '1', 'Gone Truth', '45 Heavy', 'No', 'No'),
('19/04/2019', '1307', 'Bottled', 'Sugar', 'Kgs', '2', 5, '1307.png', 500, '1', 'Gone Truth', '45 Heavy', 'No', 'Yes'),
('19/04/2019', '1308', 'Bottled', 'kelvin', 'Kgs', '44', 3, '1308.png', 566, '4', 'New Pharmacy', '34 Leopold Takawira', 'No', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` varchar(100) NOT NULL,
  `Alias` varchar(100) NOT NULL,
  `AccLevel` varchar(100) NOT NULL,
  `Usrnm` varchar(100) NOT NULL,
  `Pswd` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Alias`, `AccLevel`, `Usrnm`, `Pswd`) VALUES
('1200', 'Garikai Zanu', 'Admin', 'usrnm', 'pswd');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
