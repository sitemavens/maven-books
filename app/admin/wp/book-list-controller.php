<?php

namespace MavenBooks\Admin\Wp;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class EventListController extends \MavenBooks\Admin\EventsAdminController {

	private $page_hook = '';

	public function __construct() {
		parent::__construct();
	}

	public function init() {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$this->getHookManager()->addAction( 'admin_menu', array( $this, 'register_menu_page' ) );
		$this->getHookManager()->addAction( 'admin_enqueue_scripts', array( $this, 'addScripts' ), 10, 1 );

		$this->getHookManager()->addAjaxAction( 'mvn_getEventsList', array( $this, 'getEvents' ) );
	}

	function addScripts( $hook ) {

		global $post;
		//var_dump( $hook );
		if ( $hook == $this->page_hook ) {

			$registry = \MavenBooks\Settings\BooksRegistry::instance();

			wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-google-chart', $registry->getBowerComponentUrl() . "angular-google-chart/ng-google-chart.js", 'angular', $registry->getPluginVersion() );

			wp_enqueue_script( 'mavenEventsListApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/services/events.js', $registry->getScriptsUrl() . "admin/services/events.js", 'mavenEventsListApp', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/event-list.js', $registry->getScriptsUrl() . "admin/controllers/event-list.js", 'mavenEventsListApp', $registry->getPluginVersion() );
		}
	}

	// Add the Events Meta Boxes
	function addEvents() {
		add_meta_box( 'wpt_events_location', 'Event Information', array( $this, 'showEvents' ), \MavenBooks\Core\EventsConfig::eventTypeName, 'normal', 'default' );
	}

	// The Event Location Metabox
	function register_menu_page() {

		$me = new self();
		$this->page_hook = \add_menu_page( 'Events&copy;', 'Events&copy;', 'manage_options', 'mvn_events_list', array( $me, 'showList' ) );

		//var_dump( $this->page_hook );
	}

	/**
	 * update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save( $postId, $post ) {
		
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert( $postId, $post ) {
		
	}

	/**
	 * Delete a Maven Category
	 * @param int $termId
	 * @param int $taxonomyId
	 * @param object $deletedTerm
	 */
	public function delete( $termId, $taxonomyId, $deletedTerm ) {
		
	}

	public function showForm() {
		
	}

	public function showList() {
		echo $this->getOutput()->getWpAdminView( "event-list" );
	}

	public function getEvents() {
		$manager = new \MavenBooks\Core\EventManager();

		$events = $manager->getAll();

		$this->getOutput()->sendData( $events );
	}

}
