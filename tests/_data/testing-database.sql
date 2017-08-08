-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: domownik
-- ------------------------------------------------------
-- Server version	5.5.5-10.1.21-MariaDB

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
-- Table structure for table `budget_consolidations`
--

DROP TABLE IF EXISTS `budget_consolidations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `budget_consolidations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `base_budget_id` int(10) unsigned NOT NULL,
  `subject_budget_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_consolidations_base_budget_id_foreign` (`base_budget_id`),
  KEY `budget_consolidations_subject_budget_id_foreign` (`subject_budget_id`),
  CONSTRAINT `budget_consolidations_base_budget_id_foreign` FOREIGN KEY (`base_budget_id`) REFERENCES `budgets` (`id`),
  CONSTRAINT `budget_consolidations_subject_budget_id_foreign` FOREIGN KEY (`subject_budget_id`) REFERENCES `budgets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budget_consolidations`
--

LOCK TABLES `budget_consolidations` WRITE;
/*!40000 ALTER TABLE `budget_consolidations` DISABLE KEYS */;
/*!40000 ALTER TABLE `budget_consolidations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `budgets`
--

DROP TABLE IF EXISTS `budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `budgets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` char(32) COLLATE utf8_polish_ci NOT NULL,
  `name` char(64) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci,
  `status` char(32) COLLATE utf8_polish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `budgets_name_unique` (`name`),
  KEY `budgets_type_index` (`type`),
  KEY `budgets_name_index` (`name`),
  KEY `budgets_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `budgets`
--

LOCK TABLES `budgets` WRITE;
/*!40000 ALTER TABLE `budgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `budgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (46,'2017_06_10_000000_create_users_table',1),(47,'2017_06_10_000001_create_settings_table',1),(48,'2017_06_10_000002_create_budgets_table',1),(49,'2017_06_10_000003_create_budget_consolidations_table',1),(50,'2017_06_10_000004_create_transaction_categories_table',1),(51,'2017_06_10_000005_create_transactions_table',1),(52,'2017_06_10_000006_create_transaction_periodicities_table',1),(53,'2017_06_10_000007_create_transaction_periodicity_dailies_table',1),(54,'2017_06_10_000008_create_transaction_periodicity_monthlies_table',1),(55,'2017_06_10_000009_create_transaction_periodicity_one_shots_table',1),(56,'2017_06_10_000010_create_transaction_periodicity_weeklies_table',1),(57,'2017_06_10_000011_create_transaction_periodicity_yearlies_table',1),(58,'2017_06_10_000012_create_transaction_value_constants_table',1),(59,'2017_06_10_000013_create_transaction_value_ranges_table',1),(60,'2017_06_10_000014_create_transaction_schedules_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `key` char(128) COLLATE utf8_polish_ci NOT NULL,
  `value` text COLLATE utf8_polish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_user_id_index` (`user_id`),
  KEY `settings_user_id_key_index` (`user_id`,`key`),
  CONSTRAINT `settings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_categories`
--

DROP TABLE IF EXISTS `transaction_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_category_id` int(10) unsigned DEFAULT NULL,
  `name` char(128) COLLATE utf8_polish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_categories`
--

LOCK TABLES `transaction_categories` WRITE;
/*!40000 ALTER TABLE `transaction_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_periodicities`
--

DROP TABLE IF EXISTS `transaction_periodicities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_periodicities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) unsigned NOT NULL,
  `transaction_periodicity_id` int(10) unsigned NOT NULL,
  `transaction_periodicity_type` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_transaction_periodicities_1` (`transaction_periodicity_id`,`transaction_periodicity_type`),
  KEY `transaction_periodicities_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `transaction_periodicities_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_periodicities`
--

LOCK TABLES `transaction_periodicities` WRITE;
/*!40000 ALTER TABLE `transaction_periodicities` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_periodicities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_periodicity_dailies`
--

DROP TABLE IF EXISTS `transaction_periodicity_dailies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_periodicity_dailies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_periodicity_dailies`
--

LOCK TABLES `transaction_periodicity_dailies` WRITE;
/*!40000 ALTER TABLE `transaction_periodicity_dailies` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_periodicity_dailies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_periodicity_monthlies`
--

DROP TABLE IF EXISTS `transaction_periodicity_monthlies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_periodicity_monthlies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `day` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_periodicity_monthlies`
--

LOCK TABLES `transaction_periodicity_monthlies` WRITE;
/*!40000 ALTER TABLE `transaction_periodicity_monthlies` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_periodicity_monthlies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_periodicity_one_shots`
--

DROP TABLE IF EXISTS `transaction_periodicity_one_shots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_periodicity_one_shots` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_periodicity_one_shots`
--

LOCK TABLES `transaction_periodicity_one_shots` WRITE;
/*!40000 ALTER TABLE `transaction_periodicity_one_shots` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_periodicity_one_shots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_periodicity_weeklies`
--

DROP TABLE IF EXISTS `transaction_periodicity_weeklies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_periodicity_weeklies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weekday` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_periodicity_weeklies`
--

LOCK TABLES `transaction_periodicity_weeklies` WRITE;
/*!40000 ALTER TABLE `transaction_periodicity_weeklies` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_periodicity_weeklies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_periodicity_yearlies`
--

DROP TABLE IF EXISTS `transaction_periodicity_yearlies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_periodicity_yearlies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `month` tinyint(4) NOT NULL,
  `day` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_periodicity_yearlies`
--

LOCK TABLES `transaction_periodicity_yearlies` WRITE;
/*!40000 ALTER TABLE `transaction_periodicity_yearlies` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_periodicity_yearlies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_schedules`
--

DROP TABLE IF EXISTS `transaction_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_schedules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_schedules_date_index` (`date`),
  KEY `transaction_schedules_transaction_id_foreign` (`transaction_id`),
  CONSTRAINT `transaction_schedules_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_schedules`
--

LOCK TABLES `transaction_schedules` WRITE;
/*!40000 ALTER TABLE `transaction_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_value_constants`
--

DROP TABLE IF EXISTS `transaction_value_constants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_value_constants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_value_constants`
--

LOCK TABLES `transaction_value_constants` WRITE;
/*!40000 ALTER TABLE `transaction_value_constants` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_value_constants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_value_ranges`
--

DROP TABLE IF EXISTS `transaction_value_ranges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaction_value_ranges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `value_from` decimal(8,2) NOT NULL,
  `value_to` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_value_ranges`
--

LOCK TABLES `transaction_value_ranges` WRITE;
/*!40000 ALTER TABLE `transaction_value_ranges` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction_value_ranges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_transaction_id` int(10) unsigned DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `parent_type` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT NULL,
  `type` char(16) COLLATE utf8_polish_ci NOT NULL,
  `name` char(128) COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci,
  `value_id` int(10) unsigned DEFAULT NULL,
  `value_type` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `periodicity_type` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_parent_id_parent_type_index` (`parent_id`,`parent_type`),
  KEY `transactions_value_id_value_type_index` (`value_id`,`value_type`),
  KEY `transactions_parent_id_index` (`parent_id`),
  KEY `transactions_parent_type_index` (`parent_type`),
  KEY `transactions_type_index` (`type`),
  KEY `transactions_name_index` (`name`),
  KEY `transactions_category_id_foreign` (`category_id`),
  CONSTRAINT `transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `transaction_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` char(64) COLLATE utf8_polish_ci NOT NULL,
  `password` char(128) COLLATE utf8_polish_ci NOT NULL,
  `full_name` char(128) COLLATE utf8_polish_ci NOT NULL,
  `status` char(32) COLLATE utf8_polish_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_login_index` (`login`),
  KEY `users_full_name_index` (`full_name`),
  KEY `users_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$fvImtqIHRdnIQskjZp06LOCQ05qJVDxB8QdQ1pzYDHuDmtddjLNEC','Admin Admin','active','jPY7H4dZgI0Zg3MMmIFwYhAaziguEd2d59quZUpuiEb97xdTtzLKydcp7sWj','2017-08-08 16:08:05','2017-08-08 16:08:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-08 18:10:54
