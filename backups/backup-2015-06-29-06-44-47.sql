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
) TYPE=MyISAM AUTO_INCREMENT=2;

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
) TYPE=MyISAM AUTO_INCREMENT=8;

CREATE TABLE `deu_forum_categories` (
  `ID_CAT` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `catOrder` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID_CAT`)
) TYPE=MyISAM AUTO_INCREMENT=5;

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
) TYPE=MyISAM AUTO_INCREMENT=62;

CREATE TABLE `deu_forum_topics` (
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
) TYPE=MyISAM AUTO_INCREMENT=17;

CREATE TABLE `deu_istekler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(8) NOT NULL,
  `aid` int(8) NOT NULL,
  `tarih` datetime NOT NULL,
  `durum` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM AUTO_INCREMENT=8;

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
  `agent` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `hits` int(11) NOT NULL
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
) TYPE=MyISAM AUTO_INCREMENT=14;

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

INSERT INTO `deu_duyurular` VALUES ('1', 'fhf ghgfh gfh gfh', '2015-06-29 16:46:52');

INSERT INTO `deu_forum_boards` VALUES ('1', '1', '0', '1', '61', '61', 'Forum 1', 'Forum 1 Açıklaması', '11', '53', '0');
INSERT INTO `deu_forum_boards` VALUES ('2', '1', '0', '2', '6', '6', 'Forum 1 Alt Kategorisi', 'Forum 1 Alt Kategorisi Açıklaması', '1', '1', '0');
INSERT INTO `deu_forum_boards` VALUES ('3', '2', '0', '1', '0', '0', 'Forum 2', 'Forum 2 Açıklaması', '0', '0', '0');
INSERT INTO `deu_forum_boards` VALUES ('4', '2', '3', '2', '56', '56', 'Forum 1 Alt 2 Kategorisi', 'Forum 1 Alt 2 Kategorisi Açıklaması', '1', '3', '0');
INSERT INTO `deu_forum_boards` VALUES ('5', '3', '0', '1', '40', '40', 'Ağaçören', '', '1', '1', '0');
INSERT INTO `deu_forum_boards` VALUES ('6', '4', '0', '1', '41', '41', 'İl Yöneticisi', '', '1', '1', '0');
INSERT INTO `deu_forum_boards` VALUES ('7', '1', '1', '3', '57', '57', 'Adıyaman', 'Guzel sehirdir beaaa', '1', '2', '0');

INSERT INTO `deu_forum_categories` VALUES ('1', 'Paylaşımlar', '1');
INSERT INTO `deu_forum_categories` VALUES ('2', 'Genel Muhabbet', '2');
INSERT INTO `deu_forum_categories` VALUES ('3', 'Mobil', '3');
INSERT INTO `deu_forum_categories` VALUES ('4', 'Merkez', '4');

INSERT INTO `deu_forum_log_boards` VALUES ('2', '1', '61');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '4', '56');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '1', '61');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '2', '40');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '2', '56');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '3', '40');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '4', '40');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '5', '40');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '7', '58');
INSERT INTO `deu_forum_log_boards` VALUES ('10', '1', '41');
INSERT INTO `deu_forum_log_boards` VALUES ('10', '2', '40');
INSERT INTO `deu_forum_log_boards` VALUES ('10', '5', '40');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '5', '44');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '7', '61');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '6', '56');
INSERT INTO `deu_forum_log_boards` VALUES ('10', '6', '41');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '3', '56');
INSERT INTO `deu_forum_log_boards` VALUES ('2', '0', '61');
INSERT INTO `deu_forum_log_boards` VALUES ('1', '6', '56');


INSERT INTO `deu_forum_log_topics` VALUES ('2', '1', '61');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '2', '56');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '1', '3');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '3', '4');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '4', '5');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '5', '6');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '4', '60');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '3', '58');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '2', '39');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '6', '40');
INSERT INTO `deu_forum_log_topics` VALUES ('10', '4', '41');
INSERT INTO `deu_forum_log_topics` VALUES ('10', '5', '40');
INSERT INTO `deu_forum_log_topics` VALUES ('10', '6', '40');
INSERT INTO `deu_forum_log_topics` VALUES ('10', '3', '40');
INSERT INTO `deu_forum_log_topics` VALUES ('10', '1', '40');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '6', '40');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '7', '56');
INSERT INTO `deu_forum_log_topics` VALUES ('10', '7', '41');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '8', '44');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '9', '45');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '10', '46');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '11', '47');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '12', '59');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '13', '61');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '14', '58');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '15', '56');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '5', '51');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '16', '58');
INSERT INTO `deu_forum_log_topics` VALUES ('2', '17', '54');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '7', '56');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '15', '56');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '16', '57');
INSERT INTO `deu_forum_log_topics` VALUES ('1', '14', '58');

INSERT INTO `deu_forum_messages` VALUES ('1', '1', '1', '1434705960', '2', '1', '127.0.0.1', 'Bu yeni bir başlık', 'sdfdsf dsfsdf dsfdsf sfsd fsdf sdf s');
INSERT INTO `deu_forum_messages` VALUES ('2', '2', '4', '1434707684', '2', '2', '127.0.0.1', '11 bakalım', 'cvvbcbcvbcvbcvb');
INSERT INTO `deu_forum_messages` VALUES ('3', '1', '1', '1434707941', '2', '0', '127.0.0.1', 'Cvp:Bu yeni bir başlık', 'evet  öfdfgdfgdfgdf');
INSERT INTO `deu_forum_messages` VALUES ('4', '3', '1', '1434708430', '1', '4', '127.0.0.1', 'Test Yapışkan ve Kilitli', 'Doldur doldurrrrr');
INSERT INTO `deu_forum_messages` VALUES ('5', '4', '1', '1434708534', '1', '5', '127.0.0.1', 'TEkrar deneme', 'deneme yapalım');
INSERT INTO `deu_forum_messages` VALUES ('6', '5', '2', '1434708643', '1', '6', '127.0.0.1', 'f fg dfg dfgdfg', 'dsfdsfdsfdsf');
INSERT INTO `deu_forum_messages` VALUES ('7', '4', '1', '1434715870', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dsfdsfds fsdfds f');
INSERT INTO `deu_forum_messages` VALUES ('8', '4', '1', '1434715875', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'df sdfdsf dsfsdfdsf ');
INSERT INTO `deu_forum_messages` VALUES ('9', '4', '1', '1434715879', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'df dsfdsf sdf sdfdsf');
INSERT INTO `deu_forum_messages` VALUES ('10', '4', '1', '1434715883', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dfs dfdsf sdf dsf sd');
INSERT INTO `deu_forum_messages` VALUES ('11', '4', '1', '1434715888', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'fs fdsf sdf sdfsdf ');
INSERT INTO `deu_forum_messages` VALUES ('12', '4', '1', '1434715892', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dfsd fdsf dsfds sdfdsf');
INSERT INTO `deu_forum_messages` VALUES ('13', '4', '1', '1434715898', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'sdf sdfdsf sdfdsfsdfsfsddsf');
INSERT INTO `deu_forum_messages` VALUES ('14', '4', '1', '1434715902', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dfs sfdsf dsf dsf dsfsd fdsf');
INSERT INTO `deu_forum_messages` VALUES ('15', '4', '1', '1434715906', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'fsd fdsfdsf');
INSERT INTO `deu_forum_messages` VALUES ('16', '4', '1', '1434715910', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'fghfh fghfgh fgh');
INSERT INTO `deu_forum_messages` VALUES ('17', '4', '1', '1434715932', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dsa das dasdasdasd');
INSERT INTO `deu_forum_messages` VALUES ('18', '4', '1', '1434717841', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dfsdfdsf dsf dsf');
INSERT INTO `deu_forum_messages` VALUES ('19', '4', '1', '1434717846', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'sdfsfdsfdsfdsf');
INSERT INTO `deu_forum_messages` VALUES ('20', '4', '1', '1434717852', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'dfs fs dfdsf hjkhj khjk h');
INSERT INTO `deu_forum_messages` VALUES ('21', '4', '1', '1434717859', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'ghk jkhjk jhk');
INSERT INTO `deu_forum_messages` VALUES ('22', '4', '1', '1434717865', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'vbc bcvb cbcvb cbcvb c');
INSERT INTO `deu_forum_messages` VALUES ('23', '4', '1', '1434717871', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'cvb cvbcvbcvb cvbcv ');
INSERT INTO `deu_forum_messages` VALUES ('24', '4', '1', '1434717876', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'cvb cbcvbcvb cbcvbc ');
INSERT INTO `deu_forum_messages` VALUES ('25', '4', '1', '1434717882', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'cvb cb cvb cvbcvbc c cvb');
INSERT INTO `deu_forum_messages` VALUES ('26', '4', '1', '1434717888', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'vb cvbcvb cb cb cv');
INSERT INTO `deu_forum_messages` VALUES ('27', '4', '1', '1434717894', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'vcb cbcvb cb cvb cvb cvb');
INSERT INTO `deu_forum_messages` VALUES ('28', '4', '1', '1434717899', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'cvb cvb cvb cvb');
INSERT INTO `deu_forum_messages` VALUES ('29', '3', '1', '1434719127', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'sdfdsf sdf sd fsdf ds fsf');
INSERT INTO `deu_forum_messages` VALUES ('30', '3', '1', '1434719148', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'dfgd dfg vnbvnbvnv');
INSERT INTO `deu_forum_messages` VALUES ('31', '3', '1', '1434719153', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'vbnv bnbvn vbn vbn vb');
INSERT INTO `deu_forum_messages` VALUES ('32', '3', '1', '1434719157', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'vbnvbn vbnvbn vbn');
INSERT INTO `deu_forum_messages` VALUES ('33', '3', '1', '1434719163', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'bvn vbnvnvbn vbnvbn');
INSERT INTO `deu_forum_messages` VALUES ('34', '3', '1', '1434719168', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'bnvbnvbn vbn bvn bvn');
INSERT INTO `deu_forum_messages` VALUES ('35', '3', '1', '1434719173', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'bvnbvnvn vbn vbn vn');
INSERT INTO `deu_forum_messages` VALUES ('36', '3', '1', '1434719178', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'bvn vbn vnbvn b');
INSERT INTO `deu_forum_messages` VALUES ('37', '3', '1', '1434719184', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'vbn vbn vbnbvn bvn v');
INSERT INTO `deu_forum_messages` VALUES ('38', '3', '1', '1434719189', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'bvn vbn vbn bvn vbnbvn');
INSERT INTO `deu_forum_messages` VALUES ('39', '3', '1', '1434719196', '2', '0', '127.0.0.1', 'Cvp:Test Yapışkan ve Kilitli', 'bvn vbnbvn bvnbvn vbn');
INSERT INTO `deu_forum_messages` VALUES ('40', '6', '5', '1434755533', '1', '40', '127.0.0.1', 'Kilitli mesaj', 'mesajımızı yazalım');
INSERT INTO `deu_forum_messages` VALUES ('41', '7', '6', '1434966054', '2', '41', '127.0.0.1', 'test', 'test');
INSERT INTO `deu_forum_messages` VALUES ('42', '8', '1', '1435054642', '2', '42', '127.0.0.1', '5656756657', '6757567567');
INSERT INTO `deu_forum_messages` VALUES ('43', '8', '1', '1435054661', '2', '0', '127.0.0.1', 'Cvp:5656756657', 'fdf dsfsdfdsfsdfsdf');
INSERT INTO `deu_forum_messages` VALUES ('44', '8', '1', '1435054671', '2', '0', '127.0.0.1', 'Cvp: fdg dfgdfg dfgdfdfg d', 'dfg dfgdf gdfg');
INSERT INTO `deu_forum_messages` VALUES ('45', '9', '1', '1435061862', '2', '45', '127.0.0.1', 'Bu yeni bir başlık', 'dfdsfdsfdf');
INSERT INTO `deu_forum_messages` VALUES ('46', '10', '1', '1435061878', '2', '46', '127.0.0.1', 'vdfdsds', 'dsfdsfsfsfsdf');
INSERT INTO `deu_forum_messages` VALUES ('47', '11', '1', '1435061892', '2', '47', '127.0.0.1', 'sdfsfdsf', 'dsfdsfdsfdsfdsf');
INSERT INTO `deu_forum_messages` VALUES ('48', '12', '1', '1435061908', '2', '48', '127.0.0.1', 'nbmmbmbnm', 'bnmbnmbnmbmbnmbn');
INSERT INTO `deu_forum_messages` VALUES ('49', '13', '1', '1435061924', '2', '49', '127.0.0.1', 'dvxcvcxvxc', 'vcxvcxvcxv');
INSERT INTO `deu_forum_messages` VALUES ('50', '14', '1', '1435061942', '2', '50', '127.0.0.1', '9890890', 'fdgdfg dfg dfg');
INSERT INTO `deu_forum_messages` VALUES ('51', '15', '1', '1435061973', '2', '51', '127.0.0.1', 'bncxcxvdfs gfgfhg', 'szwqecgcv hgchg  gfhvhgvh hgvhv');
INSERT INTO `deu_forum_messages` VALUES ('52', '4', '1', '1435066510', '2', '0', '127.0.0.1', 'Cvp:deneme', 'dfdsfdsfs');
INSERT INTO `deu_forum_messages` VALUES ('53', '15', '1', '1435066968', '2', '0', '127.0.0.1', 'Cvp:bncxcxvdfs gfgfhg', 'fdsf dsf sdf dsfdsf dsf');
INSERT INTO `deu_forum_messages` VALUES ('54', '16', '7', '1435067090', '2', '54', '127.0.0.1', 'herşey güzel olacak', 'Yinelenen bir sayfa içeriğinin okuyucunun dikkatini dağıttığı bilinen bir gerçektir. Lorem Ipsum kullanmanın amacı, sürekli \'buraya metin gelecek, buraya metin gelecek\' yazmaya kıyasla daha dengeli bir harf dağılımı sağlayarak okunurluğu artırmasıdır. Şu anda birçok masaüstü yayıncılık paketi ve web sayfa düzenleyicisi, varsayılan mıgır metinler olarak Lorem Ipsum kullanmaktadır. Ayrıca arama motorlarında \'lorem ipsum\' anahtar sözcükleri ile arama yapıldığında henüz tasarım aşamasında olan çok sayıda site listelenir. Yıllar içinde, bazen kazara, bazen bilinçli olarak (örneğin mizah katılarak), çeşitli sürümleri geliştirilmiştir.');
INSERT INTO `deu_forum_messages` VALUES ('55', '2', '4', '1435067346', '2', '0', '127.0.0.1', 'Cvp:11 bakalım', 'Yinelenen bir sayfa içeriğinin okuyucunun dikkatini dağıttığı bilinen bir gerçektir. Lorem Ipsum kullanmanın amacı, sürekli \'buraya metin gelecek, buraya metin gelecek\' yazmaya kıyasla daha dengeli bir harf dağılımı sağlayarak okunurluğu artırmasıdır. Şu anda birçok masaüstü yayıncılık paketi ve web sayfa düzenleyicisi, varsayılan mıgır metinler olarak Lorem Ipsum kullanmaktadır. Ayrıca arama motorlarında \'lorem ipsum\' anahtar sözcükleri ile arama yapıldığında henüz tasarım aşamasında olan çok sayıda site listelenir. Yıllar içinde, bazen kazara, bazen bilinçli olarak (örneğin mizah katılarak), çeşitli sürümleri geliştirilmiştir.');
INSERT INTO `deu_forum_messages` VALUES ('56', '2', '4', '1435067390', '2', '0', '127.0.0.1', 'Cvp:11 bakalım', 'Yinelenen bir sayfa içeriğinin okuyucunun dikkatini dağıttığı bilinen bir gerçektir. Lorem Ipsum kullanmanın amacı, sürekli \'buraya metin gelecek, buraya metin gelecek\' yazmaya kıyasla daha dengeli bir harf dağılımı sağlayarak okunurluğu artırmasıdır. Şu anda birçok masaüstü yayıncılık paketi ve web sayfa düzenleyicisi, varsayılan mıgır metinler olarak Lorem Ipsum kullanmaktadır. Ayrıca arama motorlarında \'lorem ipsum\' anahtar sözcükleri ile arama yapıldığında henüz tasarım aşamasında olan çok sayıda site listelenir. Yıllar içinde, bazen kazara, bazen bilinçli olarak (örneğin mizah katılarak), çeşitli sürümleri geliştirilmiştir.');
INSERT INTO `deu_forum_messages` VALUES ('57', '16', '7', '1435306901', '1', '0', '127.0.0.1', 'Cvp:herşey güzel olacak', 'Yaygın inancın tersine, Lorem Ipsum rastgele sözcüklerden oluşmaz. Kökleri M.Ö. 45 tarihinden bu yana klasik Latin edebiyatına kadar uzanan 2000 yıllık bir geçmişi vardır. Virginia\'daki Hampden-Sydney College\'dan Latince profesörü Richard McClintock, bir Lorem Ipsum pasajında geçen ve anlaşılması en güç sözcüklerden biri olan \'consectetur\' sözcüğünün klasik edebiyattaki örneklerini incelediğinde kesin bir kaynağa ulaşmıştır. Lorm Ipsum, Çiçero tarafından M.Ö. 45 tarihinde kaleme alınan \"de Finibus Bonorum et Malorum\" (İyi ve Kötünün Uç Sınırları) eserinin 1.10.32 ve 1.10.33 sayılı bölümlerinden gelmektedir. Bu kitap, ahlak kuramı üzerine bir tezdir ve Rönesans döneminde çok popüler olmuştur. Lorem Ipsum pasajının ilk satırı olan \"Lorem ipsum dolor sit amet\" 1.10.32 sayılı bölümdeki bir satırdan gelmektedir.');
INSERT INTO `deu_forum_messages` VALUES ('58', '14', '1', '1435306942', '1', '0', '127.0.0.1', 'Cvp:9890890', '\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec ac aliquam ante. Duis non turpis ultricies, volutpat orci non, mattis lacus. Ut pretium rutrum elit, vitae vestibulum lorem facilisis ut. Nullam viverra blandit nunc eu euismod. Nullam bibendum erat id augue fringilla aliquet. Pellentesque egestas malesuada odio, at ultrices mi posuere et. In ex lacus, mattis non diam quis, consequat pellentesque orci. Integer in varius nisi, ac vestibulum ipsum. Aliquam pharetra, tortor non volutpat placerat, turpis orci accumsan orci, a ultricies massa ipsum sit amet nulla. Proin tincidunt dignissim dui vitae faucibus.\r\n\r\nNullam consequat odio sit amet metus sodales, nec feugiat lorem tincidunt. Aliquam sollicitudin pulvinar dolor, efficitur dignissim felis congue ut. In vehicula, sem et euismod mattis, turpis mauris porta erat, ut lacinia metus massa vitae quam. Ut ultricies, orci sit amet pretium condimentum, enim diam laoreet ante, at condimentum elit nisl quis arcu. Praesent quis augue tellus. Phasellus vitae lectus libero. Cras sit amet tellus sapien. Mauris porta magna ac condimentum hendrerit. Proin tempus vel ex ut ultricies.\r\n\r\nSuspendisse potenti. Donec tempus blandit risus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ac enim vitae leo dictum faucibus. Donec viverra nisi non diam cursus vehicula. Nullam et facilisis purus. Proin libero justo, maximus ac gravida id, posuere non odio. Maecenas non congue augue, quis accumsan elit. In posuere bibendum dignissim. Nullam id dolor orci. Aliquam nisl purus, ultricies sit amet augue ut, rutrum viverra risus.\r\n\r\nEtiam vulputate, ligula a iaculis vestibulum, ante odio interdum ipsum, euismod facilisis mauris diam vestibulum nunc. In iaculis ut lorem id maximus. Sed dictum ultricies eros, non tempus purus congue sit amet. Mauris suscipit, quam eu blandit egestas, ligula enim varius ipsum, vitae scelerisque metus neque id diam. Quisque gravida tortor et rhoncus tempus. Praesent laoreet consectetur magna, a accumsan leo facilisis sed. Pellentesque et libero massa.\r\n\r\nMauris volutpat venenatis ex, tempus sagittis neque placerat aliquet. Vivamus et diam auctor, suscipit sapien vitae, finibus nisl. Maecenas elementum feugiat felis ut ultricies. Proin eget ipsum felis. Sed et maximus tortor. Nunc eu vulputate dui, ac sodales nisl. Morbi vitae massa sed ex mollis sagittis. ');
INSERT INTO `deu_forum_messages` VALUES ('59', '4', '1', '1435323773', '2', '0', '127.0.0.1', 'Cvp:TEkrar deneme', 'gdfg dfhgfhgfh fgh g');
INSERT INTO `deu_forum_messages` VALUES ('60', '4', '1', '1435564390', '2', '60', '127.0.0.1', 'Cvp:TEkrar deneme', 'deneme mesajı');
INSERT INTO `deu_forum_messages` VALUES ('61', '13', '1', '1435564428', '2', '61', '127.0.0.1', 'Cvp:dvxcvcxvxc', 'deneme');

INSERT INTO `deu_forum_topics` VALUES ('1', '0', '1', '1', '3', '1', '8', '1');
INSERT INTO `deu_forum_topics` VALUES ('2', '0', '4', '2', '56', '2', '5', '0');
INSERT INTO `deu_forum_topics` VALUES ('3', '0', '1', '4', '39', '11', '5', '0');
INSERT INTO `deu_forum_topics` VALUES ('4', '1', '1', '5', '60', '25', '17', '0');
INSERT INTO `deu_forum_topics` VALUES ('5', '0', '2', '6', '6', '0', '3', '1');
INSERT INTO `deu_forum_topics` VALUES ('6', '0', '5', '40', '40', '0', '3', '1');
INSERT INTO `deu_forum_topics` VALUES ('7', '0', '6', '41', '41', '0', '4', '0');
INSERT INTO `deu_forum_topics` VALUES ('8', '0', '1', '42', '44', '2', '1', '0');
INSERT INTO `deu_forum_topics` VALUES ('9', '0', '1', '45', '45', '0', '1', '0');
INSERT INTO `deu_forum_topics` VALUES ('10', '0', '1', '46', '46', '0', '1', '0');
INSERT INTO `deu_forum_topics` VALUES ('11', '0', '1', '47', '47', '0', '1', '0');
INSERT INTO `deu_forum_topics` VALUES ('12', '0', '1', '48', '48', '0', '2', '0');
INSERT INTO `deu_forum_topics` VALUES ('13', '0', '1', '49', '61', '1', '2', '0');
INSERT INTO `deu_forum_topics` VALUES ('14', '0', '1', '50', '58', '1', '3', '0');
INSERT INTO `deu_forum_topics` VALUES ('15', '0', '1', '51', '53', '1', '6', '0');
INSERT INTO `deu_forum_topics` VALUES ('16', '0', '7', '54', '57', '1', '4', '0');

INSERT INTO `deu_istekler` VALUES ('1', '2', '10', '2015-06-23 11:23:44', '1');
INSERT INTO `deu_istekler` VALUES ('2', '2', '4', '2015-06-23 11:23:51', '1');
INSERT INTO `deu_istekler` VALUES ('3', '2', '3', '2015-06-23 11:23:57', '1');
INSERT INTO `deu_istekler` VALUES ('4', '2', '1', '2015-06-23 11:24:03', '1');
INSERT INTO `deu_istekler` VALUES ('7', '1', '3', '2015-06-26 10:09:47', '0');
INSERT INTO `deu_istekler` VALUES ('6', '4', '1', '2015-06-22 06:23:24', '1');

INSERT INTO `deu_mesajlar` VALUES ('ifJyRrWNecIeCbHaKwrqMJcFglxHsem3TVii8NxEET87LeGlJGWMqN1ZBpMagmdj7t3WQW1BacVFYVldiYQJkgNV78GXebTFqerxBe1NSaH9QzCIGNL8BIi2U4lFcbPVVNx787jBtX5YUPdkmkRvDQ5yyPBT5uKStLTb6qIPRSbyjYui3gx3tj9r4HVTkLkJPSymTX7yZ86rpsXNb9eyTWu1xtAZPB9Pxn23RXbJy2pn6TrBZT', '2', '1', 'RGVuZW1lIEJhxZ9sxLHEn8Sx', 'WWluZWxlbmVuIGJpciBzYXlmYSBpw6dlcmnEn2luaW4gb2t1eXVjdW51biBkaWtrYXRpbmkgZGHEn8SxdHTEscSfxLEgYmlsaW5lbiBiaXIgZ2Vyw6dla3Rpci4gTG9yZW0gSXBzdW0ga3VsbGFubWFuxLFuIGFtYWPEsSwgc8O8cmVrbGkgJ2J1cmF5YSBtZXRpbiBnZWxlY2VrLCBidXJheWEgbWV0aW4gZ2VsZWNlaycgeWF6bWF5YSBrxLF5YXNsYSBkYWhhIGRlbmdlbGkgYmlyIGhhcmYgZGHEn8SxbMSxbcSxIHNhxJ9sYXlhcmFrIG9rdW51cmx1xJ91IGFydMSxcm1hc8SxZMSxci4gxZ51IGFuZGEgYmlyw6dvayBtYXNhw7xzdMO8IHlhecSxbmPEsWzEsWsgcGFrZXRpIHZlIHdlYiBzYXlmYSBkw7x6ZW5sZXlpY2lzaSwgdmFyc2F5xLFsYW4gbcSxZ8SxciBtZXRpbmxlciBvbGFyYWsgTG9yZW0gSXBzdW0ga3VsbGFubWFrdGFkxLFyLg==', '2015-06-26 10:00:21', '1', '0', '0');
INSERT INTO `deu_mesajlar` VALUES ('y9LpJFa1jdgIcVInxSPcLXkfDHE5cLpnf7H988mvxnVWM9H5aYN7vNf5XFNPrwsETWYIXscL8jrd8MeML1cNUvD6bQrdH9qxNR7YVHusPUf9tMQML9aV3XUE4mqY8ZN2cvh6gfsPluhnUImGcUEp9ivyPbVLuQv3W3gfaBYPN8DHQN74NiFSVCQUq7csFbPvI5xjiFr7k4Bq2dmdZ7pg6tYgNLBPK5KneFXDPJdEVmKHqZaII96fhESrj', '2', '10', 'c29uZXJlIG1lc2Fq', 'TG9yZW0gSXBzdW0gcGFzYWpsYXLEsW7EsW4gYmlyw6dvayDDp2XFn2l0bGVtZXNpIHZhcmTEsXIuIEFuY2FrIGJ1bmxhcsSxbiBiw7x5w7xrIGJpciDDp2/En3VubHXEn3UgbWl6YWgga2F0xLFsYXJhayB2ZXlhIHJhc3RnZWxlIHPDtnpjw7xrbGVyIGVrbGVuZXJlayBkZcSfacWfdGlyaWxtacWfbGVyZGlyLiBFxJ9lciBiaXIgTG9yZW0gSXBzdW0gcGFzYWrEsSBrdWxsYW5hY2Frc2FuxLF6LCBtZXRpbiBhcmFsYXLEsW5hIHV0YW5kxLFyxLFjxLEgc8O2emPDvGtsZXIgZ2l6bGVubWVkacSfaW5kZW4gZW1pbiBvbG1hbsSxeiBnZXJla2lyLiDEsG50ZXJuZXQndGVraSB0w7xtIExvcmVtIElwc3VtIMO8cmV0ZcOnbGVyaSDDtm5jZWRlbiBiZWxpcmxlbm1pxZ8gbWV0aW4gYmxva2xhcsSxbsSxIHlpbmVsZXIu', '2015-06-26 10:00:59', '0', '0', '0');
INSERT INTO `deu_mesajlar` VALUES ('NBzp9P6CBqiTD6dKFIHlRf2FNRPjd6umANvCBWgCFQq5jvvkWgh256eQGkz1i4xGNTyZe5mu3ZPNtd5E2njkg6LfrUZrbJn1skBxTzQSiVPgRrSrVJvGskqkeqpJACCpXugWbTRnmDFqfJtigQrVRHcuzZhJ8XnhiquRgfjzgn6RSFaI54Py7b1zpMp94ZmMkXWYym2etCEazMyCRRyrGfe1wVRF7gZjYN2yi8WWuyGB4Dp5', '1', '2', 'c29uZXJlIG1lc2Fq', 'ZXJ0ZXJ0ZXJ0ZXJ0ZXJ0dmVydHZlIGVydCBlcnQgZXJ0ZXJ0IA==', '2015-06-26 10:10:50', '1', '0', '0');
INSERT INTO `deu_mesajlar` VALUES ('bIuHZILXy2qKsu5Q5ia4kDbmWmgzzRHmkLGBkjWxGI4AAbFBpAtqXSeN86EUjUaKCkjniyz6TLqQtG9XVqG4qTMjxEHbByRzkcEENKdlVwRVvUEsBiyuAcXDJTIIm958JvhfSLY6rWz2zrSjFYbbwciHesHDP53PWEAc96dmCQlun1sdRCplErlsCCenA1QIWQAGzwqHG7ElynFapxnpakIL6LBtruRgdLc3Kv7VSAE9qyfLQ', '2', '1', 'Q3ZwOiBzb25lcmUgbWVzYWo=', 'ZGZnZmdkZiBnZGZnIGRmZ2RmIGdkZmcg', '2015-06-26 13:21:46', '1', '0', '0');

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

INSERT INTO `deu_sessions` VALUES ('1', 'admin', '1435596287', 'eeb9d269cc325ab3bb4f5919423a3124', 'admin', 'db');

INSERT INTO `deu_stats` VALUES ('Mozilla 5.0', '0', '4');
INSERT INTO `deu_stats` VALUES ('Windows 8.1', '1', '26');
INSERT INTO `deu_stats` VALUES ('LAPTOP', '2', '26');
INSERT INTO `deu_stats` VALUES ('Mozilla Firefox 38.0', '0', '21');

INSERT INTO `deu_users` VALUES ('1', 'Soner Ekici', 'admin', 'sonerekici@gmail.com', '1796dcaa1dcfb34b545d491c11d15d3b:jHYuDPc2BvZ9XFgt', 'Babadağ Toplum Sağlığı Merkezi', '1', 'Dr', '1998', '2006', '', '', '20', '30-07-1981', '20', '1', '', '2015-06-29 18:07:05', '2015-06-29 16:56:36', '2015-06-13 00:00:00', '1', '');
INSERT INTO `deu_users` VALUES ('2', 'Gülsen Koç Ekici', 'test', 'test@test.com', '28472767685baa34583f9a08d31a3224:jbjiLM6ggPWatY4N', 'Babadağ Toplum Sağlığı Merkezi', '13', 'Uzm.Dr', '2002', '2009', '1998105038', '0 (505) 743 76 15', '20', '09-04-1984', '20', '0', '', '2015-06-29 13:46:39', '2015-06-29 13:04:56', '2015-06-14 05:28:00', '1', '');
INSERT INTO `deu_users` VALUES ('3', 'Hebele Hubele', 'hebele', 'tester@tester.com', 'a41c934fae198e954e4c9c84e3584a0c:xDygzCTcyHP71lI', 'Babadağ Toplum Sağlığı Merkezi', '1', 'Dr', '1998', '2006', '1998105039', '0 (554) 857 77 79', '20', '30-07-1981', '20', '1', '', '2015-06-14 01:15:25', '2015-06-14 01:14:33', '2015-06-14 01:10:20', '1', '');
INSERT INTO `deu_users` VALUES ('4', 'Mehmet Ekici', 'memo', 'mem@mem.com', 'c2c820d898d1031632417d67631f019d:9yzVpf7k6kPHjw', 'Babadağ Toplum Sağlığı Merkezi', '1', 'Dr', '1999', '2006', '1999105038', '0 (505) 743 76 15', '19', '06-04-1984', '20', '1', '', '2015-06-15 12:17:40', '2015-06-14 19:35:06', '2015-06-14 01:23:34', '1', '');
INSERT INTO `deu_users` VALUES ('13', 'hebe', 'hebe', 'super@super.com', '8ee046d1f83cf462a695a4fbe052b170:DdbnLMgiFqdkEVNN', 'Hebele hebe', '1', 'Dr', '1985', '1994', '', '0 (555) 555 55 55', '1', '12-12-1912', '1', '0', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015-06-29 13:44:33', '0', 'kB8CAeNv8sp');
INSERT INTO `deu_users` VALUES ('11', 'gfggfgf', 'gfgfgfgf', 'test@yrtd.com', 'f6ba25b1927d090c5769a8c943d20eae:7AVpl1Py8F1RPSaz', 'Hebele', '25', 'Prof.Dr', '1988', '1996', '', '0 ', '7', '01-01-1950', '1', '1', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2015-06-22 17:16:38', '0', 'Kpb6X6bZ4kn9');
INSERT INTO `deu_users` VALUES ('10', 'İl Yöneticisi', 'iladmin', 'iladmin@iladmin.com', 'd0ea8e7d84757f46ff99efe6243566df:1Uf1rpKaHn3Edhi', 'Hebele', '6', 'Uzm.Dr', '1988', '1994', '1995105001', '0 (555) 555 55 55', '16', '01-01-1980', '6', '1', '', '2015-06-22 16:21:51', '2015-06-20 01:48:18', '2015-06-20 01:31:22', '1', '');

