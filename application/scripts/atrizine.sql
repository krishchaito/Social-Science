USE `atrizine`;

--
-- Table structure for `projects`
--
CREATE TABLE IF NOT EXISTS `projects` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Project ID',
    `pid` VARCHAR(32) NOT NULL COMMENT 'Used for Editing project. Should not be used in any other place.',
    `title` VARCHAR(50) NOT NULL,
    `hashTag` VARCHAR(50) NOT NULL,
    `summary` text NOT NULL,
    `description` longtext NOT NULL,
    `useTwitter` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-TRUE, 0-FALSE',
    `useInstagram` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-TRUE, 0-FALSE',
    `usePicture` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-TRUE, 0-FALSE',
    `gpsReq` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1-TRUE, 0-FALSE',
    `tweetFormat` VARCHAR(100) NOT NULL,
    `trackData` text NOT NULL,
    `status` enum('Active','Closed') NOT NULL DEFAULT 'Active',
    `createdDateTime` datetime DEFAULT '0000-00-00 00:00:00',
    `tsLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `pid` (`pid`),
    KEY `createdDateTime` (`createdDateTime`),
    KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for `posts`
--
CREATE TABLE IF NOT EXISTS `posts` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `projectId` bigint(20) unsigned NOT NULL COMMENT 'Id from projects table',
    `postIdStr` VARCHAR(64) NOT NULL DEFAULT '',
    `text` VARCHAR(200) NOT NULL,
    `userIdStr` VARCHAR(64) NOT NULL DEFAULT '',
    `username` VARCHAR(100) NOT NULL DEFAULT '',
    `userScreenName` VARCHAR(100) NOT NULL DEFAULT '',
    `source` enum('Twitter', 'Instagram') NOT NULL DEFAULT 'Twitter',
    `postCreatedAt` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'UTC Time when post was created',
    `coordinatesLongitude` VARCHAR(30) NOT NULL DEFAULT '',
    `coordinatesLatitude` VARCHAR(30) NOT NULL DEFAULT '',
    `place` VARCHAR(64) NOT NULL DEFAULT '',
    `countryCode` VARCHAR(2) NOT NULL DEFAULT '',
    `imageURL` VARCHAR(256) NOT NULL DEFAULT '',
    `imageThumbnail` VARCHAR(256) NOT NULL DEFAULT '',
    `lowResolutionImage` VARCHAR(256) NOT NULL DEFAULT '',
    `createdDateTime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'Date & Time when this record was created',
    `tsLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `projectId` (`projectId`),
    KEY `postIdStr` (`postIdStr`),
    KEY `source` (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for `postsMeta`
--
CREATE TABLE IF NOT EXISTS `postsMeta` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `projectId` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Id from projects table',
    `metaKey` VARCHAR(255) DEFAULT NULL,
    `metaValue` text,
    PRIMARY KEY (`id`),
    KEY `projectId` (`projectId`),
    KEY `metaKey` (`metaKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for `dataLog`
--
CREATE TABLE IF NOT EXISTS `dataLog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `projectId` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'Id from projects table',
  `description` varchar(255) NOT NULL COMMENT 'Human readable description of the purpose of this communication',
  `field1` text NOT NULL,
  `field2` text NOT NULL,
  `field3` text NOT NULL,
  `field4` text NOT NULL,
  `field5` text NOT NULL,
  `transport` enum('GET','POST','OAuth') NOT NULL COMMENT 'How the request values were sent/received',
  `origin` enum('Us','Them') NOT NULL DEFAULT 'Us' COMMENT 'Who originated this communiction?',
  `remoteHost` varchar(255) NOT NULL COMMENT 'IP Address or hostname of remote-side of communication',
  `requestValues` LONGTEXT NOT NULL,
  `responseValues` LONGTEXT NOT NULL,
  `status` enum('Succeeded','Failed') NOT NULL DEFAULT 'Succeeded' COMMENT 'Communication Status',
  `statusCode` varchar(20) NOT NULL COMMENT 'Protocol level status code (i.e. 200 for HTTP success)',
  `communicationDateTime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT 'datetime When this communication happened',
  `tsLastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `projectId` (`projectId`),
  KEY `communicationDateTime` (`communicationDateTime`),
  KEY `status` (`status`),
  KEY `statusCode` (`statusCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table for saving all communication logs, etc..';