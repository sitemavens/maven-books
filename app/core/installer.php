<?php

namespace MavenBooks\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class Installer {

	public function __construct () {
		;
	}

	public function install () {

		global $wpdb;

		$create = array(
			"
				CREATE TABLE `mvn_books` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(500) NOT NULL,
				  `description` text NOT NULL,
				  `reserved` tinyint(1) NOT NULL,
				  `special` tinyint(1) NOT NULL,
				  `date_imprinted` tinyint(1) NOT NULL,
				  `footnote` text NOT NULL,
				  `imprint` text NOT NULL,
				  `reservation_password` varchar(100) NOT NULL,
				  `featured` int(11) NOT NULL,
				  `isbn` varchar(100) NOT NULL,
				  `publication_date` varchar(100) NOT NULL,
				  `publication_place` varchar(500) NOT NULL,
				  `publication_year` int(4) NOT NULL,
				  `bibliography` varchar(100) NOT NULL,
				  `author` varchar(100) NOT NULL,
				  `subtitle` text NOT NULL,
				  `inventory_id` int(11) NOT NULL,
				  `price` float NOT NULL,
				  `sale_price` float NOT NULL,
				  `stock_enabled` tinyint(1) NOT NULL,
				  `stock_quantity` smallint NOT NULL,
				  `url` varchar(500) NOT NULL,
				  PRIMARY KEY (`id`)
				) "
		);

		foreach ( $create AS $sql ) {
			if ( trim( $sql ) ) {
				if ( $wpdb->query( $sql ) === false ) {
					return false;
				}
			}
		}
	}

	public function uninstall () {

		global $wpdb;

		$settings = \MavenBooks\Settings\BooksRegistry::instance();
		$settings->reset();
		//To danger to remove the tables in the uninstall process
		$drop = array(
		);


		foreach ( $drop AS $sql ) {
			if ( $wpdb->query( $sql ) === false ) {
				return false;
			}
		}
	}

}
