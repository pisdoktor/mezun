<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

/** boolean True if a Windows based host */
define('PATH_ISWIN', (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));
/** boolean True if a Mac based host */
define('PATH_ISMAC', (strtoupper(substr(PHP_OS, 0, 3)) === 'MAC'));

if (!defined('DS')) {
	/** string Shortcut for the DIRECTORY_SEPARATOR define */
	define('DS', DIRECTORY_SEPARATOR);
}

class mezunPath {
	/**
	 * Checks if a path's permissions can be changed
	 *
	 * @param	string	$path	Path to check
	 * @return	boolean	True if path can have mode changed
	 * @since	1.5
	 */
	static function canChmod($path) {
		$perms = fileperms($path);
		if ($perms !== false)
		{
			if (@ chmod($path, $perms ^ 0001))
			{
				@chmod($path, $perms);
				return true;
			}
		}
		return false;
	}

	/**
	 * Chmods files and directories recursivly to given permissions
	 *
	 * @param	string	$path		Root path to begin changing mode [without trailing slash]
	 * @param	string	$filemode	Octal representation of the value to change file mode to [null = no change]
	 * @param	string	$foldermode	Octal representation of the value to change folder mode to [null = no change]
	 * @return	boolean	True if successful [one fail means the whole operation failed]
	 * @since	1.5
	 */
	static function setPermissions($path, $filemode = '0644', $foldermode = '0755') {

		// Initialize return value
		$ret = true;

		if (is_dir($path))
		{
			$dh = opendir($path);
			while ($file = readdir($dh))
			{
				if ($file != '.' && $file != '..') {
					$fullpath = $path.'/'.$file;
					if (is_dir($fullpath)) {
						if (!mezunPath::setPermissions($fullpath, $filemode, $foldermode)) {
							$ret = false;
						}
					} else {
						if (isset ($filemode)) {
							if (!@ chmod($fullpath, octdec($filemode))) {
								$ret = false;
							}
						}
					} // if
				} // if
			} // while
			closedir($dh);
			if (isset ($foldermode)) {
				if (!@ chmod($path, octdec($foldermode))) {
					$ret = false;
				}
			}
		}
		else
		{
			if (isset ($filemode)) {
				$ret = @ chmod($path, octdec($filemode));
			}
		} // if
		return $ret;
	}

	/**
	 * Get the permissions of the file/folder at a give path
	 *
	 * @param	string	$path	The path of a file/folder
	 * @return	string	Filesystem permissions
	 * @since	1.5
	 */
	static function getPermissions($path) {
		$path = mezunPath::clean($path);
		$mode = @ decoct(@ fileperms($path) & 0777);

		if (strlen($mode) < 3) {
			return '---------';
		}
		$parsed_mode = '';
		for ($i = 0; $i < 3; $i ++)
		{
			// read
			$parsed_mode .= ($mode { $i } & 04) ? "r" : "-";
			// write
			$parsed_mode .= ($mode { $i } & 02) ? "w" : "-";
			// execute
			$parsed_mode .= ($mode { $i } & 01) ? "x" : "-";
		}
		return $parsed_mode;
	}

	/**
	 * Checks for snooping outside of the file system root
	 *
	 * @param	string	$path	A file system path to check
	 * @return	string	A cleaned version of the path
	 * @since	1.5
	 */
	static function check($path) {
		if (strpos($path, '..') !== false) {
			ErrorAlert('mezunPath::check Use of relative paths not permitted'); // don't translate
			jexit();
		}
		$path = mezunPath::clean($path);
		if (strpos($path, mezunPath::clean(ABS_PATH)) !== 0) {
			ErrorAlert('mezunPath::check Snooping out of bounds @ '.$path); // don't translate
			jexit();
		}
	}

	/**
	 * Function to strip additional / or \ in a path name
	 *
	 * @static
	 * @param	string	$path	The path to clean
	 * @param	string	$ds		Directory separator (optional)
	 * @return	string	The cleaned path
	 * @since	1.5
	 */
	static function clean($path, $ds=DS) {
		$path = trim($path);

		if (empty($path)) {
			$path = ABSPATH;
		} else {
			// Remove double slashes and backslahses and convert all slashes and backslashes to DS
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}

	/**
	 * Method to determine if script owns the path
	 *
	 * @static
	 * @param	string	$path	Path to check ownership
	 * @return	boolean	True if the php script owns the path passed
	 * @since	1.5
	 */
	static function isOwner($path) {
		mimport('helpers.file.file');

		$tmp = md5(MakePassword(16));
		$ssp = ini_get('session.save_path');
		$jtp = ABSPATH.DS.'tmp';

		// Try to find a writable directory
		$dir = is_writable('/tmp') ? '/tmp' : false;
		$dir = (!$dir && is_writable($ssp)) ? $ssp : false;
		$dir = (!$dir && is_writable($jtp)) ? $jtp : false;

		if ($dir)
		{
			$test = $dir.DS.$tmp;

			// Create the test file
			mezunFile::write($test, '');

			// Test ownership
			$return = (fileowner($test) == fileowner($path));

			// Delete the test file
			mezunFile::delete($test);

			return $return;
		}

		return false;
	}

	/**
	 * Searches the directory paths for a given file.
	 *
	 * @access	protected
	  * @param	array|string	$path	An path or array of path to search in
	 * @param	string	$file	The file name to look for.
	 * @return	mixed	The full path and file name for the target file, or boolean false if the file is not found in any of the paths.
	 * @since	1.5
	 */
	static function find($paths, $file) {
		settype($paths, 'array'); //force to array

		// start looping through the path set
		foreach ($paths as $path)
		{
			// get the path to the file
			$fullname = $path.DS.$file;

			// is the path based on a stream?
			if (strpos($path, '://') === false)
			{
				// not a stream, so do a realpath() to avoid directory
				// traversal attempts on the local file system.
				$path = realpath($path); // needed for substr() later
				$fullname = realpath($fullname);
			}

			// the substr() check added to make sure that the realpath()
			// results in a directory registered so that
			// non-registered directores are not accessible via directory
			// traversal attempts.
			if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path) {
				return $fullname;
			}
		}

		// could not find the file in the set of paths
		return false;
	}
}