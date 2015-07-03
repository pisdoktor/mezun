-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 03 Tem 2015, 19:17:16
-- Sunucu sürümü: 5.6.15-log
-- PHP Sürümü: 5.6.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Veritabanı: `mezun`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_branslar`
--

CREATE TABLE IF NOT EXISTS `deu_branslar` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

--
-- Tablo döküm verisi `deu_branslar`
--

INSERT INTO `deu_branslar` (`id`, `name`) VALUES
(1, 'Pratisyen'),
(2, 'Acil TÄ±p'),
(3, 'Adli TÄ±p'),
(4, 'Genel Cerrahi'),
(5, 'Plastik Cerrahi'),
(6, 'Pediatri'),
(7, 'Kardiyoloji'),
(8, 'Dermatoloji'),
(9, 'Aile HekimliÄŸi'),
(10, 'Kalp Damar Cerrahisi'),
(11, 'Anatomi'),
(12, 'TÄ±bbi Patoloji'),
(13, 'Fizyoloji'),
(14, 'Histoloji'),
(15, 'Beyin ve Sinir Cerrahisi'),
(16, 'Anesteziyoloji ve Reanimasyon'),
(17, 'Ã‡ocuk Cerrahisi'),
(18, 'Ã‡ocuk SaÄŸlÄ±ÄŸÄ± ve HastalÄ±klarÄ±'),
(19, 'Ã‡ocuk ve Ergen Ruh SaÄŸlÄ±ÄŸÄ± ve HastalÄ±klarÄ±'),
(20, 'Enfeksiyon HastalÄ±klarÄ± ve Klinik Mikrobiyoloji'),
(21, 'Fiziksel TÄ±p ve Rehabilitasyon'),
(22, 'GÃ¶ÄŸÃ¼s Cerrahisi'),
(23, 'GÃ¶ÄŸÃ¼s HastalÄ±klarÄ±'),
(24, 'GÃ¶z HastalÄ±klarÄ±'),
(25, 'Halk SaÄŸlÄ±ÄŸÄ±'),
(26, 'Histoloji ve Embriyoloji'),
(27, 'IÃ§ HastalÄ±klarÄ±'),
(28, 'KadÄ±n HastalÄ±klarÄ± ve DoÄŸum'),
(29, 'Kulak Burun BoÄŸaz HastalÄ±klarÄ±'),
(30, 'NÃ¶roloji'),
(31, 'NÃ¼kleer TÄ±p'),
(32, 'Ortopedi ve Travmatoloji'),
(33, 'Radyasyon Onkolojisi'),
(34, 'Radyoloji'),
(35, 'Ruh SaÄŸlÄ±ÄŸÄ± ve HastalÄ±klarÄ±'),
(36, 'Spor HekimliÄŸi'),
(37, 'SualtÄ± HekimliÄŸi ve Hiperbarik TÄ±p'),
(38, 'TÄ±bbi Biyokimya'),
(39, 'TÄ±bbi Farmakoloji'),
(40, 'TÄ±bbi Genetik'),
(41, 'TÄ±bbi Mikrobiyoloji'),
(42, 'Uroloji');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_duyurular`
--

CREATE TABLE IF NOT EXISTS `deu_duyurular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `tarih` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_boards`
--

CREATE TABLE IF NOT EXISTS `deu_forum_boards` (
  `ID_BOARD` smallint(5) NOT NULL AUTO_INCREMENT,
  `ID_CAT` tinyint(4) NOT NULL,
  `ID_PARENT` smallint(5) NOT NULL,
  `boardOrder` smallint(5) NOT NULL,
  `ID_LAST_MSG` int(11) NOT NULL,
  `ID_MSG_UPDATED` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `aciklama` text NOT NULL,
  `numTopics` mediumint(8) NOT NULL,
  `numPosts` mediumint(8) NOT NULL,
  `countPosts` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_BOARD`),
  UNIQUE KEY `categories` (`ID_CAT`,`ID_BOARD`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_categories`
--

CREATE TABLE IF NOT EXISTS `deu_forum_categories` (
  `ID_CAT` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `catOrder` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_CAT`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_log_boards`
--

CREATE TABLE IF NOT EXISTS `deu_forum_log_boards` (
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `ID_MSG` int(10) NOT NULL,
  PRIMARY KEY (`ID_MEMBER`,`ID_BOARD`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_log_mark_read`
--

CREATE TABLE IF NOT EXISTS `deu_forum_log_mark_read` (
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `ID_MSG` int(10) NOT NULL,
  PRIMARY KEY (`ID_MEMBER`,`ID_BOARD`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_log_topics`
--

CREATE TABLE IF NOT EXISTS `deu_forum_log_topics` (
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_TOPIC` mediumint(8) NOT NULL,
  `ID_MSG` int(10) NOT NULL,
  PRIMARY KEY (`ID_MEMBER`,`ID_TOPIC`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_messages`
--

CREATE TABLE IF NOT EXISTS `deu_forum_messages` (
  `ID_MSG` int(10) NOT NULL AUTO_INCREMENT,
  `ID_TOPIC` mediumint(8) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `posterTime` int(10) NOT NULL,
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_MSG_MODIFIED` int(10) NOT NULL,
  `posterIP` tinytext NOT NULL,
  `subject` tinytext NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`ID_MSG`),
  UNIQUE KEY `topic` (`ID_TOPIC`,`ID_MSG`),
  UNIQUE KEY `ID_BOARD` (`ID_BOARD`,`ID_MSG`),
  UNIQUE KEY `ID_MEMBER` (`ID_MEMBER`,`ID_MSG`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_forum_topics`
--

CREATE TABLE IF NOT EXISTS `deu_forum_topics` (
  `ID_TOPIC` mediumint(8) NOT NULL AUTO_INCREMENT,
  `isSticky` tinyint(4) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `ID_FIRST_MSG` int(10) NOT NULL,
  `ID_LAST_MSG` int(10) NOT NULL,
  `numReplies` int(10) NOT NULL,
  `numViews` int(10) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_TOPIC`),
  UNIQUE KEY `lastMessage` (`ID_LAST_MSG`,`ID_BOARD`),
  UNIQUE KEY `firstMessage` (`ID_FIRST_MSG`,`ID_BOARD`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_istekler`
--

CREATE TABLE IF NOT EXISTS `deu_istekler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(8) NOT NULL,
  `aid` int(8) NOT NULL,
  `tarih` datetime NOT NULL,
  `durum` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_mesajlar`
--

CREATE TABLE IF NOT EXISTS `deu_mesajlar` (
  `id` varchar(255) NOT NULL,
  `gid` int(8) NOT NULL,
  `aid` int(8) NOT NULL,
  `baslik` varchar(150) NOT NULL,
  `text` text NOT NULL,
  `tarih` datetime NOT NULL,
  `okunma` int(1) NOT NULL,
  `gsilinme` tinyint(1) NOT NULL,
  `asilinme` tinyint(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_sehirler`
--

CREATE TABLE IF NOT EXISTS `deu_sehirler` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=83 ;

--
-- Tablo döküm verisi `deu_sehirler`
--

INSERT INTO `deu_sehirler` (`id`, `name`) VALUES
(1, 'Adana'),
(2, 'AdÄ±yaman'),
(3, 'Afyonkarahisar'),
(4, 'AÄŸrÄ±'),
(5, 'Amasya'),
(6, 'Ankara'),
(7, 'Antalya'),
(8, 'Artvin'),
(9, 'AydÄ±n'),
(10, 'BalÄ±kesir'),
(11, 'Bilecik'),
(12, 'BingÃ¶l'),
(13, 'Bitlis'),
(14, 'Bolu'),
(15, 'Burdur'),
(16, 'Bursa'),
(17, 'Ã‡anakkale'),
(18, 'Ã‡ankÄ±rÄ±'),
(19, 'Ã‡orum'),
(20, 'Denizli'),
(21, 'DiyarbakÄ±r'),
(22, 'Edirne'),
(23, 'ElazÄ±ÄŸ'),
(24, 'Erzincan'),
(25, 'Erzurum'),
(26, 'EskiÅŸehir'),
(27, 'Gaziantep'),
(28, 'Giresun'),
(29, 'GÃ¼mÃ¼ÅŸhane'),
(30, 'Hakkari'),
(31, 'Hatay'),
(32, 'Isparta'),
(33, 'Mersin'),
(34, 'Ä°stanbul'),
(35, 'Ä°zmir'),
(36, 'Kars'),
(37, 'Kastamonu'),
(38, 'Kayseri'),
(39, 'KÄ±rklareli'),
(40, 'KÄ±rÅŸehir'),
(41, 'Kocaeli'),
(42, 'Konya'),
(43, 'KÃ¼tahya'),
(44, 'Malatya'),
(45, 'Manisa'),
(46, 'KahramanmaraÅŸ'),
(47, 'Mardin'),
(48, 'MuÄŸla'),
(49, 'MuÅŸ'),
(50, 'NevÅŸehir'),
(51, 'NiÄŸde'),
(52, 'Ordu'),
(53, 'Rize'),
(54, 'Sakarya'),
(55, 'Samsun'),
(56, 'Siirt'),
(57, 'Sinop'),
(58, 'Sivas'),
(59, 'TekirdaÄŸ'),
(60, 'Tokat'),
(61, 'Trabzon'),
(62, 'Tunceli'),
(63, 'ÅžanlÄ±urfa'),
(64, 'UÅŸak'),
(65, 'Van'),
(66, 'Yozgat'),
(67, 'Zonguldak'),
(68, 'Aksaray'),
(69, 'Bayburt'),
(70, 'Karaman'),
(71, 'KÄ±rÄ±kkale'),
(72, 'Batman'),
(73, 'ÅžÄ±rnak'),
(74, 'BartÄ±n'),
(75, 'Ardahan'),
(76, 'IÄŸdÄ±r'),
(77, 'Yalova'),
(78, 'KarabÃ¼k'),
(79, 'Kilis'),
(80, 'Osmaniye'),
(81, 'DÃ¼zce'),
(82, 'Yurt DÄ±ÅŸÄ±');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_sessions`
--

CREATE TABLE IF NOT EXISTS `deu_sessions` (
  `userid` int(8) NOT NULL,
  `username` varchar(150) NOT NULL,
  `time` varchar(150) NOT NULL,
  `session` varchar(255) NOT NULL,
  `access_type` varchar(25) NOT NULL,
  `nerede` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_stats`
--

CREATE TABLE IF NOT EXISTS `deu_stats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(300) DEFAULT NULL,
  `referer` varchar(300) DEFAULT NULL,
  `agent` varchar(100) DEFAULT NULL,
  `browser` varchar(200) NOT NULL,
  `os` varchar(200) NOT NULL,
  `domain` varchar(200) NOT NULL,
  `remote_add` varchar(30) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `referer_host` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_stats_blocklist`
--

CREATE TABLE IF NOT EXISTS `deu_stats_blocklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `block` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_stats_counts`
--

CREATE TABLE IF NOT EXISTS `deu_stats_counts` (
  `agent` varchar(300) CHARACTER SET utf8 NOT NULL,
  `type` tinyint(1) NOT NULL,
  `hits` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;


-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `deu_users`
--

CREATE TABLE IF NOT EXISTS `deu_users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(255) NOT NULL,
  `work` varchar(255) NOT NULL,
  `brans` int(3) NOT NULL,
  `unvan` varchar(20) NOT NULL,
  `byili` year(4) NOT NULL,
  `myili` year(4) NOT NULL,
  `okulno` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `sehir` int(3) NOT NULL,
  `dogumtarihi` varchar(10) NOT NULL,
  `dogumyeri` int(3) NOT NULL,
  `cinsiyet` tinyint(1) NOT NULL,
  `image` varchar(255) NOT NULL,
  `nowvisit` datetime NOT NULL,
  `lastvisit` datetime NOT NULL,
  `registerDate` datetime NOT NULL,
  `activated` tinyint(1) NOT NULL,
  `activation` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
