<?php

namespace Concur;

class Cache {
	private static function getFilename($filename, $domain = '') {
		//simple check to remove ../ etc and only allow a to z and 0 to 9
		$filename = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $filename ) );
		$domain = preg_replace( '/[^a-z0-9]+/', '-', strtolower( $domain ) );
		
		$dir = realpath ( PROJECTROOT . '/cache/' );
		if (! file_exists ( $dir . '/' . $domain )) {
			mkdir ( $dir . '/' . $domain );
		}
		$dir = realpath ( $dir . '/' . $domain );
		return $dir . '/' . $filename;
	}
	static function exists($filename, $domain = '') {
		$filename = self::getFilename ( $filename, $domain );
		return file_exists ( $filename );
	}
	static function put($data, $filename, $domain = '') {
		$filename = self::getFilename ( $filename, $domain );
		file_put_contents ( $filename, $data );
		return true;
	}
	static function get($filename, $domain = '') {
		$filename = self::getFilename ( $filename, $domain );
		if (! file_exists ( $filename )) {
			return false;
		}
		return file_get_contents ( $filename );
	}
}

?>