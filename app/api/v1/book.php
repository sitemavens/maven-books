<?php

namespace MavenBooks\Api\V1;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

/**
 * Description of observer
 *
 * @author mustela
 */
class Book {

	private static $instance;

	public static function current () {
		if ( !self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private $manager;

	public function __construct () {
		$this->manager = new \MavenBooks\Core\BookManager();
	}

	public function registerRestApi () {

		\Maven\Core\HookManager::instance()->addFilter( 'json_endpoints', array( $this, 'registerRouters' ) );
	}

	public function registerRouters ( $routes ) {

//		$routes[ '/maven-books/v1/books' ] = array(
//			array( array( $this, 'getAll' ), \WP_JSON_Server::CREATABLE | \WP_JSON_Server::ACCEPT_JSON )
//		);

		$routes[ '/maven-books/v1/books/(?P<id>\d+)' ] = array(
			array( array( $this, 'get' ), \WP_JSON_Server::READABLE )
		);


		return $routes;
	}

	public function get ( $id ) {

		$book = $this->manager->get( $id );

		if ( !$book->isEmpty() ) {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createSuccessfulMessage( 'Book found', $book ) );
		}else{
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'Book not found' ) );
		}
	}

	private function userCan () {

		if ( !current_user_can( 'edit_users' ) ) {
			$this->sendResponse( \Maven\Core\Message\MessageManager::createErrorMessage( 'You don\'t have permissions to do it' ) );
		}

		return true;
	}

	private function sendResponse ( \Maven\Core\Message\Message $result ) {

		$output = new \Maven\Core\UI\OutputTranslator();
		$transformedData = $output->convert( $result->getData() );

		if ( $result->isSuccessful() ) {
			$result = array( 'successful' => true, 'error' => false, 'description' => $result->getContent(), 'data' => $transformedData );
		} else {
			$result = array( 'successful' => false, 'error' => true, 'description' => $result->getContent(), 'data' => $transformedData );
		}

		die( json_encode( $result ) );
	}

}
