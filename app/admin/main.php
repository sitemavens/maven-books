<?php

namespace MavenBooks\Admin;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Main {

	public static function init() {

		$bookController = new \MavenBooks\Admin\Wp\BookController();
		$bookController->init();

		//$eventListController = new Wp\EventListController();
		//$eventListController->init();
	}

	  

}
