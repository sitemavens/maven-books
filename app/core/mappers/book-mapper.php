<?php

namespace MavenBooks\Core\Mappers;

use \MavenBooks\Core\BooksConfig;
use \MavenBooks\Core\Domain\BookFilterType;

class BookMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( \MavenBooks\Core\BooksConfig::bookTableName );
	}

	public function getAll( $orderBy = "name" ) {

		$books = array();
		$results = $this->getResults( $orderBy );

		foreach ( $results as $row ) {
			$book = new \MavenBooks\Core\Domain\Book();
			$this->fillObject( $book, $row );
			$books[] = $book;
		}

		return $books;
	}

	public function getBooksCount( $type ) {

		$where = $this->getWhereByType( $type );

		if ( $where )
			$where = ' and ' . $where;

		$query = "select count(*)
					from {$this->tableName} 
					where  1=1 
					{$where}";

		return $this->getVar( $query );
	}

	/**
	 * Return a event object
	 * @param int $id/array
	 * @param bool $readWpPost
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public function get( $id, $readWpPost = true ) {

		$book = new \MavenBooks\Core\Domain\Book();

		if ( ! $id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}


		$row = $this->getRowById( $id );

		if ( ! $row ) {
			return $book;
		}

		if ( $readWpPost ) {
			$postBook = get_post( $id );

			if ( $postBook ) {
				$book->setUrl( $postBook->post_name );
			}
		}

		$this->fillObject( $book, $row );

		return $book;
	}
	
	/**
	 * Return a event object
	 * @param int $id/array
	 * @param bool $readWpPost
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public function getBy( $column, $value, $format = '%d' ) {

		$book = new \MavenBooks\Core\Domain\Book();

		$row = $this->getRowBy($column, $value, $format);

		if ( ! $row ) {
			return $book;
		}

		$this->fillObject( $book, $row );

		return $book;
	}

	public function remove( $id ) {

		if ( ! $id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		//delete event
		$this->deleteRow( $id );

		 
	}

	/** Create or update the donation to the database
	 * 
	 * @param \MavenBooks\Core\Domain\Book $book
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public function save( \MavenBooks\Core\Domain\Book $book ) {

		$book->sanitize();
		$bookData = array(
		    'id' => $book->getId(),
		    'name' => $book->getName(),
		    'description' => $book->getDescription(),
		    'price' => $book->getPrice(),
			'sale_price' => $book->getSalePrice(),
		    'reserved' => $book->isReserved() ? 1 : 0,
			'special' => $book->isSpecial() ? 1 : 0,
			'date_imprinted' => $book->isDateImprinted() ? 1 : 0,
			'footnote' => $book->getFootnote(),
			'imprint' => $book->getImprint(),
		    'reservation_password' => $book->getReservationPassword(),
		    'isbn' => $book->getIsbn(),
		    'publication_date' => $book->getPublicationDate(),
			'publication_date' => $book->getPublicationDate(),
		    'publication_year' => $book->getPublicationYear(),
			'bibliography' => $book->getBibliography(),
			'author' => $book->getAuthor(),
			'subtitle' => $book->getSubtitle(),
		    'stock_enabled' => $book->isStockEnabled() ? 1 : 0,
		    'stock_quantity' => $book->getStockQuantity(),
		    'inventory_id' => $book->getInventoryId(),
		    'featured' => $book->isFeatured() ? 1 : 0,
		    'url' => $book->getUrl()
		);

		$format = array(
		    '%d', //id
		    '%s', //name
		    '%s', //description
		    '%f', //price
			'%f', //price
		    '%d', //reserved
			'%d', //special
			'%d', //date_imprinted
			'%s', //footnote
			'%s', //imprint
		    '%s', //reservation_password
		    '%s', //isbn
		    '%s', //publication_date
		    '%s', //publication_place
			'%d', //publication_year
			'%s', //bibliography
			'%s', //author
			'%s', //subtitle
		    '%d', //stock_enabled
		    '%d', //stock_quantity
		    '%s', //inventory_id
		    '%d', //featured
		    '%s' //url,
		);

		$columns = '';
		$values = '';
		$updateValues = '';
		$i = 0;

		foreach ( $bookData as $key => $value ) {
			$columns = $columns ? $columns . ", " . $key : $key;
			$values = $values ? $values . ", " . $format[ $i ] : $format[ $i ];
			$updateValues = $updateValues ? $updateValues . ", " . "{$key}=values({$key})" : "{$key}=values({$key})";
			$i ++;
		}

		$query = $this->prepare( "INSERT INTO {$this->tableName} ({$columns}) VALUES ($values)
					ON DUPLICATE KEY UPDATE {$updateValues};", array_values( $bookData ) );
		//die($query);
		$this->executeQuery( $query );

		return $book;
	}

	public function fill( $object, $row ) {
		$this->fillObject( $object, $row );
	}

}
