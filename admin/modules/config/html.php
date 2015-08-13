<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class ConfigHTML {
	
	static function getConfigList($config, $lists) {
		?>
		<div class="panel panel-default">
	<div class="panel-heading"><h4>Yönetim Paneli - Site Ayarları</h4>
	</div>
	<div class="panel-body">
	<script type="text/javascript">
	$(document).ready(function() {	
		
		var current = $('#MAILER').val();
		
		if (current == 'sendmail') {
				$('#sendmail-group').show();
				$('#smtp-group').hide();
			}
			if (current == 'smtp') {
				$('#sendmail-group').hide();
				$('#smtp-group').show();
			}
			if (current == 'mail') {
				$('#sendmail-group').hide();
				$('#smtp-group').hide();
			}
		
		
		$('#MAILER').change(function() {
		
		var mailer = $('#MAILER').val();
					
			if (mailer == 'sendmail') {
				$('#sendmail-group').show('1000');
				$('#smtp-group').hide('1000');
			}
			if (mailer == 'smtp') {
				$('#sendmail-group').hide('1000');
				$('#smtp-group').show('1000');
			}
			if (mailer == 'mail') {
				$('#sendmail-group').hide('1000');
				$('#smtp-group').hide('1000');
			}
			
		});	
	});
	</script>
<form action="index.php" method="post" name="adminForm" role="form">

<fieldset>
		<legend>Site Genel Ayarları:</legend>
		
		<div class="form-group">		  
		<div class="row">
			  <div class="col-sm-4">
			  <label for="_ISO">
			  Site Charset:
			  </label>
			  </div>
			  <div class="col-sm-2">
			  <input type="text" name="_ISO" id="_ISO" value="<?php echo $config['_ISO'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
			  <div class="col-sm-4">
			  <label for="SITEHEAD">
			  Site Başlığı:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="SITEHEAD" id="SITEHEAD" value="<?php echo $config['SITEHEAD'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="META_DESC">
			  Site Meta Açıklaması:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="META_DESC" id="META_DESC" value="<?php echo $config['META_DESC'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
			  <div class="col-sm-4">
			  <label for="META_KEYS">
			  Site Meta Anahtarları:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="META_KEYS" id="META_KEYS" value="<?php echo $config['META_KEYS'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">                
		<div class="row">
			  <div class="col-sm-4">
			  <label for="OFFSET">
			  Site Zamanı:
			  </label>
			  </div>
			  <div class="col-sm-2">
			  <?php echo $lists['offset'];?>
			  </div>
			  <div class="col-sm-6">
			  Site Zamanı: <?php echo mezunGlobalHelper::timeformat(date('Y-m-d H:i:s'), true, true);?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">        
		<div class="row">
			  <div class="col-sm-4">
			  <label for="SECRETWORD">
			  Gizli Kelime:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <input type="text" name="SECRETWORD" id="SECRETWORD" value="<?php echo $config['SECRETWORD'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="SESSION_TYPE">
			  Oturum Güvenliği:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <?php echo $lists['sessiontype'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="USER_ACTIVATION">
			  Üyelik Aktivasyonu:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['useractivation'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="GZIPCOMP">
			  Gzip Sıkıştırma:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['gzipcomp'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="todayMod">
			  Dün-Bugün Gösterimi:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <?php echo $lists['todaymod'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="TIMEFORMAT">
			  Zaman Formatı:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="TIMEFORMAT" id="TIMEFORMAT" value="%d %B %Y, %H:%M:%S" class="form-control">
			  </div>
		</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		<legend>Mail Ayarları:</legend>
		
		<div class="form-group">                            
		<div class="row">
			  <div class="col-sm-4">
			  <label for="MAILER">
			  Mail Gönderici:
			  </label>
			  </div>
			  <div class="col-sm-4">
			  <?php echo $lists['mailer'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="MAILFROMNAME">
			  Mail Gönderen Adı:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="MAILFROMNAME" id="MAILFROMNAME" value="<?php echo $config['MAILFROMNAME'];?>" class="form-control">
			  </div>
		</div>
		</div>
				
		<div class="form-group">            
		<div class="row">
			  <div class="col-sm-4">
			  <label for="MAILFROM">
			  Mail Gönderen E-postası:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="MAILFROM" id="MAILFROM" value="<?php echo $config['MAILFROM'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div id="sendmail-group">
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="SENDMAIL">
			  Sendmail Yolu:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="SENDMAIL" id="SENDMAIL" value="<?php echo $config['SENDMAIL'];?>" class="form-control">
			  </div>
		</div>
		</div>
		</div>
		
		<div id="smtp-group">
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="smtpauth">
			  SMTP Auth:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="smtpauth" id="smtpauth" value="true" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
			  <div class="col-sm-4">
			  <label for="smtpuser">
			  SMTP User:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="smtpuser" id="smtpuser" value="" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="smtppass">
			  SMTP Parola:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="smtppass" id="smtppass" value="" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="smtphost">
			  SMTP Host:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="smtphost" id="smtphost" value="" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="smtpport">
			  SMTP Port:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="smtpport" id="smtpport" value="465" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="smtpsecure">
			  SMTP Secure:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <input type="text" name="smtpsecure" id="smtpsecure" value="tls" class="form-control">
			  </div>
		</div>
		</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		<legend>Hata Ayarları:</legend>
		
		<div class="form-group">
		<div class="row">
			  <div class="col-sm-4">
			  <label for="DEBUGMODE">
			  Hata Ayıklama:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['debugmode'];?>
			  </div>
		</div>
		</div>
					
		<div class="form-group">            
		<div class="row">
			  <div class="col-sm-4">
			  <label for="ERROR_REPORT">
			  Hata Gösterimi:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['errorreport'];?>
			  </div>
		</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		<legend>İstatistik Ayarları:</legend>
				
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="STATS">
			  Ziyaretçi İstatistikleri:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['stats'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">                    
		<div class="row">
			  <div class="col-sm-4">
			  <label for="COUNTSTATS">
			  İstatistik Sayacı:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['countstats'];?>
			  </div>
		</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		<legend>Tema Ayarları:</legend>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="ADMINTEMPLATE">
			  Admin Teması:
			  </label>
			  </div>
			  <div class="col-sm-4">
			  <input type="text" name="ADMINTEMPLATE" id="ADMINTEMPLATE" value="<?php echo $config['ADMINTEMPLATE'];?>" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="SITETEMPLATE">
			  Site Teması:
			  </label>
			  </div>
			  <div class="col-sm-4">
			  <input type="text" name="SITETEMPLATE" id="SITETEMPLATE" value="<?php echo $config['SITETEMPLATE'];?>" class="form-control">
			  </div>
		</div>
		</div>
			
		</fieldset>
				
		<fieldset>
		<legend>SEF Desteği:</legend>
		
		<div class="form-group">
		<div class="row">
			  <div class="col-sm-4">
			  <label for="SEF">
			  SEF Desteği:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['sef'];?>
			  </div>
		</div>
		</div>
		
		</fieldset>
		
		<fieldset>
		<legend>Forum Ayarları:</legend>
		
		<div class="form-group">			  
		<div class="row">
			  <div class="col-sm-4">
			  <label for="countChildPosts">
			  Alt Boardları Say:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['countchild'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="hotTopicPosts">
			  Popüler Başlık Mesaj Sayısı:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <input type="text" name="hotTopicPosts" id="hotTopicPosts" value="20" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">
		<div class="row">
			  <div class="col-sm-4">
			  <label for="hotTopicVeryPosts">
			  Çok Popüler Başlık Mesaj Sayısı:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <input type="text" name="hotTopicVeryPosts" id="hotTopicVeryPosts" value="50" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="compactTopicPagesEnable">
			  Compact Başlık Sayfaları Gösterimi:
			  </label>
			  </div>
			  <div class="col-sm-6">
			  <?php echo $lists['compacttopic'];?>
			  </div>
		</div>
		</div>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="compactTopicPagesContiguous">
			  Compact Başlık Sayfaları Sayısı:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <input type="text" name="compactTopicPagesContiguous" id="compactTopicPagesContiguous" value="5" class="form-control">
			  </div>
		</div>
		</div>
		
		<div class="form-group">					
		<div class="row">
			  <div class="col-sm-4">
			  <label for="latestPostCount">
			  Son Mesajlar Sayısı:
			  </label>
			  </div>
			  <div class="col-sm-3">
			  <input type="text" name="latestPostCount" id="latestPostCount" value="3" class="form-control">
			  </div>
		</div>
		</div>
		</fieldset>
					  
<input type="hidden" name="option" value="admin" />
<input type="hidden" name="bolum" value="config" />
<input type="hidden" name="task" value="" />
</form>
<br />

<div>
<input type="button" name="button" value="Kaydet" onclick="javascript:submitbutton('save');" class="btn btn-primary"  />
<input type="button" name="button" value="İptal" onclick="javascript:submitbutton('cancel');" class="btn btn-warning" />
</div>

</div>
</div>
<?php
}

}