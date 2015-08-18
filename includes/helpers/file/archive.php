<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

class mezunArchive {
	/**
	 * @param    string    The name of the archive file
	 * @param    string    Directory to unpack into
	 * @return    boolean    True for success
	 */
	static function extract( $archivename, $extractdir) {
		mimport('helpers.file.file');
		mimport('helpers.file.folder');
		$untar = false;
		$result = false;
		$ext = mezunFile::getExt(strtolower($archivename));
		// check if a tar is embedded...gzip/bzip2 can just be plain files!
		if (mezunFile::getExt(mezunFile::stripExt(strtolower($archivename))) == 'tar') {
			$untar = true;
		}

		switch ($ext) {
			
			case 'zip':
				$adapter =& mezunArchive::getAdapter('zip');
				if ($adapter) {
					$result = $adapter->extract($archivename, $extractdir);
				}
			break;
			
			case 'tar':
				$adapter =& mezunArchive::getAdapter('tar');
				if ($adapter) {
					$result = $adapter->extract($archivename, $extractdir);
				}
			break;
			
			
			default:
				ErrorAlert('Kabul edilmeyen arşiv dosyası');
				return false;
			break;
		}

		if (! $result) {
			return false;
		}
		return true;
	}

	static function &getAdapter($type) {
		static $adapters;

		if (!isset($adapters)) {
			$adapters = array();
		}

		if (!isset($adapters[$type])) {
			// Try to load the adapter object
			$class = 'mezunArchive'.ucfirst($type);

			if (!class_exists($class)) {
				$path = dirname(__FILE__).DS.'archive'.DS.strtolower($type).'.php';
				if (file_exists($path)) {
					require_once($path);
				} else {
					ErrorAlert('Unable to load archive');
				}
			}

			$adapters[$type] = new $class();
		}
		return $adapters[$type];
	}
}