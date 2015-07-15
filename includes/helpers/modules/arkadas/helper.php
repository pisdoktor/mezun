<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' );

class mezunArkadasHelper {
	
	/**
	* Tanıyor olabileceğin kullanıcıları getiren fonksiyon
	* Kullanıcıların fakülteye başlama ve bitiş tarihleri, yaşadıkları ve doğdukları şehir,
	* branşları gibi özelliklerine bakıp ortak olan üyeleri alıyor. Arkadaşlarını listenin
	* dışına çıkarıyor ve bir objectlist olarak sana geri veriyor.
	* Objectlist içerisinde kullanıcının; id, name, username, image, registerDate, lastvisit
	* bilgileri var.
	*/
	static function belkiTaniyorsun() {
		global $dbase, $my;
		
		//önce kendi arkadaşlarımızı alalım
		$dbase->setQuery("SELECT gid AS fid FROM #__istekler WHERE aid=".$dbase->Quote($my->id)." AND durum=1");
		$myrows1 = $dbase->loadResultArray();
		
		$dbase->setQuery("SELECT aid AS fid FROM #__istekler WHERE gid=".$dbase->Quote($my->id)." AND durum=1");
		$myrows2 = $dbase->loadResultArray();
		
		$myrows = array_merge($myrows1, $myrows2);
		//arkadaşları sql sorgusuna uygun hale getirelim
		$myfriends = implode(',', $myrows);
		
		//şimdi de  tanıyor olabileceğimiz üyeleri bulalım. ama içerisinde arkadaşlarımız olmasın!
		$query = "SELECT id, name, username, image, registerDate, lastvisit FROM #__users WHERE ("
		. " byili=".$dbase->Quote($my->byili)
		. " OR myili=".$dbase->Quote($my->myili)
		. " OR sehir=".$dbase->Quote($my->sehirid)
		. " OR dogumyeri=".$dbase->Quote($my->dogumyeriid)
		. " OR brans=".$dbase->Quote($my->brans)
		. ") AND id NOT IN (".$myfriends.") AND id NOT IN (".$my->id.")";
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
		$dbase->setQuery("SELECT gid AS fid FROM #__istekler WHERE aid=".$dbase->Quote($my->id)." AND durum=1 AND gid NOT IN (".$userid.")");
		$myrows1 = $dbase->loadResultArray();
		
		$dbase->setQuery("SELECT aid AS fid FROM #__istekler WHERE gid=".$dbase->Quote($my->id)." AND durum=1 AND aid NOT IN (".$userid.")");
		$myrows2 = $dbase->loadResultArray();
		
		$myrows = array_merge($myrows1, $myrows2);
		
		//şimdide belirtilen üyenin arkadaşlarını alalım 
		$dbase->setQuery("SELECT gid AS fid FROM #__istekler WHERE aid=".$dbase->Quote($userid)." AND durum=1 AND gid NOT IN (".$my->id.")");
		$rows1 = $dbase->loadResultArray();
		
		$dbase->setQuery("SELECT aid AS fid FROM #__istekler WHERE gid=".$dbase->Quote($userid)." AND durum=1 AND aid NOT IN (".$my->id.")");
		$rows2 = $dbase->loadResultArray();
		
		$rows = array_merge($rows1, $rows2);
		
		//karşılaştırma yapalım
		$ayni = array_intersect($myrows, $rows);
		
		$totalortak = count($ayni);
		
		//eğer sayı isteniyorsa
		if ($count) {
			echo $totalortak;
		//eğer ortak arkadaşların id değerleri isteniyorsa
		} else {
			return $ayni;
		}
	}
	
}