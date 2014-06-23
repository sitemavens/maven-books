<?php

namespace MavenBooks\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

use MavenBooks\Core\Mappers\BookMapper;

class BookManager {

	private $bookMapper = null;

	public function __construct() {
		$this->bookMapper = new BookMapper();
	}

	/**
	 * 
	 * @param \MavenBooks\Core\Domain\Book $book
	 * @return \Maven\Core\Message\Message
	 */
	function addBook( \MavenBooks\Core\Domain\Book $book ) {

		//$categoriesMapper = new Mappers\CategoryMapper();

		$update = false;
		if ( $book->getId() ) {
			$update = true;
		}

		//We save the book, we need the id for the related data
		$savedBook = $this->bookMapper->save( $book );

		//$categoriesMapper->addCategories( $book->getCategories(), $savedBook );

		// If variations isn't enabled we have to delete everything. Just in case the product had variations before.
//		if ( ! $book->isVariationsEnabled() ) {
//			$variationsManager = new VariationManager();
//			$variationsManager->deleteThingVariations( $book->getId() );
//		}
		
		if ( $update )
			Actions::UpdateBook( $book );
		else
			Actions::AddBook( $book );

		return $savedBook;
	}

	public function cloneBook( $bookId ) {
		//Get the original book
		$book = $this->get( $bookId );

		//remove the Id
		$book->setId( null );

		//Prepend "Copy of" on title
		$book->setName( "Copy of " . $book->getName() );

		//close book
		$book->setClosed( true );

		$originalPosts = $book->getPostsContent();
		$postRelation = array( );
		foreach ( $originalPosts as $post ) {
			//Check if the id has already be processes, in case that the post_id are repeated
			if ( ! array_key_exists( $post[ 'id' ], $postRelation ) ) {
				$newPostId = \Maven\Core\Utils::duplicatePost( $post[ 'id' ] );
				$postRelation[ $post[ 'id' ] ] = $newPostId;
				$book->removePostContent( $post[ 'id' ] );
				$book->addPostContent( $newPostId );
			} else {
				$book->removePostContent( $post[ 'id' ] );
			}
		}

		//save the new book
		$this->addBook( $book );

		//Associate the book id to the created posts
		foreach ( $book->getPostsContent() as $post ) {
			//Asociate the new book with the post
			update_post_meta( $post[ 'id' ], BooksContent::bookColumnName, $book->getId() );

			//Fix the parent relation between the new posts
			if ( $post[ 'parent' ] ) {
				\Maven\Core\Utils::updatePostParent( $post[ 'id' ], $postRelation[ $post[ 'parent' ] ] );
			}
		}

		return $book;
	}


	/**
	 * Get an book
	 * @param int $bookId
	 * @return \MavenBooks\Core\Domain\Book
	 */
	public function get( $bookId ) {

		if ( intval( $bookId ) === 0 ) {
			throw new \Maven\Exceptions\MissingParameterException( "Book id is required" );
		}

		$book = $this->bookMapper->get( $bookId );


		return $book;
	}

	/**
	 * 
	 * @param string $slug
	 * @return \MavenBooks\Core\Domain\Book
	 * @throws \Maven\Exceptions\NotFoundException
	 */
	public function getBookBySlug( $slug ) {

		$post = get_page_by_path( $slug, OBJECT, BooksConfig::bookTypeName );

		if ( !$post ) {
			throw new \Maven\Exceptions\NotFoundException( 'Post not found: ' . $slug );
		}

		return $this->getBookFromPost( $post );
	}

	public function getBookFromPost( $wpBookPost ) {

		if ( !is_object( $wpBookPost ) || !isset( $wpBookPost->ID ) ) {
			throw new \Maven\Exceptions\MissingParameterException( "Book post is required" );
		}

		$book = $this->bookMapper->get( $wpBookPost->ID, false );

		if ( !$book ) {
			throw new \Maven\Exceptions\NotFoundException( 'Book not found' );
		}

		$book->setUrl( $wpBookPost->post_name );

		return $book;
	}
 

	/**
	 * 
	 * @param string $type
	 * @param string $orderBy
	 * @param string $orderType
	 * @param int $start
	 * @param int $limit
	 * @return \MavenBooks\Core\Domain\Book[]
	 */
	public function getBooksByType( $type, $orderBy = 'book_start_date', $orderType = 'desc', $start = 0, $limit = 100 ) {

		$books = $this->bookMapper->getBooksByType( $type, $orderBy, $orderType, $start, $limit );

		foreach ( $books as $book ) {
			$book = $this->loadBookInformation( $book );
		}

		return $books;
	}

	/**
	 * Get all books
	 * @return \MavenBooks\Core\Domain\Book[]
	 */
	public function getAll() {

		$filter = new \MavenBooks\Core\Domain\BookFilter();
		$filter->setType( Domain\BookFilterType::all );

		return $this->getBooks( $filter );
	}

	/**
	 * 
	 * @param \MavenBooks\Core\Domain\BookFilter $filter
	 * @return \MavenBooks\Core\Domain\Book[]
	 */
	public function getBooks( \MavenBooks\Core\Domain\BookFilter $filter, $orderBy = 'name', $orderType = 'asc', $start = 0, $limit = 1000 ) {

		if ( $filter->getType() ) {
			return $this->getBooksByType( $filter->getType(), $orderBy, $orderType, $start, $limit );
		}
	}

	public function getBooksCount( \MavenBooks\Core\Domain\BookFilter $filter ) {
		return $this->bookMapper->getBooksCount( $filter->getType() );
	}
 
	public function delete( $id ) {

		if ( !$id ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id is required' );
		}

		return $this->bookMapper->remove( $id );
	}
 

}

