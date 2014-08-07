<?php

namespace MavenBooks\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class BooksApi {

	private static $bookManager;
	
	/**
	 * 
	 * @param \MavenBooks\Core\BookFilter $filter
	 * @return \MavenBooks\Core\Domain\Book[]
	 */
	public static function getBooks( \MavenBooks\Core\BookFilter $filter ) {

		return self::getBookManager()->getBooks( $filter );
			
	}
	
	/**
	 * 
	 * @return \MavenBooks\Core\BookManager
	 */
	private static function getBookManager(){
		
		if ( ! self::$bookManager ){
			self::$bookManager = new BookManager();
		
		}
		
		return self::$bookManager;
	}
	
	
	/**
	 * 
	 * @param string $property
	 * @param mix $value
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public static function getBookByProperty( $property, $value ){
			 
		return self::getBookManager()->getBookByProperty($property, $value);
	}
	
	/**
	 *  Get Book
	 * @param int/object $book
	 * @return \MavenBooks\Core\Domain\Book
	 * @throws \Maven\Exceptions\MissingParameterException
	 * @throws \Maven\Exceptions\MavenException
	 */
	public static function getBook( $book ){
		
		if ( !$book ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Book is required. It can be an id or a wp post' );
		}
 
		if ( is_object( $book ) && isset( $book->ID ) ) {
			return self::getBookManager()->getBookFromPost( $book );
		} else if ( is_numeric( $book ) ) {
			return self::getBookManager()->get( $book );
		} else {
			return self::getBookManager()->getBookBySlug( $book );
		}

		throw new \Maven\Exceptions\MavenException('Invalid event');
	}
	 

	/**
	 * Create a new filter
	 * @return \MavenBooks\Core\Domain\BookFilter
	 */
	public static function newFilter() {
		return new \MavenBooks\Core\Domain\BookFilter();
	}
	
	/**
	 * 
	 * @param \MavenBooks\Core\Domain\Book $book
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public static function updateBook( \MavenBooks\Core\Domain\Book $book ){
		
		return self::getBookManager()->addBook( $book );
		
	}
   

}