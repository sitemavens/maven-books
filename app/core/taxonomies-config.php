<?php

namespace MavenBooks\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class TaxonomiesConfig {

	public static function init () {
		\Maven\Core\HookManager::instance()->addInit( array( __CLASS__, 'registerMetaTable' ) );
	}

	/**
	 * Add taxonomy metatable to wp tables
	 * @global obj $wpdb 
	 */
	
	public static	function registerMetaTable() {
		global $wpdb;
		$wpdb->term_taxonomymeta = "{$wpdb->prefix}term_taxonomymeta";
	}
}

TaxonomiesConfig::init();