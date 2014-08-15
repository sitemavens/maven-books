<?php

namespace MavenBooks\Admin\Wp;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class BookController extends \MavenBooks\Admin\BooksAdminController {

	public function __construct () {
		parent::__construct();
	}

	public function init () {


		if ( $this->getRequest()->isDoingAutoSave() ) {
			return;
		}

		$this->getHookManager()->addAction( 'current_screen', array( $this, 'currentScreen' ) );

		//post_edit_form_tag
		$this->getHookManager()->addAction( 'add_meta_boxes_' . \MavenBooks\Core\BooksConfig::bookTypeName, array( $this, 'addBooksMetaBox' ) );
		$this->getHookManager()->addAction( 'admin_enqueue_scripts', array( $this, 'addScripts' ), 10, 1 );
		//add_action('edit_form_advanced', array( $this, 'editFormBottom' ), 10, 1 );
		//add_action('add_meta_boxes',array( $this, 'editFormTop' ),10,2);
		$this->getHookManager()->addAction( 'save_post_' . \MavenBooks\Core\BooksConfig::bookTypeName, array( $this, 'save' ), 10, 2 );
		$this->getHookManager()->addAction( 'insert_post_' . \MavenBooks\Core\BooksConfig::bookTypeName, array( $this, 'insert' ), 10, 2 );
		$this->getHookManager()->addAction( 'delete_post', array( $this, 'delete' ), 10, 3 );
	}

	public function currentScreen ( $screen ) {

		if ( $screen->post_type === \MavenBooks\Core\BooksConfig::bookTypeName ) {
			$this->getHookManager()->addAction( 'admin_xml_ns', array( $this, 'adminXml' ) );
		}
	}

	function adminXml () {
		echo 'ng-app="mavenBooksApp"';
	}

	function addScripts ( $hook ) {

		global $post;

		if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
			if ( 'mvn_book' === $post->post_type ) {

				$registry = \MavenBooks\Settings\BooksRegistry::instance();

				if ( $registry->isDevEnv() ) {
					wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
					wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );

					wp_enqueue_script( 'mavenBooksApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_script( 'admin/controllers/book.js', $registry->getScriptsUrl() . "admin/controllers/book.js", 'mavenBooksApp', $registry->getPluginVersion() );


					wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
					wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

					wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
				} else {
					wp_enqueue_script( 'mainApp', $registry->getScriptsUrl() . "main.min.js", 'angular', $registry->getPluginVersion() );
					wp_enqueue_style( 'mainCss', $registry->getStylesUrl() . "main.min.css", array(), $registry->getPluginVersion() );
				}
			}
		}
	}

	// Add the Books Meta Boxes
	function addBooksMetaBox () {
		add_meta_box( 'wpt_books_location', 'Book Information', array( $this, 'showBooks' ), \MavenBooks\Core\BooksConfig::bookTypeName, 'normal', 'default' );
	}

	// The Book Location Metabox
	function showBooks () {

		global $post;

		\Maven\Loggers\Logger::log()->message( '\MavenBooks\Admin\Wp\BookController: showBooks: ' . $post->ID );

		$bookManager = new \MavenBooks\Core\BookManager();
		$book = $bookManager->get( $post->ID );

		$this->addJSONData( 'book', $book );

		echo $this->getOutput()->getWpAdminView( "book" );
	}

	/**
	 * Update a Maven product
	 * @param int $postId
	 * @param object $post
	 */
	public function save ( $postId, $post ) {

		\Maven\Loggers\Logger::log()->message( '\MavenBooks\Admin\Wp\BookController: save: ' . $postId );

		$this->saveBook( $post );
	}

	private function saveBook ( $post ) {
		$book = new \MavenBooks\Core\Domain\Book();

		$mvn = $this->getRequest()->getProperty( 'mvn' );

		//Check if we have something in the post, because it can be the quick edit mode
		if ( $mvn ) {

			\Maven\Loggers\Logger::log()->message( '\MavenBooks\Admin\Wp\BookController: saveBook: ' . $post->ID );

			$book->load( $mvn[ 'book' ] );

			$book->setId( $post->ID );
			$book->setName( $post->post_title );
			$book->setDescription( $post->post_content );

			$bookManager = new \MavenBooks\Core\BookManager();
			$bookManager->addBook( $book );
		}
	}

	/**
	 * Update a Maven product
	 * @param int $termId
	 * @param int $taxonomyId
	 */
	public function insert ( $postId, $post ) {

		\Maven\Loggers\Logger::log()->message( '\MavenBooks\Admin\Wp\BookController: insert' );

		$this->saveBook( $post );
	}

	/**
	 * Delete a Maven Category
	 * @param int $termId
	 * @param int $taxonomyId
	 * @param object $deletedTerm
	 */
	public function delete ( $postId ) {
		$post = get_post( $postId );
		if ( $post->post_type === \MavenBooks\Core\BooksConfig::bookTypeName ) {
			$bookManager = new \MavenBooks\Core\BookManager();
			$bookManager->delete( $postId );
		}
	}

	public function showForm () {
		
	}

	public function showList () {
		
	}

}
