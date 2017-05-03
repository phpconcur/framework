<?php

namespace Concur;

class Generator {
	static function Iterator($path, $baseroute ='', $importincludes = false) {
		$loader = new \Twig_Loader_Filesystem ( __DIR__ . DIRECTORY_SEPARATOR );
		$twig = new \Twig_Environment ( $loader );
		
		$templates = [ ];
		$routes = [ ];
		$includes = [ ];
		$autoloads = [ ];
		
		foreach ( new \DirectoryIterator ( $path ) as $fileinfo ) {
			try {
				
				if (! $fileinfo->isDot () and $fileinfo->isDir ()) {
					
					$options = [ ];
					$options ['autoload'] = true;
					$options ['routegroup'] = '/' . $fileinfo->getFilename ();
					$options ['templates'] = [ 
							"dir" => "templates",
							"namespace" => $fileinfo->getFilename () 
					];
					$options ['includes'] = [ ];
					
					if (file_exists ( $fileinfo->getPathname () . DIRECTORY_SEPARATOR . $fileinfo->getFilename () . '.json' )) {
						// array_merge
						$jsonopions = json_decode ( file_get_contents ( $fileinfo->getPathname () . DIRECTORY_SEPARATOR . $fileinfo->getFilename () . '.json' ), true );
						if (! is_array ( $jsonopions )) {
							throw new \Exception ( "Invalid JSON File (" . $fileinfo->getFilename () . ") " );
						}
						$options = array_merge ( $options, $jsonopions );
					}
					
					if ($options ['autoload']) {
						$autoloads [$fileinfo->getFilename ()] = (is_string ( $options ['autoload'] )) ? DIRECTORY_SEPARATOR . $fileinfo->getFilename () . DIRECTORY_SEPARATOR . $options ['autoload'] : "";
					}
					if (! ($options ['templates'] === false)) {
						$templatespath = (! empty ( $options ['templates'] ['dir'] )) ? DIRECTORY_SEPARATOR . $options ['templates'] ['dir'] : "";
						if (file_exists ( $fileinfo->getPathname () . $templatespath )) {
							$templates [] = [ 
									$options ['templates'] ['namespace'],
									DIRECTORY_SEPARATOR . $fileinfo->getFilename () . $templatespath 
							];
						}
					}
					if (file_exists ( $fileinfo->getPathname () . DIRECTORY_SEPARATOR . $fileinfo->getFilename () . '.php' )) {
						// 'Include' => file_get_contents ( $path . $value ),
						
						$routes [$options ['routegroup']] = DIRECTORY_SEPARATOR . $fileinfo->getFilename () . DIRECTORY_SEPARATOR . $fileinfo->getFilename () . '.php';
					}
					
					foreach ( $options ['includes'] as $inc ) {
						if (! file_exists ( $fileinfo->getPathname () . DIRECTORY_SEPARATOR . $inc )) {
							throw new \exception ( $fileinfo->getFilename () . " Includes: File Not Found, $inc" );
						}
						$includes [] = DIRECTORY_SEPARATOR . $fileinfo->getFilename () . DIRECTORY_SEPARATOR . $inc;
					}
				}
			} catch ( \Exception $e ) {
				throw new \Exception ( "Error (" . $fileinfo->getFilename () . ") " . $e->getMessage () );
			}
		}
		
		$out = $twig->render ( "templates/autoload.twig", compact ( [ 
				'routes',
				'includes',
				'templates',
				'autoloads',
				'path',
				'baseroute'
		] ) );
		
		file_put_contents ( $path . 'autoload.php', $out );
		
		return true;
	}
}

?>