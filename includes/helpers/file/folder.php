<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

mimport('helpers.file.path');

/**
 * A Folder handling class
 */
class mezunFolder
{
	/**
	 * Copy a folder.
	 *
	 * @param	string	The path to the source folder.
	 * @param	string	The path to the destination folder.
	 * @param	string	An optional base path to prefix to the file names.
	 * @param	boolean	Optionally force folder/file overwrites.
	 * @return	mixed	JError object on failure or boolean True on success.
	 * @since	1.5
	 */
	public function copy($src, $dest, $path = '', $force = false) {
		
		if ($path) {
			$src = mezunPath::clean($path . DS . $src);
			$dest = mezunPath::clean($path . DS . $dest);
		}

		// Eliminate trailing directory separators, if any
		$src = rtrim($src, DS);
		$dest = rtrim($dest, DS);

		if (!mezunFolder::exists($src)) {
			return ErrorAlert('Cannot find source folder');
		}
		if (mezunFolder::exists($dest) && !$force) {
			return ErrorAlert('Folder already exists');
		}

		// Make sure the destination exists
		if (! mezunFolder::create($dest)) {
			return ErrorAlert('Unable to create target folder');
		}
			if (!($dh = @opendir($src))) {
				return ErrorAlert('Unable to open source folder');
			}
			// Walk through the directory copying files and recursing into folders.
			while (($file = readdir($dh)) !== false) {
				$sfid = $src . DS . $file;
				$dfid = $dest . DS . $file;
				switch (filetype($sfid)) {
					case 'dir':
						if ($file != '.' && $file != '..') {
							$ret = mezunFolder::copy($sfid, $dfid, null, $force);
							if ($ret !== true) {
								return $ret;
							}
						}
						break;

					case 'file':
						if (!@copy($sfid, $dfid)) {
							return ErrorAlert('Copy failed');
						}
						break;
				}
			}
		
		return true;
	}

	/**
	 * Create a folder -- and all necessary parent folders.
	 *
	 * @param string A path to create from the base path.
	 * @param int Directory permissions to set for folders created.
	 * @return boolean True if successful.
	 * @since 1.5
	 */
	public function create($path = '', $mode = 0755) {
		
		static $nested = 0;

		// Check to make sure the path valid and clean
		$path = mezunPath::clean($path);

		// Check if parent dir exists
		$parent = dirname($path);
		if (!mezunFolder::exists($parent)) {
			// Prevent infinite loops!
			$nested++;
			if (($nested > 20) || ($parent == $path)) {
				ErrorAlert('mezunFolder::create: Infinite loop detected');
				$nested--;
				return false;
			}

			// Create the parent directory
			if (mezunFolder::create($parent, $mode) !== true) {
				// mezunFolder::create throws an error
				$nested--;
				return false;
			}

			// OK, parent directory has been created
			$nested--;
		}

		// Check if dir already exists
		if (mezunFolder::exists($path)) {
			return true;
		}
			// We need to get and explode the open_basedir paths
			$obd = ini_get('open_basedir');

			// If open_basedir is set we need to get the open_basedir that the path is in
			if ($obd != null)
			{
				if (PATH_ISWIN) {
					$obdSeparator = ";";
				} else {
					$obdSeparator = ":";
				}
				// Create the array of open_basedir paths
				$obdArray = explode($obdSeparator, $obd);
				$inBaseDir = false;
				// Iterate through open_basedir paths looking for a match
				foreach ($obdArray as $test) {
					$test = mezunPath::clean($test);
					if (strpos($path, $test) === 0) {
						$obdpath = $test;
						$inBaseDir = true;
						break;
					}
				}
				if ($inBaseDir == false) {
					// Return false for mezunFolder::create because the path to be created is not in open_basedir
					ErrorAlert('mezunFolder::create: Path not in open_basedir paths');
					return false;
				}
			}

			// First set umask
			$origmask = @umask(0);

			// Create the path
			if (!$ret = @mkdir($path, $mode)) {
				@umask($origmask);
				ErrorAlert('mezunFolder::create: Could not create directory Path: ' . $path);
				return false;
			}

			// Reset umask
			@umask($origmask);
		
		return $ret;
	}

	/**
	 * Delete a folder.
	 *
	 * @param string The path to the folder to delete.
	 * @return boolean True on success.
	 * @since 1.5
	 */
	public function delete($path) {
		// Sanity check
		if (!$path) {
			// Bad programmer! Bad Bad programmer!
			ErrorAlert('mezunFolder::delete: Attempt to delete base directory');
			return false;
		}
		
		// Check to make sure the path valid and clean
		$path = mezunPath::clean($path);

		// Is this really a folder?
		if (!is_dir($path)) {
			ErrorAlert('mezunFolder::delete: Path is not a folder Path: ' . $path);
			return false;
		}

		// Remove all the files in folder if they exist
		$files = mezunFolder::files($path, '.', false, true, array());
		if (!empty($files)) {
			mimport('helpers.file.file');
			if (mezunFile::delete($files) !== true) {
				// mezunFile::delete throws an error
				return false;
			}
		}

		// Remove sub-folders of folder
		$folders = mezunFolder::folders($path, '.', false, true, array());
		foreach ($folders as $folder) {
			if (is_link($folder)) {
				// Don't descend into linked directories, just delete the link.
				mimport('helpers.file.file');
				if (mezunFile::delete($folder) !== true) {
					// mezunFile::delete throws an error
					return false;
				}
			} elseif (mezunFolder::delete($folder) !== true) {
				// mezunFolder::delete throws an error
				return false;
			}
		}

		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp
		if (@rmdir($path)) {
			$ret = true;
		} else {
			ErrorAlert('mezunFolder::delete: Could not delete folder Path: ' . $path);
			$ret = false;
		}
		return $ret;
	}

	/**
	 * Moves a folder.
	 *
	 * @param string The path to the source folder.
	 * @param string The path to the destination folder.
	 * @param string An optional base path to prefix to the file names.
	 * @return mixed Error message on false or boolean true on success.
	 * @since 1.5
	 */
	public function move($src, $dest, $path = '') {

		if ($path) {
			$src = mezunPath::clean($path . DS . $src);
			$dest = mezunPath::clean($path . DS . $dest);
		}

		if (!mezunFolder::exists($src) && !is_writable($src)) {
			return 'Cannot find source folder';
		}
		if (mezunFolder::exists($dest)) {
			return 'Folder already exists';
		}
			if (!@rename($src, $dest)) {
				return 'Rename failed';
			}
			$ret = true;
	
		return $ret;
	}

	/**
	 * Wrapper for the standard file_exists function
	 *
	 * @param string Folder name relative to installation dir
	 * @return boolean True if path is a folder
	 * @since 1.5
	 */
	public function exists($path) {
		return is_dir(mezunPath::clean($path));
	}

	/**
	 * Utility function to read the files in a folder.
	 *
	 * @param	string	The path of the folder to read.
	 * @param	string	A filter for file names.
	 * @param	mixed	True to recursively search into sub-folders, or an
	 * integer to specify the maximum depth.
	 * @param	boolean	True to return the full path to the file.
	 * @param	array	Array with names of files which should not be shown in
	 * the result.
	 * @return	array	Files in the given folder.
	 * @since 1.5
	 */
	public function files($path, $filter = '.', $recurse = false, $fullpath = false, $exclude = array('.svn', 'CVS'))
	{
		// Initialize variables
		$arr = array();

		// Check to make sure the path valid and clean
		$path = mezunPath::clean($path);

		// Is the path a folder?
		if (!is_dir($path)) {
			ErrorAlert('mezunFolder::files: Path is not a folder Path: ' . $path);
			return false;
		}

		// read the source directory
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false)
		{
			if (($file != '.') && ($file != '..') && (!in_array($file, $exclude))) {
				$dir = $path . DS . $file;
				$isDir = is_dir($dir);
				if ($isDir) {
					if ($recurse) {
						if (is_integer($recurse)) {
							$arr2 = mezunFolder::files($dir, $filter, $recurse - 1, $fullpath);
						} else {
							$arr2 = mezunFolder::files($dir, $filter, $recurse, $fullpath);
						}
						
						$arr = array_merge($arr, $arr2);
					}
				} else {
					if (preg_match("/$filter/", $file)) {
						if ($fullpath) {
							$arr[] = $path . DS . $file;
						} else {
							$arr[] = $file;
						}
					}
				}
			}
		}
		closedir($handle);

		asort($arr);
		return $arr;
	}

	/**
	 * Utility function to read the folders in a folder.
	 *
	 * @param	string	The path of the folder to read.
	 * @param	string	A filter for folder names.
	 * @param	mixed	True to recursively search into sub-folders, or an
	 * integer to specify the maximum depth.
	 * @param	boolean	True to return the full path to the folders.
	 * @param	array	Array with names of folders which should not be shown in
	 * the result.
	 * @return	array	Folders in the given folder.
	 * @since 1.5
	 */
	public function folders($path, $filter = '.', $recurse = false, $fullpath = false, $exclude = array('.svn', 'CVS')) {
		// Initialize variables
		$arr = array();

		// Check to make sure the path valid and clean
		$path = mezunPath::clean($path);

		// Is the path a folder?
		if (!is_dir($path)) {
			ErrorAlert('mezunFolder::folder: Path is not a folder Path: ' . $path);
			return false;
		}

		// read the source directory
		$handle = opendir($path);
		while (($file = readdir($handle)) !== false)
		{
			if (($file != '.') && ($file != '..') && (!in_array($file, $exclude))) {
				$dir = $path . DS . $file;
				$isDir = is_dir($dir);
				if ($isDir) {
					// Removes filtered directories
					if (preg_match("/$filter/", $file)) {
						if ($fullpath) {
							$arr[] = $dir;
						} else {
							$arr[] = $file;
						}
					}
					if ($recurse) {
						if (is_integer($recurse)) {
							$arr2 = mezunFolder::folders($dir, $filter, $recurse - 1, $fullpath);
						} else {
							$arr2 = mezunFolder::folders($dir, $filter, $recurse, $fullpath);
						}
						
						$arr = array_merge($arr, $arr2);
					}
				}
			}
		}
		closedir($handle);

		asort($arr);
		return $arr;
	}

	/**
	 * Lists folder in format suitable for tree display.
	 *
	 * @access	public
	 * @param	string	The path of the folder to read.
	 * @param	string	A filter for folder names.
	 * @param	integer	The maximum number of levels to recursively read,
	 * defaults to three.
	 * @param	integer	The current level, optional.
	 * @param	integer	Unique identifier of the parent folder, if any.
	 * @return	array	Folders in the given folder.
	 * @since	1.5
	 */
	public function listFolderTree($path, $filter, $maxLevel = 3, $level = 0, $parent = 0) {
		$dirs = array ();
		if ($level == 0) {
			$GLOBALS['_mezunFolder_folder_tree_index'] = 0;
		}
		if ($level < $maxLevel) {
			$folders = mezunFolder::folders($path, $filter);
			// first path, index foldernames
			foreach ($folders as $name) {
				$id = ++$GLOBALS['_mezunFolder_folder_tree_index'];
				$fullName = mezunPath::clean($path . DS . $name);
				$dirs[] = array(
					'id' => $id,
					'parent' => $parent,
					'name' => $name,
					'fullname' => $fullName,
					'relname' => str_replace(ABSPATH, '', $fullName)
				);
				$dirs2 = mezunFolder::listFolderTree($fullName, $filter, $maxLevel, $level + 1, $id);
				$dirs = array_merge($dirs, $dirs2);
			}
		}
		return $dirs;
	}

	/**
	 * Makes path name safe to use.
	 *
	 * @access	public
	 * @param	string The full path to sanitise.
	 * @return	string The sanitised string.
	 * @since	1.5
	 */
	public function makeSafe($path) {
		$ds = (DS == '\\') ? '\\' . DS : DS;
		$regex = array('#[^A-Za-z0-9:\_\-' . $ds . ' ]#');
		return preg_replace($regex, '', $path);
	}

}