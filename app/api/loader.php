<?php

namespace MavenBooks\Api;

class Loader {

	public static function init () {

		$hookManager = \Maven\Core\HookManager::instance();
		
		$book = V1\Book::current();
		$hookManager->addAction( 'wp_json_server_before_serve', array( $book, 'registerRestApi' ) );
		
	}

}
