<?php

namespace MavenBooks\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class BooksApi {

	/**
	 * 
	 * @param \MavenBooks\Core\BookFilter $filter
	 * @return \MavenBooks\Core\Domain\Book[]
	 */
	public static function getBooks( \MavenBooks\Core\BookFilter $filter ) {

		$manager = new BookManager();
		
		return $manager->getBooks( $filter );
			
	}
	
	
	
	/**
	 * 
	 * @param int/object $book
	 */
	public static function getBook( $book ){
		
		if ( !$book ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Book is required. It can be an id or a wp post' );
		}


		$manager = new \MavenBooks\Core\BookManager();
		
		if ( is_object( $book ) && isset( $book->ID ) ) {
			return $manager->getBookFromPost( $book );
		} else if ( is_numeric( $book ) ) {
			return $manager->get( $book );
		} else {
			return $manager->getBookBySlug( $book );
		}

		throw new \Maven\Exceptions\MavenException('Invalid event');
	}
	/**
	 * 
	 * @param int/object $book
	 */
	public static function getBookBy( $column, $value, $format = '%d' ){
		
		$manager = new \MavenBooks\Core\BookManager();
		
		return $manager->getBookBy($column, $value, $format);
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
		
		$manager = new \MavenBooks\Core\BookManager();
		
		return $manager->addBook( $book );
		
	}
   

}