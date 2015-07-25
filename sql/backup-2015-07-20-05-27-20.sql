CREATE TABLE `deu_akis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tarih` datetime NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_branslar` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=43;

CREATE TABLE `deu_duyurular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `tarih` datetime NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_forum_boards` (
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
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_forum_categories` (
  `ID_CAT` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `catOrder` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_CAT`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_forum_log_boards` (
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `ID_MSG` int(10) NOT NULL,
  PRIMARY KEY (`ID_MEMBER`,`ID_BOARD`)
) TYPE=MyISAM;

CREATE TABLE `deu_forum_log_mark_read` (
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `ID_MSG` int(10) NOT NULL,
  PRIMARY KEY (`ID_MEMBER`,`ID_BOARD`)
) TYPE=MyISAM;

CREATE TABLE `deu_forum_log_topics` (
  `ID_MEMBER` mediumint(8) NOT NULL,
  `ID_TOPIC` mediumint(8) NOT NULL,
  `ID_MSG` int(10) NOT NULL,
  PRIMARY KEY (`ID_MEMBER`,`ID_TOPIC`)
) TYPE=MyISAM;

CREATE TABLE `deu_forum_messages` (
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
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_forum_topics` (
  `ID_TOPIC` mediumint(8) NOT NULL AUTO_INCREMENT,
  `icon` varchar(100) NOT NULL,
  `ID_BOARD` smallint(5) NOT NULL,
  `ID_FIRST_MSG` int(10) NOT NULL,
  `ID_LAST_MSG` int(10) NOT NULL,
  `numReplies` int(10) NOT NULL,
  `numViews` int(10) NOT NULL,
  `locked` tinyint(4) NOT NULL,
  `isSticky` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_TOPIC`),
  UNIQUE KEY `lastMessage` (`ID_LAST_MSG`,`ID_BOARD`),
  UNIQUE KEY `firstMessage` (`ID_FIRST_MSG`,`ID_BOARD`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_groups` (
  `id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `aciklama` tinytext NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `creator` int(8) NOT NULL,
  `creationdate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_groups_members` (
  `groupid` smallint(5) NOT NULL,
  `userid` smallint(5) NOT NULL,
  `isadmin` tinyint(1) NOT NULL,
  `joindate` datetime NOT NULL,
  KEY `usergroup` (`userid`,`groupid`)
) TYPE=MyISAM;

CREATE TABLE `deu_groups_messages` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `groupid` smallint(5) NOT NULL,
  `userid` smallint(5) NOT NULL,
  `text` text NOT NULL,
  `tarih` datetime NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_istekler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(8) NOT NULL,
  `aid` int(8) NOT NULL,
  `tarih` datetime NOT NULL,
  `durum` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_mesajlar` (
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
) TYPE=MyISAM;

CREATE TABLE `deu_sehirler` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=83;

CREATE TABLE `deu_sessions` (
  `userid` int(8) NOT NULL,
  `username` varchar(150) NOT NULL,
  `time` varchar(150) NOT NULL,
  `session` varchar(255) NOT NULL,
  `access_type` varchar(25) NOT NULL,
  `nerede` varchar(25) NOT NULL
) TYPE=MyISAM;

CREATE TABLE `deu_stats` (
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
) TYPE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `deu_stats_blocklist` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `block` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `deu_stats_counts` (
  `agent` varchar(300) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `hits` int(8) NOT NULL
) TYPE=MyISAM;

CREATE TABLE `deu_users` (
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
) TYPE=MyISAM AUTO_INCREMENT=1;

INSERT INTO `deu_branslar` VALUES ('1', 'Pratisyen');
INSERT INTO `deu_branslar` VALUES ('2', 'Acil Tıp');
INSERT INTO `deu_branslar` VALUES ('3', 'Adli Tıp');
INSERT INTO `deu_branslar` VALUES ('4', 'Genel Cerrahi');
INSERT INTO `deu_branslar` VALUES ('5', 'Plastik Cerrahi');
INSERT INTO `deu_branslar` VALUES ('6', 'Pediatri');
INSERT INTO `deu_branslar` VALUES ('7', 'Kardiyoloji');
INSERT INTO `deu_branslar` VALUES ('8', 'Dermatoloji');
INSERT INTO `deu_branslar` VALUES ('9', 'Aile Hekimliği');
INSERT INTO `deu_branslar` VALUES ('10', 'Kalp Damar Cerrahisi');
INSERT INTO `deu_branslar` VALUES ('11', 'Anatomi');
INSERT INTO `deu_branslar` VALUES ('12', 'Tıbbi Patoloji');
INSERT INTO `deu_branslar` VALUES ('13', 'Fizyoloji');
INSERT INTO `deu_branslar` VALUES ('14', 'Histoloji');
INSERT INTO `deu_branslar` VALUES ('15', 'Beyin ve Sinir Cerrahisi');
INSERT INTO `deu_branslar` VALUES ('16', 'Anesteziyoloji ve Reanimasyon');
INSERT INTO `deu_branslar` VALUES ('17', 'Çocuk Cerrahisi');
INSERT INTO `deu_branslar` VALUES ('18', 'Çocuk Sağlığı ve Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('19', 'Çocuk ve Ergen Ruh Sağlığı ve Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('20', 'Enfeksiyon Hastalıkları ve Klinik Mikrobiyoloji');
INSERT INTO `deu_branslar` VALUES ('21', 'Fiziksel Tıp ve Rehabilitasyon');
INSERT INTO `deu_branslar` VALUES ('22', 'Göğüs Cerrahisi');
INSERT INTO `deu_branslar` VALUES ('23', 'Göğüs Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('24', 'Göz Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('25', 'Halk Sağlığı');
INSERT INTO `deu_branslar` VALUES ('26', 'Histoloji ve Embriyoloji');
INSERT INTO `deu_branslar` VALUES ('27', 'Iç Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('28', 'Kadın Hastalıkları ve Doğum');
INSERT INTO `deu_branslar` VALUES ('29', 'Kulak Burun Boğaz Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('30', 'Nöroloji');
INSERT INTO `deu_branslar` VALUES ('31', 'Nükleer Tıp');
INSERT INTO `deu_branslar` VALUES ('32', 'Ortopedi ve Travmatoloji');
INSERT INTO `deu_branslar` VALUES ('33', 'Radyasyon Onkolojisi');
INSERT INTO `deu_branslar` VALUES ('34', 'Radyoloji');
INSERT INTO `deu_branslar` VALUES ('35', 'Ruh Sağlığı ve Hastalıkları');
INSERT INTO `deu_branslar` VALUES ('36', 'Spor Hekimliği');
INSERT INTO `deu_branslar` VALUES ('37', 'Sualtı Hekimliği ve Hiperbarik Tıp');
INSERT INTO `deu_branslar` VALUES ('38', 'Tıbbi Biyokimya');
INSERT INTO `deu_branslar` VALUES ('39', 'Tıbbi Farmakoloji');
INSERT INTO `deu_branslar` VALUES ('40', 'Tıbbi Genetik');
INSERT INTO `deu_branslar` VALUES ('41', 'Tıbbi Mikrobiyoloji');
INSERT INTO `deu_branslar` VALUES ('42', 'Uroloji');


INSERT INTO `deu_sehirler` VALUES ('1', 'Adana');
INSERT INTO `deu_sehirler` VALUES ('2', 'Adıyaman');
INSERT INTO `deu_sehirler` VALUES ('3', 'Afyonkarahisar');
INSERT INTO `deu_sehirler` VALUES ('4', 'Ağrı');
INSERT INTO `deu_sehirler` VALUES ('5', 'Amasya');
INSERT INTO `deu_sehirler` VALUES ('6', 'Ankara');
INSERT INTO `deu_sehirler` VALUES ('7', 'Antalya');
INSERT INTO `deu_sehirler` VALUES ('8', 'Artvin');
INSERT INTO `deu_sehirler` VALUES ('9', 'Aydın');
INSERT INTO `deu_sehirler` VALUES ('10', 'Balıkesir');
INSERT INTO `deu_sehirler` VALUES ('11', 'Bilecik');
INSERT INTO `deu_sehirler` VALUES ('12', 'Bingöl');
INSERT INTO `deu_sehirler` VALUES ('13', 'Bitlis');
INSERT INTO `deu_sehirler` VALUES ('14', 'Bolu');
INSERT INTO `deu_sehirler` VALUES ('15', 'Burdur');
INSERT INTO `deu_sehirler` VALUES ('16', 'Bursa');
INSERT INTO `deu_sehirler` VALUES ('17', 'Çanakkale');
INSERT INTO `deu_sehirler` VALUES ('18', 'Çankırı');
INSERT INTO `deu_sehirler` VALUES ('19', 'Çorum');
INSERT INTO `deu_sehirler` VALUES ('20', 'Denizli');
INSERT INTO `deu_sehirler` VALUES ('21', 'Diyarbakır');
INSERT INTO `deu_sehirler` VALUES ('22', 'Edirne');
INSERT INTO `deu_sehirler` VALUES ('23', 'Elazığ');
INSERT INTO `deu_sehirler` VALUES ('24', 'Erzincan');
INSERT INTO `deu_sehirler` VALUES ('25', 'Erzurum');
INSERT INTO `deu_sehirler` VALUES ('26', 'Eskişehir');
INSERT INTO `deu_sehirler` VALUES ('27', 'Gaziantep');
INSERT INTO `deu_sehirler` VALUES ('28', 'Giresun');
INSERT INTO `deu_sehirler` VALUES ('29', 'Gümüşhane');
INSERT INTO `deu_sehirler` VALUES ('30', 'Hakkari');
INSERT INTO `deu_sehirler` VALUES ('31', 'Hatay');
INSERT INTO `deu_sehirler` VALUES ('32', 'Isparta');
INSERT INTO `deu_sehirler` VALUES ('33', 'Mersin');
INSERT INTO `deu_sehirler` VALUES ('34', 'İstanbul');
INSERT INTO `deu_sehirler` VALUES ('35', 'İzmir');
INSERT INTO `deu_sehirler` VALUES ('36', 'Kars');
INSERT INTO `deu_sehirler` VALUES ('37', 'Kastamonu');
INSERT INTO `deu_sehirler` VALUES ('38', 'Kayseri');
INSERT INTO `deu_sehirler` VALUES ('39', 'Kırklareli');
INSERT INTO `deu_sehirler` VALUES ('40', 'Kırşehir');
INSERT INTO `deu_sehirler` VALUES ('41', 'Kocaeli');
INSERT INTO `deu_sehirler` VALUES ('42', 'Konya');
INSERT INTO `deu_sehirler` VALUES ('43', 'Kütahya');
INSERT INTO `deu_sehirler` VALUES ('44', 'Malatya');
INSERT INTO `deu_sehirler` VALUES ('45', 'Manisa');
INSERT INTO `deu_sehirler` VALUES ('46', 'Kahramanmaraş');
INSERT INTO `deu_sehirler` VALUES ('47', 'Mardin');
INSERT INTO `deu_sehirler` VALUES ('48', 'Muğla');
INSERT INTO `deu_sehirler` VALUES ('49', 'Muş');
INSERT INTO `deu_sehirler` VALUES ('50', 'Nevşehir');
INSERT INTO `deu_sehirler` VALUES ('51', 'Niğde');
INSERT INTO `deu_sehirler` VALUES ('52', 'Ordu');
INSERT INTO `deu_sehirler` VALUES ('53', 'Rize');
INSERT INTO `deu_sehirler` VALUES ('54', 'Sakarya');
INSERT INTO `deu_sehirler` VALUES ('55', 'Samsun');
INSERT INTO `deu_sehirler` VALUES ('56', 'Siirt');
INSERT INTO `deu_sehirler` VALUES ('57', 'Sinop');
INSERT INTO `deu_sehirler` VALUES ('58', 'Sivas');
INSERT INTO `deu_sehirler` VALUES ('59', 'Tekirdağ');
INSERT INTO `deu_sehirler` VALUES ('60', 'Tokat');
INSERT INTO `deu_sehirler` VALUES ('61', 'Trabzon');
INSERT INTO `deu_sehirler` VALUES ('62', 'Tunceli');
INSERT INTO `deu_sehirler` VALUES ('63', 'Şanlıurfa');
INSERT INTO `deu_sehirler` VALUES ('64', 'Uşak');
INSERT INTO `deu_sehirler` VALUES ('65', 'Van');
INSERT INTO `deu_sehirler` VALUES ('66', 'Yozgat');
INSERT INTO `deu_sehirler` VALUES ('67', 'Zonguldak');
INSERT INTO `deu_sehirler` VALUES ('68', 'Aksaray');
INSERT INTO `deu_sehirler` VALUES ('69', 'Bayburt');
INSERT INTO `deu_sehirler` VALUES ('70', 'Karaman');
INSERT INTO `deu_sehirler` VALUES ('71', 'Kırıkkale');
INSERT INTO `deu_sehirler` VALUES ('72', 'Batman');
INSERT INTO `deu_sehirler` VALUES ('73', 'Şırnak');
INSERT INTO `deu_sehirler` VALUES ('74', 'Bartın');
INSERT INTO `deu_sehirler` VALUES ('75', 'Ardahan');
INSERT INTO `deu_sehirler` VALUES ('76', 'Iğdır');
INSERT INTO `deu_sehirler` VALUES ('77', 'Yalova');
INSERT INTO `deu_sehirler` VALUES ('78', 'Karabük');
INSERT INTO `deu_sehirler` VALUES ('79', 'Kilis');
INSERT INTO `deu_sehirler` VALUES ('80', 'Osmaniye');
INSERT INTO `deu_sehirler` VALUES ('81', 'Düzce');
INSERT INTO `deu_sehirler` VALUES ('82', 'Yurt Dışı');


INSERT INTO `deu_users` VALUES ('1', 'Soner Ekici', 'admin', 'sonerekici@gmail.com', '1796dcaa1dcfb34b545d491c11d15d3b:jHYuDPc2BvZ9XFgt', 'Babadağ Toplum Sağlığı Merkezi', '1', 'Dr', '1998', '2006', '1998105039', '0 (554) 857 77 79', '20', '30-07-1981', '20', '1', '', '2015-07-20 16:48:10', '2015-07-20 12:39:33', '2015-06-13 00:00:00', '1', '');