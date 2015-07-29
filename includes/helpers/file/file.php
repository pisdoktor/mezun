<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

mimport('helpers.file.path');

class mezunFile {
	/**
	 * Gets the extension of a file name
	 *
	 * @param string $file The file name
	 * @return string The file extension
	 * @since 1.5
	 */
	static function getExt($file) {
		$dot = strrpos($file, '.') + 1;
		return substr($file, $dot);
	}

	/**
	 * Strips the last extension off a file name
	 *
	 * @param string $file The file name
	 * @return string The file name without the extension
	 * @since 1.5
	 */
	static function stripExt($file) {
		return preg_replace('#\.[^.]*$#', '', $file);
	}

	/**
	 * Makes file name safe to use
	 *
	 * @param string $file The name of the file [not full path]
	 * @return string The sanitised string
	 * @since 1.5
	 */
	static function makeSafe($file) {
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		return preg_replace($regex, '', $file);
	}

	/**
	 * Copies a file
	 *
	 * @param string $src The path to the source file
	 * @param string $dest The path to the destination file
	 * @param string $path An optional base path to prefix to the file names
	 * @return boolean True on success
	 * @since 1.5
	 */
	static function copy($src, $dest, $path = null) {

		// Prepend a base path if it exists
		if ($path) {
			$src = mezunPath::clean($path.DS.$src);
			$dest = mezunPath::clean($path.DS.$dest);
		}

		//Check src path
		if (!is_readable($src)) {
			ErrorAlert('mezunFile::copy: Cannot find or read file: '.$src);
			return false;
		}
			if (!@ copy($src, $dest)) {
				ErrorAlert('Copy failed');
				return false;
			}
			$ret = true;
		
		return $ret;
	}

	/**
	 * Delete a file or array of files
	 *
	 * @param mixed $file The file name or an array of file names
	 * @return boolean  True on success
	 * @since 1.5
	 */
	static function delete($file) {

		if (is_array($file)) {
			$files = $file;
		} else {
			$files[] = $file;
		}

		foreach ($files as $file) {
			$file = mezunPath::clean($file);

			// Try making the file writeable first. If it's read-only, it can't be deleted
			// on Windows, even if the parent folder is writeable
			@chmod($file, 0777);

			// In case of restricted permissions we zap it one way or the other
			// as long as the owner is either the webserver or the ftp
			if (@unlink($file)) {
				// Do nothing
			} else {
				$filename	= basename($file);
				ErrorAlert('Delete failed '.$filename);
				return false;
			}
		}

		return true;
	}

	/**
	 * Moves a file
	 *
	 * @param string $src The path to the source file
	 * @param string $dest The path to the destination file
	 * @param string $path An optional base path to prefix to the file names
	 * @return boolean True on success
	 * @since 1.5
	 */
	static function move($src, $dest, $path = '') {

		if ($path) {
			$src = mezunPath::clean($path.DS.$src);
			$dest = mezunPath::clean($path.DS.$dest);
		}

		//Check src path
		if (!is_readable($src) && !is_writable($src)) {
			ErrorAlert('mezunFile::move: Cannot find, read or write file '.$src);
			return false;
		}
			if (!@ rename($src, $dest)) {
				ErrorAlert('Rename failed');
				return false;
			}
		
		return true;
	}

	/**
	 * Read the contents of a file
	 *
	 * @param string $filename The full file path
	 * @param boolean $incpath Use include path
	 * @param int $amount Amount of file to read
	 * @param int $chunksize Size of chunks to read
	 * @param int $offset Offset of the file
	 * @return mixed Returns file contents or boolean False if failed
	 * @since 1.5
	 */
	static function read($filename, $incpath = false, $amount = 0, $chunksize = 8192, $offset = 0) {
		// Initialize variables
		$data = null;
		if($amount && $chunksize > $amount) { $chunksize = $amount; }
		if (false === $fh = fopen($filename, 'rb', $incpath)) {
			ErrorAlert('mezunFile::read: Unable to open file '.$filename);
			return false;
		}
		clearstatcache();
		if($offset) fseek($fh, $offset);
		if ($fsize = @ filesize($filename)) {
			if($amount && $fsize > $amount) {
				$data = fread($fh, $amount);
			} else {
				$data = fread($fh, $fsize);
			}
		} else {
			$data = '';
			$x = 0;
			// While its:
			// 1: Not the end of the file AND
			// 2a: No Max Amount set OR
			// 2b: The length of the data is less than the max amount we want
			while (!feof($fh) && (!$amount || strlen($data) < $amount)) {
				$data .= fread($fh, $chunksize);
			}
		}
		fclose($fh);

		return $data;
	}

	/**
	 * Write contents to a file
	 *
	 * @param string $file The full file path
	 * @param string $buffer The buffer to write
	 * @return boolean True on success
	 * @since 1.5
	 */
	static function write($file, $buffer) {
		// If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($file))) {
			mimport('helpers.file.folder');
			mezunFolder::create(dirname($file));
		}
			$file = mezunPath::clean($file);
			$ret = file_put_contents($file, $buffer);
	
		return $ret;
	}

	/**
	 * Moves an uploaded file to a destination folder
	 *
	 * @param string $src The name of the php (temporary) uploaded file
	 * @param string $dest The path (including filename) to move the uploaded file to
	 * @return boolean True on success
	 * @since 1.5
	 */
	static function upload($src, $dest) {
		$ret		= false;

		// Ensure that the path is valid and clean
		$dest = mezunPath::clean($dest);

		// Create the destination directory if it does not exist
		$baseDir = dirname($dest);
		if (!file_exists($baseDir)) {
			mimport('helpers.file.folder');
			mezunFolder::create($baseDir);
		}
			if (is_writeable($baseDir) && move_uploaded_file($src, $dest)) { // Short circuit to prevent file permission errors
				if (mezunPath::setPermissions($dest)) {
					$ret = true;
				} else {
					ErrorAlert('Dizin izinleri değiştirilemedi');
				}
			} else {
				ErrorAlert('Dosya yükleme işlemi başarısız oldu');
			}
		
		return $ret;
	}

	/**
	 * Wrapper for the standard file_exists function
	 *
	 * @param string $file File path
	 * @return boolean True if path is a file
	 * @since 1.5
	 */
	static function exists($file){
		return is_file(mezunPath::clean($file));
	}

	/**
	 * Returns the name, sans any path
	 *
	 * param string $file File path
	 * @return string filename
	 * @since 1.5
	 */
	static function getName($file) {
		$slash = strrpos($file, DS);
		if ($slash !== false) {
			return substr($file, $slash + 1);
		} else {
			return $file;
		}
	}
}
