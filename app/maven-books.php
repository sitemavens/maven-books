<?php

/*
  Plugin Name: Maven Books
  Plugin URI:
  Description: Maven Books
  Author: SiteMavens.com
  Version: 0.1
  Author URI: http://www.sitemavens.com/
 */

namespace MavenBooks;

use Maven\Core\Loader;

//If the validation was already loaded
if ( !class_exists( 'MavenValidation' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'maven-validation.php';
}

// Check if Maven is activate, if not, just return.
if ( \MavenValidation::isMavenMissing() )
	return;

//Added actions class here, because there are issues with ReflectionClass on Settings controller
Loader::load( plugin_dir_path( __FILE__ ), array( 'settings/books-registry', 'core/actions', 'core/taxonomies-config' ) );

// Instanciate the registry and set all the plugins attributes
$registry = Settings\BooksRegistry::instance();

$registry->setPluginDirectoryName( "maven-books" );
$registry->setPluginDir( plugin_dir_path( __FILE__ ) );
$registry->setPluginUrl( defined( 'DEV_ENV' ) && DEV_ENV ? WP_PLUGIN_URL . "/maven-books/" : plugin_dir_url( __FILE__ )  );
$registry->setPluginName( 'Maven Books' );
$registry->setPluginShortName( 'mb' );
$registry->setPluginVersion( "0.1" );
$registry->setRequest( new \Maven\Core\Request() );
//$registry->setMail( new \Maven\Core\Mail() );

$registry->init();

/**
 * We need to register the namespace of the plugin. It will be used for autoload function to add the required files. 
 */
Loader::registerType( "MavenBooks", $registry->getPluginDir() );

Loader::load( $registry->getPluginDir(), 'core/installer.php' );

/**
 * 
 * Instantiate the installer
 *
 * * */
$installer = new \MavenBooks\Core\Installer();
register_activation_hook( __FILE__, array( &$installer, 'install' ) );
register_deactivation_hook( __FILE__, array( &$installer, 'uninstall' ) );

/**
 *  Create the Director and the plugin
 */
$director = \Maven\Core\Director::getInstance();

$director->createPluginElements( $registry );

// We need to initialize the custom post types
Core\BooksConfig::init();

//Register actions and filters for external process in gateway
$hookManager = $director->getHookManager( $registry );

$hookManager->addFilter( 'maven\core\intelligenceReport:data', array( 'MavenBooks\\Core\\IntelligenceReport', 'generateData' ), 10, 2 );

Api\Loader::init();

Front\BooksFrontEnd::registerFrontEndHooks();

//$hookManager->addInit( array( 'MavenBooks\Front\ShopFrontEnd','init' ) );
// Load admin scripts, if we are in the admin 
if ( is_admin() ) {

	Admin\Main::init();
}


