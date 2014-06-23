<?php

namespace MavenBooks\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class BookOrderItem extends \Maven\Core\Domain\OrderItem {
	
	/**
	 *
	 * @var \MavenBooks\Core\Domain\Book
	 */
	private $book;
	
	
	/**
	 * @serialized
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public function getBook() {
		return $this->book;
	}

	/**
	 * 
	 * @param \MavenBooks\Core\Domain\Book $book
	 */
	public function setBook( \MavenBooks\Core\Domain\Book $book ) {
		$this->book = $book;
	}
	 
	
}
