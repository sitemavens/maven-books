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
			" "
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
