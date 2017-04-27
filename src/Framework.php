<?php

namespace Concur;

class Framework {
	
	/**
	 *
	 * @param String $root        	
	 */
	static function defaultSettings($root) {
		return [ 
				'settings' => [ 
						'site_root' => $root .DIRECTORY_SEPARATOR,
						'concur_path' => $root . DIRECTORY_SEPARATOR . 'Concur' . DIRECTORY_SEPARATOR,
						'cache_path' => $root . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR,
						
						'displayErrorDetails' => true,
						'determineRouteBeforeAppMiddleware'=>true,
						
						// Renderer settings
						'renderer' => [ 
								'template_path' => $root . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
								'cache_path' => $root . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR 
						],
						
						// Monolog settings
						'logger' => [ 
								'name' => 'slim-app',
								'path' => $root . '/logs/app.log' 
						] 
				] 
		];
	}
}
?>