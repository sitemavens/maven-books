<?php

namespace MavenBooks\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class BooksConfig {

	const bookTypeName = 'mvn_book';
	const bookCategoryName = 'mvnb_category';
	const bookAuthorName = 'mvnb_author';
	const bookTableName = 'mvn_books';

	public static function init () {

		\Maven\Core\HookManager::instance()->addInit( array( __CLASS__, 'registerTypes' ) );
		\Maven\Core\HookManager::instance()->addFilter('post_type_link', array(__CLASS__, 'getBookPermalink'), 1, 4);
		\Maven\Core\HookManager::instance()->addAction('registered_post_type', array(__CLASS__, 'registeredPostType'), 1, 4);
        \Maven\Core\HookManager::instance()->addAction( 'maven/cart/itemPaid/maven-books', array( __CLASS__, 'paidBook' ), 10, 1 );
        \Maven\Core\HookManager::instance()->addAction( 'maven/cart/checkStock', array( __CLASS__, 'checkBooksStock' ), 10, 1 );

	}

	static function registerTypes () {
		// Add books
		$labels = array(
			'name' => _x( 'Books', 'Post Type General Name', 'text_domain' ),
			'singular_name' => _x( 'Book', 'Post Type Singular Name', 'text_domain' ),
			'menu_name' => __( 'Books', 'text_domain' ),
			'parent_item_colon' => __( 'Parent Book:', 'text_domain' ),
			'all_items' => __( 'All Books', 'text_domain' ),
			'view_item' => __( 'View Book', 'text_domain' ),
			'add_new_item' => __( 'Add New Book', 'text_domain' ),
			'add_new' => __( 'New Book', 'text_domain' ),
			'edit_item' => __( 'Edit Book', 'text_domain' ),
			'update_item' => __( 'Update Book', 'text_domain' ),
			'search_items' => __( 'Search books', 'text_domain' ),
			'not_found' => __( 'No books found', 'text_domain' ),
			'not_found_in_trash' => __( 'No books found in Trash', 'text_domain' ),
		);

		$registry = \MavenBooks\Settings\BooksRegistry::instance();
		$prefix = $registry->getBooksSlugPrefix();

		$slug = $registry->getBooksSlug();

		if ( $prefix ) {
			$slug = "{$prefix}/{$slug}";
		}

		$slug = \Maven\Core\HookManager::instance()->applyFilters( 'maven-books/config/slug', $slug );

		$args = array(
			'label' => __( 'mvn_book', 'text_domain' ),
			'description' => __( 'Books', 'text_domain' ),
			'labels' => $labels,
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			//'taxonomies' => array( 'mvn_venue' ),
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 5,
			'menu_icon' => $registry->getImagesUrl() . "icon.png",
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
			'permalink_epmask' => EP_ALL,
			'rewrite' => array( 'slug' => $slug, 'with_front' => false )
		);

		register_post_type( BooksConfig::bookTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
			'name' => _x( 'Book Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Book Category', 'taxonomy singular name' ),
			'search_items' => __( 'Search Book Categories' ),
			'popular_items' => __( 'Popular Book Categories' ),
			'all_items' => __( 'All Book Categories' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Book Category' ),
			'update_item' => __( 'Update Book Category' ),
			'add_new_item' => __( 'Add New Book Category' ),
			'new_item_name' => __( 'New Book Category Name' ),
			'separate_items_with_commas' => __( 'Separate book categories with commas' ),
			'add_or_remove_items' => __( 'Add or remove book categories' ),
			'choose_from_most_used' => __( 'Choose from the most used book categories' ),
			'not_found' => __( 'No book category found.' ),
			'menu_name' => __( 'Book Categories' )
		);

                $catSlug = \Maven\Core\HookManager::instance()->applyFilters( 'maven-books/config/catSlug', 'book-category' );
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => $catSlug )
		);

		register_taxonomy( BooksConfig::bookCategoryName, BooksConfig::bookTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
			'name' => _x( 'Book Author', 'taxonomy general name' ),
			'singular_name' => _x( 'Book Author', 'taxonomy singular name' ),
			'search_items' => __( 'Search Book Authors' ),
			'popular_items' => __( 'Popular Book Authors' ),
			'all_items' => __( 'All Book Authors' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Book Author' ),
			'update_item' => __( 'Update Book Author' ),
			'add_new_item' => __( 'Add New Book Author' ),
			'new_item_name' => __( 'New Book Author Name' ),
			'separate_items_with_commas' => __( 'Separate book authors with commas' ),
			'add_or_remove_items' => __( 'Add or remove book authors' ),
			'choose_from_most_used' => __( 'Choose from the most used book authors' ),
			'not_found' => __( 'No book author found.' ),
			'menu_name' => __( 'Book Authors' )
		);

                $authorSlug = \Maven\Core\HookManager::instance()->applyFilters( 'maven-books/config/authorSlug', 'book-author' );
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => $authorSlug )
		);

		register_taxonomy( BooksConfig::bookAuthorName, BooksConfig::bookTypeName, $args );
	}

	/**
	 *
	 * registered_post_type
	 *  ** add rewrite tag for Custom Post Type.
	 *
	 */

	public static function registeredPostType( $postType, $args ) {

		if( $postType !== self::bookTypeName || $args->_builtin || !$args->publicly_queryable || !$args->show_ui ){
			return false;
		}
 
		$rewrite_args = $args->rewrite;
		if( !is_array($rewrite_args) ) {
			$rewrite_args  = array( 'with_front' => $args->rewrite );
		}

		$rewrite_args["walk_dirs"] = false;
		add_permastruct( $postType, apply_filters( 'maven-books/config/permalink-structure', $args->rewrite['slug'] . '/%'.$postType.'%' ), $rewrite_args);
 
	}
    
    public static function paidBook ( $orderItem ) {
        
        $bookManager = new \MavenBooks\Core\BookManager();

		$book = $bookManager->get( $orderItem->getThingId());
        if ($book->isStockEnabled() && $book->getStockQuantity() > 0) {
            $book->setStockQuantity($book->getStockQuantity() - 1);
        }
        $bookManager->addBook($book);
	}
    
    public static function checkBooksStock ( $orderItems ) {
        $bookManager = new \MavenBooks\Core\BookManager();
        $hasStock = true;
        foreach($orderItems as $orderItem){
            if ($hasStock) {
                $book = $bookManager->get( $orderItem->getThingId());
                if ($book->isStockEnabled() && $book->getStockQuantity() == 0) {
                    $hasStock = false;
                }
            }
        }
        return $hasStock;
	}

	
	public static function getBookPermalink($postLink, $post, $leaveName) {
		global $wp_rewrite;
		
		if( is_object($post) ){
			$id = $post->ID;
		}else if ( is_numeric($post) ){
			$id = $post;
			$post = get_post($id);
		}else{
			return $postLink;
		}
			
		if ( $post->post_type != self::bookTypeName ){
			return $postLink;
		}
		
		$permalinkStructure = get_option( 'permalink_structure' );
		if ( $permalinkStructure != '' ) {
//		if ( $permalinkStructure != '' && !in_array( $post->post_status, array( 'draft', 'pending' ) ) ) {
			$bookAuthors = wp_get_object_terms( $id, self::bookAuthorName );
			
			$bookAuthorsSlugs = array( );
			foreach ( $bookAuthors as $bookAuthor ) {
				$bookAuthorsSlugs[] = $bookAuthor->slug;
			}
			
			$authorSlug = '';
			if ( count( $bookAuthors ) > 1 ) {
				if ( ( $currentAuthor = get_query_var( self::bookAuthorName ) ) && in_array( $currentAuthor, $bookAuthorsSlugs ) ){
					$authorSlug = $currentAuthor;
				}else{
					$authorSlug = $bookAuthors[0]->slug;
				}
			} else {
				// If the product is associated with only one category, we only have one choice

				$bookAuthor = !isset( $bookAuthors[0] ) ? '' : $bookAuthors[0];

				if ( is_object( $bookAuthor ) && !empty( $bookAuthor->slug ) ){
					$authorSlug = $bookAuthor->slug;
				}
			}

			if( empty($authorSlug) ){ 
				$authorSlug = 'unauthored';
			}
			 
			$newlinkStructure = $wp_rewrite->get_extra_permastruct( self::bookTypeName );
			$arguments = array( 'variables' => array( '%'.self::bookAuthorName.'%', '%post_id%'), 'values' => array( self::bookAuthorName => $authorSlug, 'post_id' => $post->ID ) );
			if( !$leaveName ){
				$arguments['variables'][] = '%'.self::bookTypeName.'%';
				$arguments['values']['self::bookTypeName'] = $post->post_name;
			}
			$newlinkStructureArray = apply_filters( 'maven-books/config/permalink-variables', $arguments, $newlinkStructure, $post );
			
			
			$newlinkStructureVariables = $newlinkStructureArray['variables'];
			$newlinkStructureReplace = $newlinkStructureArray['values'];
			$newlink = str_replace( $newlinkStructureVariables, $newlinkStructureReplace, $newlinkStructure );
			$postLink = home_url( user_trailingslashit( $newlink, 'single' ) );
		}
		return apply_filters( 'maven-books/config/permalink', $postLink, $post->ID );
	}

}

BooksConfig::init();


