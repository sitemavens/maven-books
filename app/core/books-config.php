<?php

namespace MavenBooks\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class BooksConfig {

	const bookTypeName = 'mvn_book';
	const bookCategoryName = 'mvnb_category';
	const bookTableName = 'mvn_books';

	public static function init () {

		\Maven\Core\HookManager::instance()->addInit( array( __CLASS__, 'registerTypes' ) );
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
			'rewrite' => array( 'slug' => $slug, 'with_front' => false )
		);

		register_post_type( BooksConfig::bookTypeName, $args );

		// Add category taxonomy. It's not hierarchical
		$labels = array(
			'name' => _x( 'Category', 'taxonomy general name' ),
			'singular_name' => _x( 'Category', 'taxonomy singular name' ),
			'search_items' => __( 'Search Book Categories' ),
			'popular_items' => __( 'Popular Book Categories' ),
			'all_items' => __( 'All Book Categories' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Category' ),
			'update_item' => __( 'Update Category' ),
			'add_new_item' => __( 'Add New Category' ),
			'new_item_name' => __( 'New Category Name' ),
			'separate_items_with_commas' => __( 'Separate book categories with commas' ),
			'add_or_remove_items' => __( 'Add or remove book categories' ),
			'choose_from_most_used' => __( 'Choose from the most used book categories' ),
			'not_found' => __( 'No book category found.' ),
			'menu_name' => __( 'Book Categories' )
		);

		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var' => true,
			'rewrite' => array( 'slug' => 'book-category' )
		);

		register_taxonomy( BooksConfig::bookCategoryName, BooksConfig::bookTypeName, $args );


  
	}

}

BooksConfig::init();


