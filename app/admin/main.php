<?php

namespace MavenBooks\Admin;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Main {

	public static function init() {

		$bookController = new \MavenBooks\Admin\Wp\BookController();
		$bookController->init();
		
		$taxonomiesController = new \MavenBooks\Admin\Wp\TaxonomiesController();
		$taxonomiesController->init();

		//$eventListController = new Wp\EventListController();
		//$eventListController->init();
	}

	  

}
