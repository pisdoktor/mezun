<?php
// no direct access
defined( 'ERISIM' ) or die( 'Bu alanı görmeye yetkiniz yok!' ); 

define('DS', '/');

if(!defined('DS')) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

class mezunImporter {
	
	static function import( $filePath, $base = null, $key = 'includes.' ) {
		static $paths;

		if (!isset($paths)) {
			$paths = array();
		}

		$keyPath = $key ? $key . $filePath : $filePath;

		if (!isset($paths[$keyPath])) {
			if ( ! $base ) {
				$base =  dirname( __FILE__ );
			}

			$parts = explode( '.', $filePath );

			$classname = array_pop( $parts );
			
			switch($classname) {
				case 'helper' :
					$classname = ucfirst(array_pop( $parts )).ucfirst($classname);
					break;

				default :
					$classname = ucfirst($classname);
					break;
			}

			$path  = str_replace( '.', DS, $filePath );

			$classname  = 'mezun'.$classname;
			$classes    = mezunImporter::register($classname, $base.DS.$path.'.php');
			$rs         = isset($classes[strtolower($classname)]);
			
			$paths[$keyPath] = $rs;
		}

		return $paths[$keyPath];
		
		
	}
	
	static function & register ($class = null, $file = null) {
		static $classes;

		if(!isset($classes)) {
			$classes    = array();
		}

		if ($class && is_file($file)) {
			// Force to lower case.
			$class = strtolower($class);
			$classes[$class] = $file;
			mezunImporter::load($class);
			
		}

		return $classes;
	}
	
	static function load( $class ) {
		$class = strtolower($class); //force to lower case

		if (class_exists($class)) {
			  return;
		}

		$classes = mezunImporter::register();
		if(array_key_exists( strtolower($class), $classes)) {
			include($classes[$class]);
			return true;
		}
		return false;
	}
}

function mimport( $path ) {
	return mezunImporter::import($path);
}
