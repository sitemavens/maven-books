<?php

/**
 * Description of mvn_shop_admin_product_taxonomies
 *
 * @author Guillermo Tenaschuk
 */

namespace MavenBooks\Admin\Wp;

use MavenBooks\Core\BooksConfig;
use MavenBooks\Core\TaxonomiesManager;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class TaxonomiesController extends \MavenBooks\Admin\BooksAdminController {

	const action_add = 'mvnTaxonomyAdd';
	const action_edit = 'mvnTaxonomyEdit';
	const action_remove = 'mvnTaxonomyRemove';
	const action_remove_field = 'mvnTaxonomyFieldRemove';

	/**
	 * Used for singleton class
	 * @staticvar instance
	 */
	static $instance = null;

	/**
	 * @var constant standarize the save action key
	 */
	const save_action = 'mvnTaxonomySaveData';

	/**
	 * Used to keep a singleton of the current class
	 * @return Class
	 */
	public static function &instance () {
		if ( !self::$instance ) {
			$class = __CLASS__;
			self::$instance = new $class;
		}

		return self::$instance;
	}

	/**
	 * Construct the class and initialize some parameters for this one
	 */
	public function __construct () {
		parent::__construct();
	}

	function init () {
		//TODO: analize if we should add the folowing instructions just when it is a Maven Shop Taxonomy
		// if so remember that there are taxonomies registered with filters/actions

		add_filter( 'admin_print_styles', array( &$this, 'adminPrintStyles' ) );
		add_filter( 'admin_print_scripts', array( &$this, 'adminPrintScripts' ) );
		/* ---- Category custom info ---- */
		add_action( BooksConfig::bookCategoryName . '_add_form_fields', array( &$this, 'addCategoryForm' ), 11 );
		add_action( BooksConfig::bookCategoryName . '_edit_form_fields', array( &$this, 'addCategoryForm' ), 11 );
		add_action( 'edited_' . BooksConfig::bookCategoryName, array( &$this, 'saveCategoryForm' ), 10, 2 );
		add_action( 'created_' . BooksConfig::bookCategoryName, array( &$this, 'saveCategoryForm' ), 10, 2 );

		/* ---- Author custom info ---- */
		add_action( BooksConfig::bookAuthorName . '_add_form_fields', array( &$this, 'addCategoryForm' ), 11 );
		add_action( BooksConfig::bookAuthorName . '_edit_form_fields', array( &$this, 'addCategoryForm' ), 11 );
		add_action( 'edited_' . BooksConfig::bookAuthorName, array( &$this, 'saveCategoryForm' ), 10, 2 );
		add_action( 'created_' . BooksConfig::bookAuthorName, array( &$this, 'saveCategoryForm' ), 10, 2 );

		add_action( 'init', array( &$this, '_init' ) );
	}

	function _init () {
		add_filter( 'manage_mvn_product_category_custom_column', array( &$this, 'addProductCategoryColumnContent' ), 10, 3 );
		add_filter( 'manage_edit-mvn_product_category_columns', array( &$this, 'add_product_category_columns' ) );
	}

	/**
	 * Enqueue admin styles for products
	 */
	function adminPrintStyles () {
		global $post_type;
		// Add admin scripts just if we are in a mvn_book post type page
		if ( $post_type === BooksConfig::bookTypeName ) {
//			$this->add_admin_style("mvn-shop-admin-style", "style.css");
		}
	}

	/**
	 * Enqueue admin scripts for taxonomies
	 */
	function adminPrintScripts () {
		global $post_type, $taxonomy;
		// Add admin scripts just if we are in a mvn_product post type page
		if ( $post_type === BooksConfig::bookTypeName && ($taxonomy == BooksConfig::bookAuthorName || $taxonomy == BooksConfig::bookCategoryName) ) {
			$registry = \MavenBooks\Settings\BooksRegistry::instance();
			wp_enqueue_script( 'angular', $registry->getBowerComponentUrl() . "angular/angular.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/js/bootstrap.js", 'jquery', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-resource', $registry->getBowerComponentUrl() . "angular-resource/angular-resource.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-cookies', $registry->getBowerComponentUrl() . "angular-cookies/angular-cookies.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-sanitize', $registry->getBowerComponentUrl() . "angular-sanitize/angular-sanitize.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-route', $registry->getBowerComponentUrl() . "angular-route/angular-route.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'angular-bootstrap', $registry->getBowerComponentUrl() . "angular-bootstrap/ui-bootstrap-tpls.js", 'angular', $registry->getPluginVersion() );

			wp_enqueue_script( 'mavenBooksApp', $registry->getScriptsUrl() . "admin/app.js", 'angular', $registry->getPluginVersion() );
			wp_enqueue_script( 'admin/controllers/taxonomy.js', $registry->getScriptsUrl() . "admin/controllers/taxonomy.js", 'mavenBooksApp', $registry->getPluginVersion() );


			wp_enqueue_style( 'bootstrap', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap.css", null, $registry->getPluginVersion() );
			wp_enqueue_style( 'bootstrap-theme', $registry->getBowerComponentUrl() . "bootstrap/dist/css/bootstrap-theme.css", null, $registry->getPluginVersion() );

			wp_enqueue_style( 'main', $registry->getStylesUrl() . "main.css", array( 'bootstrap', 'bootstrap-theme' ), $registry->getPluginVersion() );
//			$this->add_admin_script('mvn-shop-product-category', 'mvn-shop-product-category.js');
//			$values = array(
//				'ruleLabel' => 'Rule',
//				'operator' => Mvn_Shop_Product_Taxonomies::dropdown_operators(array('echo' => false, 'name' => 'mvn_shop_term[smart_rules][-rid-][operator]', 'id' => 'mvn-shop-category-smart-operator--rid-')),
//				'field' => Mvn_Shop_Product_Taxonomies::dropdown_fields(array('echo' => false, 'name' => 'mvn_shop_term[smart_rules][-rid-][field]', 'id' => 'mvn-shop-category-smart-field--rid-'))
//				);
//			$this->add_admin_localize_script('mvn-shop-product-category', 'productCategoryObj', $values);
		}
	}

	/**
	 * Add custmo info to the category form
	 * @param array $term
	 */
	function addCategoryForm ( $term ) {
		$is_smart_term = 0;
		$smart_term_rules[ 'smart_rules' ] = array();
		$smart_term_rules[ 'smart_operator' ] = '';
		$smart_term_operators = TaxonomiesManager::getSmartOperators();
		$smart_term_fields = TaxonomiesManager::getSmartFields();
		// If we are editing a category
		if ( $term && isset( $term->term_taxonomy_id ) ) {
			// Get fields associated to this term
			$is_smart_term = TaxonomiesManager::isSmartTerm( $term->term_taxonomy_id );
			$smart_term_rules[ 'smart_rules' ] = TaxonomiesManager::getSmartRules( $term->term_taxonomy_id );
			$smart_term_rules[ 'smart_operator' ] = TaxonomiesManager::getSmartOperator( $term->term_taxonomy_id );
		}
		$this->addJSONData( 'smartTermOperators', $smart_term_operators );
		$this->addJSONData( 'smartTermFields', $smart_term_fields );
		$this->addJSONData( 'smartTermRules', $smart_term_rules );
		$this->addJSONData( 'isSmartTerm', $is_smart_term );
		echo $this->getOutput()->getWpAdminView( "taxonomy-form" );
//		$this->output->add_data( "is_smart_term", $is_smart_term );
//		$this->output->add_data("smart_term_rules", $smart_term_rules['smart_rules']);
//		$this->output->add_data("smart_operator", $smart_term_rules['smart_operator']);
//		$this->output->add_data("mvn_nonce", $this->nonce_field(self::action_edit, 'mvn_category_nonce', true, false));
//		$this->output->load_admin_wp_view('product-category-form');
	}

	/**
	 * Save category custom info
	 * @param int $category_id
	 * @return null 
	 */
	public function saveCategoryForm ( $term_id, $term_taxonomy_id ) {
		global $taxonomy;
//		if ( !$this->validate_nonce( self::action_edit, 'mvn_category_nonce', '_POST', false ) )
//			return;
		// Hack for ajax call
		if ( empty( $taxonomy ) && isset( $_POST[ 'taxonomy' ] ) && !empty( $_POST[ 'taxonomy' ] ) ) {
			$taxonomy = stripslashes( $_POST[ 'taxonomy' ] );
		}
		if ( empty( $taxonomy ) ) {
			return;
		}
		$mvnSmartRules = $this->getRequest()->getProperty( 'mvn_smart_rules' );
		$mvn_shop_term = isset( $mvnSmartRules[ 'smart_rules' ] ) ? $mvnSmartRules : array();
		$is_smart_term = isset( $mvnSmartRules[ 'is_smart_term' ] ) ? 1 : 0;
		TaxonomiesManager::setSmartTerm( $term_taxonomy_id, $is_smart_term );

		$old_smart_rules = $smart_rules = TaxonomiesManager::getSmartRules( $term_taxonomy_id );

		// Update only if current smart rules are different than the new ones
		if ( isset( $mvn_shop_term[ 'smart_rules' ] ) && $mvn_shop_term[ 'smart_rules' ] !== $old_smart_rules ) {
//			die( print_r( $mvn_shop_term[ 'smart_rules' ], true ) );
			TaxonomiesManager::setSmartRules( $term_taxonomy_id, $mvn_shop_term[ 'smart_rules' ] );
			TaxonomiesManager::setSmartOperator( $term_taxonomy_id, $mvn_shop_term[ 'smart_operator' ] );
			TaxonomiesManager::relateProductsWithSmartCategories( $term_id, $term_taxonomy_id, $taxonomy, $mvn_shop_term );
		}

		return;
	}

	function add_product_category_columns ( $columns ) {
		$columns[ 'smart_term' ] = __( 'Smart Category', 'maven-books' );
		return $columns;
	}

	function addProductCategoryColumnContent ( $value, $column, $term_id ) {
		global $taxonomy;
		if ( $column == 'smart_term' ) {
			$is_smart_term = TaxonomiesManager::isSmartTerm( get_term_field( 'term_taxonomy_id', $term_id, $taxonomy ) );
			if ( $is_smart_term ) {
				$value .= __( 'Yes' );
			} else {
				$value .= __( 'No' );
			}
		}
		return $value;
	}

}

$mvnbAdminProductTaxonomies = TaxonomiesController::instance();
