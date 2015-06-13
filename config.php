<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB', 'mezun');
define('DB_PREFIX', 'deu_');


define('_ISO', 'charset=UTF-8');
define('ABSPATH', dirname(__FILE__));
define('SITEURL', 'http://localhost/deutf');

define('SITEHEAD', '9 Eylül Üniversitesi Tıp Fakültesi Mezunları Sitesi');
define('META_DESC', '9 Eylül Üniversitesi Tıp Fakültesi Mezunları Sitesi');
define('META_KEYS', '');

define('ADMINTEMPLATE', 'standart');
define('SITETEMPLATE', 'standart');

define('OFFSET', 1);
define('DEBUGMODE', 0);
define('SECRETWORD', 'deutf');
define('ERROR_REPORT', 1);

define('SESSION_TYPE', 0);

define('MAILER', 'sendmail'); //sendmail or smtp
define('SENDMAIL', '/usr/sbin/sendmail');
define('MAILFROM', 'Mezun Sistemi');
define('MAILFROMNAME', 'mezun@mezunsistemi.com');
define('smtpauth', '');
define('smtpuser', '');
define('smtppass', '');
define('smtphost', '');

define('GZIPCOMP', 0);
define('STATS', 1);
define('FILEPERMS', '');
define('DIRPERMS', '');