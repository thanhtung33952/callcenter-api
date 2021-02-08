-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: JibannetCallCenter
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `CentersStaff`
--

DROP TABLE IF EXISTS `CentersStaff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CentersStaff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `family_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `given_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CentersStaff`
--

LOCK TABLES `CentersStaff` WRITE;
/*!40000 ALTER TABLE `CentersStaff` DISABLE KEYS */;
INSERT INTO `CentersStaff` VALUES (1,'user1@gmail.com','Staff 1',''),(2,'user2@gmail.com','Staff 2',''),(3,'user3@gmail.com','Staff 3',''),(4,'user4@gmail.com','Staff 4',''),(5,'user5@gmail.com','Staff 5',''),(6,'user6@gmail.com','Staff 6',''),(7,'user7@gmail.com','Staff 7',''),(8,'user8@gmail.com','Staff 8','');
/*!40000 ALTER TABLE `CentersStaff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Chat`
--

DROP TABLE IF EXISTS `Chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(9) NOT NULL,
  `writer_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_chat_id_idx` (`chat_id`),
  CONSTRAINT `fk_chat_id` FOREIGN KEY (`chat_id`) REFERENCES `ChatManage` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Chat`
--

LOCK TABLES `Chat` WRITE;
/*!40000 ALTER TABLE `Chat` DISABLE KEYS */;
INSERT INTO `Chat` VALUES (1,1,1,'2019-11-20 09:45:43','test');
/*!40000 ALTER TABLE `Chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ChatManage`
--

DROP TABLE IF EXISTS `ChatManage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ChatManage` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `theme_id` int(11) NOT NULL,
  `start` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_staff_id_idx` (`staff_id`),
  KEY `fk_theme_id_idx` (`theme_id`),
  KEY `fk_user_id_idx` (`user_id`),
  CONSTRAINT `fk_staff_id` FOREIGN KEY (`staff_id`) REFERENCES `CentersStaff` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `ChatUser` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ChatManage`
--

LOCK TABLES `ChatManage` WRITE;
/*!40000 ALTER TABLE `ChatManage` DISABLE KEYS */;
INSERT INTO `ChatManage` VALUES (1,1,1,1,'2019-11-20 18:44:46',NULL),(2,2,2,2,'2019-11-20 18:44:46',NULL);
/*!40000 ALTER TABLE `ChatManage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ChatTheme`
--

DROP TABLE IF EXISTS `ChatTheme`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ChatTheme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 NOT NULL,
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ChatTheme`
--

LOCK TABLES `ChatTheme` WRITE;
/*!40000 ALTER TABLE `ChatTheme` DISABLE KEYS */;
INSERT INTO `ChatTheme` VALUES (1,'地盤安心住宅の申込'),(2,'地盤安心住宅の進捗'),(3,'地盤調査の申込'),(4,'地盤改良工事の申込'),(5,'その他');
/*!40000 ALTER TABLE `ChatTheme` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ChatUser`
--

DROP TABLE IF EXISTS `ChatUser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ChatUser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `family_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `given_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `company_id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`company_id`),
  KEY `fk_company_id_idx` (`company_id`),
  CONSTRAINT `fk_company_id` FOREIGN KEY (`company_id`) REFERENCES `Company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ChatUser`
--

LOCK TABLES `ChatUser` WRITE;
/*!40000 ALTER TABLE `ChatUser` DISABLE KEYS */;
INSERT INTO `ChatUser` VALUES (1,'userchat1@gmail.com','User Chat 1','User Chat 1',1,'2019-11-20 18:43:19'),(2,'userchat2@gmail.com','User Chat 2','User Chat 2',2,'2019-11-20 18:43:19');
/*!40000 ALTER TABLE `ChatUser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Company`
--

DROP TABLE IF EXISTS `Company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8 NOT NULL,
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Company`
--

LOCK TABLES `Company` WRITE;
/*!40000 ALTER TABLE `Company` DISABLE KEYS */;
INSERT INTO `Company` VALUES (1,'Company 1'),(2,'Company 2');
/*!40000 ALTER TABLE `Company` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-21 18:20:19
