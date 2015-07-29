<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunGlobalMail {
	/**
* Function to create a mail object for futher use (uses phpMailer)
* @param string From e-mail address
* @param string From name
* @param string E-mail subject
* @param string Message body
* @return object Mail object
*/
	static function CreateMail( $from='', $fromname='', $subject, $body ) {
	
	$mail = new PHPMailer();

	$mail->PluginDir = ABSPATH .'/includes/phpmailer/';
	$mail->SetLanguage( 'tr', ABSPATH . '/includes/phpmailer/language/' );
	$mail->CharSet     = substr_replace(_ISO, '', 0, 8);
	$mail->isSendmail();
	$mail->From     = $from ? $from : MAILFROM;
	$mail->FromName = $fromname ? $fromname : MAILFROMNAME;

	// Add smtp values if needed
	if ( MAILER == 'smtp' ) {
		$mail->SMTPAuth = smtpauth;
		$mail->Username = smtpuser;
		$mail->Password = smtppass;
		$mail->Host     = smtphost;
	} else

	// Set sendmail path
	if ( MAILER == 'sendmail' ) {
		if (SENDMAIL)
			$mail->Sendmail = SENDMAIL;
	} // if

	$mail->Subject     = $subject;
	$mail->Body     = $body;

	return $mail;
}

/**
* Mail function (uses phpMailer)
* @param string From e-mail address
* @param string From name
* @param string/array Recipient e-mail address(es)
* @param string E-mail subject
* @param string Message body
* @param boolean false = plain text, true = HTML
* @param string/array CC e-mail address(es)
* @param string/array BCC e-mail address(es)
* @param string/array Attachment file name(s)
* @param string/array ReplyTo e-mail address(es)
* @param string/array ReplyTo name(s)
* @return boolean
*/
	static function mezunMail( $from, $fromname, $recipient, $subject, $body, $mode=0, $cc=NULL, $bcc=NULL, $attachment=NULL, $replyto=NULL, $replytoname=NULL ) {
	global $debug;

	// Allow empty $from and $fromname settings (backwards compatibility)
	if ($from == '') {
		$from = MAILFROM;
	}
	if ($fromname == '') {
		$fromname = MAILFROMNAME;
	}

	// Filter from, fromname and subject
	if (!mezunGlobalMail::IsValidEmail( $from ) || !mezunGlobalMail::IsValidName( $fromname ) || !mazunGlobalMail::IsValidName( $subject )) {
		return false;
	}

	$mail = mezunGlobalMail::CreateMail( $from, $fromname, $subject, $body );

	// activate HTML formatted emails
	if ( $mode ) {
		$mail->IsHTML(true);
	}

	if (is_array( $recipient )) {
		foreach ($recipient as $to) {
			if (!mezunGlobalMail::IsValidEmail( $to )) {
				return false;
			}
			$mail->AddAddress( $to );
		}
	} else {
		if (!mezunGlobalMail::IsValidEmail( $recipient )) {
			return false;
		}
		$mail->AddAddress( $recipient );
	}
	if (isset( $cc )) {
		if (is_array( $cc )) {
			foreach ($cc as $to) {
				if (!mezunGlobalMail::IsValidEmail( $to )) {
					return false;
				}
				$mail->AddCC($to);
			}
		} else {
			if (!mezunGlobalMail::IsValidEmail( $cc )) {
				return false;
			}
			$mail->AddCC($cc);
		}
	}
	if (isset( $bcc )) {
		if (is_array( $bcc )) {
			foreach ($bcc as $to) {
				if (!mezunGlobalMail::IsValidEmail( $to )) {
					return false;
				}
				$mail->AddBCC( $to );
			}
		} else {
			if (!mezunGlobalMail::IsValidEmail( $bcc )) {
				return false;
			}
			$mail->AddBCC( $bcc );
		}
	}
	if ($attachment) {
		if (is_array( $attachment )) {
			foreach ($attachment as $fname) {
				$mail->AddAttachment( $fname );
			}
		} else {
			$mail->AddAttachment($attachment);
		}
	}
	
	if ($replyto) {
		if (is_array( $replyto )) {
			reset( $replytoname );
			foreach ($replyto as $to) {
				$toname = ((list( $key, $value ) = each( $replytoname )) ? $value : '');
				if (!mezunGlobalMail::IsValidEmail( $to ) || !mezunGlobalMail::IsValidName( $toname )) {
					return false;
				}
				$mail->AddReplyTo( $to, $toname );
			}
		} else {
			if (!mezunGlobalMail::IsValidEmail( $replyto ) || !mezunGlobalMail::IsValidName( $replytoname )) {
				return false;
			}
			$mail->AddReplyTo($replyto, $replytoname);
		}
	}

	$mailssend = $mail->Send();

	if( DEBUGMODE ) {
		//$mosDebug->message( "Mails send: $mailssend");
	}
	if( $mail->error_count > 0 ) {
		//$mosDebug->message( "The mail message $fromname <$from> about $subject to $recipient <b>failed</b><br /><pre>$body</pre>", false );
		//$mosDebug->message( "Mailer Error: " . $mail->ErrorInfo . "" );
	}
	return $mailssend;
}

	static function IsValidEmail( $email ) {
	$valid = preg_match( '/^[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}$/', $email );

	return $valid;
}

	static function IsValidName( $string ) {
	/*
	 * The following regular expression blocks all strings containing any low control characters:
	 * 0x00-0x1F, 0x7F
	 * These should be control characters in almost all used charsets.
	 * The high control chars in ISO-8859-n (0x80-0x9F) are unused (e.g. http://en.wikipedia.org/wiki/ISO_8859-1)
	 * Since they are valid UTF-8 bytes (e.g. used as the second byte of a two byte char),
	 * they must not be filtered.
	 */
	$invalid = preg_match( '/[\x00-\x1F\x7F]/', $string );
	if ($invalid) {
		return false;
	} else {
		return true;
	}
}
	
}
