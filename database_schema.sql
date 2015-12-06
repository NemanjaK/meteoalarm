-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.46-0ubuntu0.14.04.2 - (Ubuntu)
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for meteoalarm
CREATE DATABASE IF NOT EXISTS `meteoalarm` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `meteoalarm`;


-- Dumping structure for table meteoalarm.alert_queue
DROP TABLE IF EXISTS `alert_queue`;
CREATE TABLE IF NOT EXISTS `alert_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(11) NOT NULL,
  `measurement_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscriber_id_measurement_id` (`subscriber_id`,`measurement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table meteoalarm.component
DROP TABLE IF EXISTS `component`;
CREATE TABLE IF NOT EXISTS `component` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sepa_id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sepa_id` (`sepa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table meteoalarm.measurement
DROP TABLE IF EXISTS `measurement`;
CREATE TABLE IF NOT EXISTS `measurement` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_id` int(10) unsigned NOT NULL,
  `value` double unsigned DEFAULT NULL,
  `component_id` int(10) unsigned NOT NULL,
  `measure_timestamp` datetime NOT NULL,
  `alert` tinyint(1) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_id_component_id_measure_timestamp` (`station_id`,`component_id`,`measure_timestamp`),
  KEY `measurementComponentFK` (`component_id`),
  CONSTRAINT `measurementComponentFK` FOREIGN KEY (`component_id`) REFERENCES `component` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `measurementStationFK` FOREIGN KEY (`station_id`) REFERENCES `station` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table meteoalarm.station
DROP TABLE IF EXISTS `station`;
CREATE TABLE IF NOT EXISTS `station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eoi_code` varchar(10) NOT NULL,
  `name` varchar(250) NOT NULL,
  `network` varchar(10) NOT NULL,
  `type` enum('background','industrial','traffic') NOT NULL DEFAULT 'background',
  `sepa_id` int(10) unsigned NOT NULL,
  `started` date NOT NULL,
  `zone` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  `altitude` float(5,2) NOT NULL,
  `aqi_value` int(3) DEFAULT NULL,
  `aqi_timestamp` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sepa_id` (`sepa_id`),
  UNIQUE KEY `eoi_code` (`eoi_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table meteoalarm.station_aqi_history
DROP TABLE IF EXISTS `station_aqi_history`;
CREATE TABLE IF NOT EXISTS `station_aqi_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `station_id` int(10) unsigned NOT NULL DEFAULT '0',
  `no2` int(3) unsigned DEFAULT NULL,
  `co` int(3) unsigned DEFAULT NULL,
  `pm2p5_hourly` int(3) unsigned DEFAULT NULL,
  `pm2p5_daily` int(3) unsigned DEFAULT NULL,
  `pm10_hourly` int(3) unsigned DEFAULT NULL,
  `pm10_daily` int(3) unsigned DEFAULT NULL,
  `o3` int(3) unsigned DEFAULT NULL,
  `so2` int(3) unsigned DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stationAqiHistoryFK` (`station_id`),
  CONSTRAINT `stationAqiHistoryFK` FOREIGN KEY (`station_id`) REFERENCES `station` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table meteoalarm.subscriber
DROP TABLE IF EXISTS `subscriber`;
CREATE TABLE IF NOT EXISTS `subscriber` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(200) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
