<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunArkadasHelper {
	/**
	* Arkadaşlarımın arkadaşlarını bir dizide toplayan fonksiyon
	* 
	*/
	static function getFriendFriends() {
		
		//arkadaşlarımızı alalım
		$rows = mezunArkadasHelper::getMyFriends();
		
		//arkadaşlarımın arkadaşlarını alalım ve bir diziye ekleyelim
		$dizi = array();
		foreach ($rows as $row) {
			$dizi = array_merge($dizi, mezunArkadasHelper::getUserFriends($row));
		}
		
		return $dizi;
	}
	/**
	* Belirtilen kullanıcının arkadaşlarını getiren fonksiyon
	* 
	* @param mixed $userid
	*/
	static function getUserFriends($userid) {
		global $dbase, $my;
		
		$query = "SELECT aid AS fid FROM #__istekler WHERE durum=1 AND gid=".$dbase->Quote($userid);
		$dbase->setQuery($query);
		$rows1 = $dbase->loadResultArray();
		
		$query = "SELECT gid AS fid FROM #__istekler WHERE durum=1 AND aid=".$dbase->Quote($userid);
		$dbase->setQuery($query);
		$rows2 = $dbase->loadResultArray();
		
		$rows = array_merge($rows1, $rows2);
		
		return $rows;
	}
	/**
	* Arkadaşların id değerini array olarak getiren fonksiyon
	* 
	*/
	static function getMyFriends() {
		global $dbase, $my;
		
		$query = "SELECT aid AS fid FROM #__istekler WHERE durum=1 AND gid=".$dbase->Quote($my->id);
		$dbase->setQuery($query);
		$rows1 = $dbase->loadResultArray();
		
		$query = "SELECT gid AS fid FROM #__istekler WHERE durum=1 AND aid=".$dbase->Quote($my->id);
		$dbase->setQuery($query);
		$rows2 = $dbase->loadResultArray();
		
		$rows = array_merge($rows1, $rows2);
		
		return $rows;		
	}
	/**
	* Belirtilen kullanıcı ile arkadaş olup olmadığına bakan fonksiyon
	* 
	* @param mixed $userid : bakılacak kullanıcı
	*/
	static function checkArkadaslik($userid) {
		global $dbase, $my;
		
		$where[] = "(gid=".$dbase->Quote($userid)." AND aid=".$dbase->Quote($my->id).")";
		$where[] = "(gid=".$dbase->Quote($my->id)." AND aid=".$dbase->Quote($userid).")";
		
		$query = "SELECT id FROM #__istekler"
		. "\n WHERE (" . implode( ' OR ', $where ).")"
		. "\n AND durum=1";
		;
		$dbase->setQuery($query);
		
		if ($dbase->loadResult() > 0) {
			return true;
		} else {
			return false;
		}		
	}
	
	/**
	* Tanıyor olabileceğin kullanıcıları getiren fonksiyon
	* Arkadaşlarımın arkadaşlarını da listeye ekliyor. Ayrıca
	* kullanıcıların fakülteye başlama ve bitiş tarihleri, yaşadıkları ve doğdukları şehir,
	* branşları gibi özelliklerine bakıp ortak olan üyeleri alıyor. Arkadaşlarını listenin
	* dışına çıkarıyor ve bir objectlist olarak sana geri veriyor.
	* Objectlist içerisinde kullanıcının; id, name, username, image, registerDate, lastvisit, sehir
	* bilgileri var.
	*/
	static function canRecognize() {
		global $dbase, $my;
		
		//arkadaşları sql sorgusuna uygun hale getirelim
		$myrows = mezunArkadasHelper::getMyFriends();
		$myfriends = implode(',', $myrows);
		
		$where = count($myrows) ? ' AND u.id NOT IN ('.$myfriends.')':'';
		
		//arkadaşlarımın arkadaşlarını da belki tanıyorumdur... onları da ekleyelim
		$myff = mezunArkadasHelper::getFriendFriends();
		$myff = implode(',', $myff);
		
		$where2 = count($myff) ? ' AND u.id IN ('.$myff.')':'';
		
		//şimdi de  tanıyor olabileceğimiz üyeleri bulalım. ama içerisinde arkadaşlarımız olmasın (eğer arkadaş varsa!)
		$query = "SELECT u.id, u.name, u.username, u.image, u.registerDate, u.lastvisit, s.name AS sehir "
		. " FROM #__users AS u"
		. " LEFT JOIN #__sehirler AS s ON s.id=u.sehir"
		. " WHERE ("
		. " u.byili=".$dbase->Quote($my->byili)
		. " OR u.myili=".$dbase->Quote($my->myili)
		. " OR u.sehir=".$dbase->Quote($my->sehirid)
		. " OR u.dogumyeri=".$dbase->Quote($my->dogumyeriid)
		. " OR u.brans=".$dbase->Quote($my->brans)
		. ")" 
		. $where
		. $where2
		. " AND u.id NOT IN (".$my->id.") ORDER BY RAND() LIMIT 10"
		;
		
		$dbase->setQuery($query);
		
		$rows = $dbase->loadObjectList();
		
		return $rows;
	}
	
	/**
	* Bir kullanıcı ile ortak arkadaşlarınızı tespit eder
	* 
	* @param mixed $userid : ortak arkadaşına bakılacak kullanıcı id
	* @param mixed $count : sayı mı yoksa ortak arkadaşlarınızın idleri mi dönecek?
	*/
	static function ortakArkadasCount($userid, $count=true) {
		global $dbase, $my;
		
		//önce kendi arkadaşlarımızı alalım
		$myrows = mezunArkadasHelper::getMyFriends();
		
		//şimdide belirtilen üyenin arkadaşlarını alalım 
		$rows = mezunArkadasHelper::getUserFriends($userid);
		
		//eğer benim ve onun arkadaşları varsa...
		if ($rows && $myrows) {
			//karşılaştırma yapalım
			$ayni = array_intersect($myrows, $rows);
		
			//toplam sayıdan kendimizi çıkaralım
			$totalortak = count($ayni)-1;
		
			//eğer sayı isteniyorsa
			if ($count) {
				echo $totalortak;
				//eğer ortak arkadaşların id değerleri isteniyorsa
			} else {
				return $ayni;
			}
		
		} else {
			return 0;
		}
	}
	
	static function ortakArkadasMI($userid, $checkid) {
		global $dbase, $my;
		
		//kontrol edilmek istenen ben isem false döndür
		if ($checkid == $my->id) {
			return false;
		}
		
		//benim arkadaşım mı bakalım
		$myfriends = mezunArkadasHelper::getMyFriends();
		
		$arkadasim = false;
		if (in_array($checkid, $myfriends)) {
			$arkadasim = true;
		}
		
		//userid nin arkadaslarına bakalım
		$userfriends = mezunArkadasHelper::getUserFriends($userid);
		
		$arkadasi = false;
		if (in_array($checkid, $userfriends)) {
			$arkadasi = true;
		}
		
		if ($arkadasim && $arkadasi) {
			echo 'Ortak Arkadaşınız';
		} else {
			echo '';
		}		
	}
}