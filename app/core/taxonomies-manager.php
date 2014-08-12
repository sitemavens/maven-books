<?php

namespace MavenBooks\Core;

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) )
	exit;

class TaxonomiesManager {

	const MetaType = 'term_taxonomy';
	const SmartMetaSlug = 'mvn_is_smart_term';

	/**
	 * Add meta data field to a term.
	 *
	 * @param int $term_taxonomy_id Post ID.
	 * @param string $meta_key Metadata name.
	 * @param mixed $meta_value Metadata value.
	 * @param bool $unique Optional, default is false. Whether the same key should not be added.
	 * @return bool False for failure. True for success.
	 */
	public static function addTermTaxonomyMeta ( $term_taxonomy_id, $meta_key, $meta_value, $unique = false ) {
		return add_metadata( self::MetaType, $term_taxonomy_id, $meta_key, $meta_value, $unique );
	}

	/**
	 * Remove metadata matching criteria from a term.
	 *
	 * You can match based on the key, or key and value. Removing based on key and
	 * value, will keep from removing duplicate metadata with the same key. It also
	 * allows removing all metadata matching key, if needed.
	 *
	 * @param int $term_taxonomy_id term taxonomy ID
	 * @param string $meta_key Metadata name.
	 * @param mixed $meta_value Optional. Metadata value.
	 * @return bool False for failure. True for success.
	 */
	public static function deleteTermTaxonomyMeta ( $term_taxonomy_id, $meta_key, $meta_value = '', $delete_all = false ) {
		return delete_metadata( self::MetaType, $term_taxonomy_id, $meta_key, $meta_value, $delete_all );
	}

	/**
	 * Remove all metadata matching criteria from a term.
	 *
	 */
	public static function deleteAllTermTaxonomyMeta ( $term, $term_taxonomy_id, $taxonomy ) {
		$metadata = self::getTermTaxonomyMeta( $term_taxonomy_id );
		if ( $metadata && is_array( $metadata ) ) {
			foreach ( $metadata as $meta_key => $meta_values ) {
				self::deleteTermTaxonomyMeta( $term_taxonomy_id, $meta_key );
			}
		}
	}

	/**
	 * Retrieve term meta field for a term.
	 *
	 * @param int $term_taxonomy_id Term Taxonomy ID.
	 * @param string $key The meta key to retrieve.
	 * @param bool $single Whether to return a single value.
	 * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
	 *  is true.
	 */
	public static function getTermTaxonomyMeta ( $term_taxonomy_id, $key = '', $single = false ) {
		return get_metadata( self::MetaType, $term_taxonomy_id, $key, $single );
	}

	/**
	 * Update term meta field based on term ID.
	 *
	 * Use the $prev_value parameter to differentiate between meta fields with the
	 * same key and term ID.
	 *
	 * If the meta field for the term does not exist, it will be added.
	 *
	 * @param int $term_taxonomy_id Term ID.
	 * @param string $meta_key Metadata key.
	 * @param mixed $meta_value Metadata value.
	 * @param mixed $prev_value Optional. Previous value to check before removing.
	 * @return bool False on failure, true if success.
	 */
	public static function updateTermTaxonomyMeta ( $term_taxonomy_id, $meta_key, $meta_value, $prev_value = '' ) {
		return update_metadata( self::MetaType, $term_taxonomy_id, $meta_key, $meta_value, $prev_value );
	}

	/**
	 * Check if term meta field exist based on term ID.
	 *
	 * @param int $term_taxonomy_id Term ID.
	 * @param string $meta_key Metadata key.
	 * @return bool False on failure, true if success.
	 */
	public static function termTaxonomyMetaExists ( $term_taxonomy_id, $meta_key ) {
		return metadata_exists( self::MetaType, $term_taxonomy_id, $meta_key );
	}

	/**
	 * Retrieve the operators for smart categories
	 * @return array Array of promotions types
	 */
	public static function getSmartOperators () {
		$operators = array(
			'none' => __( 'None', 'mvn-shop' ),
			'is_equal_to' => __( 'Is equal to', 'mvn-shop' ),
			'is_not_equal_to' => __( 'Is not equal to', 'mvn-shop' ),
			'is_greater_than' => __( 'Is greater than', 'mvn-shop' ),
			'is_greater_or_equal_than' => __( 'Is greater or equal than', 'mvn-shop' ),
			'is_less_than' => __( 'Is less than', 'mvn-shop' ),
			'is_less_or_equal_than' => __( 'Is less or equal than', 'mvn-shop' ),
			'contains' => __( 'Contains', 'mvn-shop' ),
			'not_contain' => __( 'Does not contain', 'mvn-shop' ),
			'begins_with' => __( 'Begins with', 'mvn-shop' ),
			'ends_with' => __( 'Ends with', 'mvn-shop' ),
			'in' => __( 'In', 'mvn-shop' ),
			'not_in' => __( 'Not in', 'mvn-shop' ),
		);
		return apply_filters( 'mvn_smart_term_operators', $operators );
	}

	/**
	 * Retrieve the promotion types
	 * @return array Array of promotions status
	 */
	public static function getSmartFields () {
		$fields = array(
			'post_date' => __( 'Date Added', 'mvn-shop' ),
			'post_title' => __( 'Product Name', 'mvn-shop' ),
			'meta:mvn_shop_regular_price' => __( 'Product Price', 'mvn-shop' ),
			'term:mvn_product_type' => __( 'Product Type', 'mvn-shop' ),
		);
		return apply_filters( 'mvn_smart_term_fields', $fields );
	}

	// TODO: move this to a helper or implement a better method
	public static function is_date ( $date ) {
		// Validate date with format as Y-mm-dd
		if ( preg_match( '/^\d{4}-\d{1,2}-\d{1,2}$/', $date ) ) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Retrieve terms ids by meta field.
	 *
	 * @param int $term_id Term ID.
	 * @param string $key The meta key to retrieve.
	 * @param bool $single Whether to return a single value.
	 * @return mixed Will be an array if $single is false. Will be value of meta data field if $single
	 *  is true.
	 */
	public static function getTermTaxonomiesByMeta ( $meta_key, $meta_value = '', $taxonomy = '', $meta_compare = '=', $cast = 'CHAR', $single = false ) {
		global $wpdb;
		if ( !$table = _get_meta_table( self::MetaType ) ) {
			return false;
		}
		$where = $n = $join = '';
		if ( !empty( $taxonomy ) ) {

			if ( !is_array( $taxonomy ) ) {
				$taxonomy = array( $taxonomy );
			}

			$where .= " AND {$wpdb->term_taxonomy}.taxonomy IN ('" . implode( "','", $taxonomy ) . "')";
		}

		$join = " INNER JOIN {$wpdb->term_taxonomy} ON {$table}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id";

		if ( $meta_compare == 'LIKE' ) {
			$n = '%';
		}
		if ( !empty( $meta_value ) ) {
			$where .= " AND CAST({$table}.meta_value AS {$cast}) {$meta_compare} '{$n}{$meta_value}{$n}'";
		}
		$ids = $wpdb->get_results( "SELECT {$wpdb->term_taxonomy}.* FROM {$table}{$join} WHERE {$table}.meta_key = '{$meta_key}'{$where}" );

		if ( $single && is_array( $ids ) && count( $ids ) > 0 ) {
			return $ids[ 0 ];
		}
		return $ids;
	}

	/**
	 * 
	 * @global type $wpdb
	 * @param type $term_id
	 * @param type $term_taxonomy_id
	 * @param type $taxonomy
	 * @param type $smart_rules
	 * @param type $objects
	 * @return boolean
	 */
	public static function relateProductsWithSmartCategories ( $term_id, $term_taxonomy_id, $taxonomy, $smart_rules = array(), $objects = array() ) {
		global $wpdb;

		if ( !term_exists( ( int ) $term_id, $taxonomy ) ) {
			return false;
		}

		$products_to_add = array();
		$products_to_delete = array();
		$all_products = self::getProductsBySmartTerm( $term_id, $term_taxonomy_id, $taxonomy, $smart_rules, $objects );
		$current_products = self::getProductsWithTerm( $term_taxonomy_id, $objects );

		if ( is_array( $all_products ) && is_array( $current_products ) ) {
			$products_to_add = array_diff( $all_products, $current_products );
			$products_to_delete = array_diff( $current_products, $all_products );
		}
		// DELETE all term relationship before updating it

		if ( !empty( $products_to_delete ) ) {
			self::deleteObjectsRelatedToTerm( $products_to_delete, $term_id, $term_taxonomy_id, $taxonomy );
		}
		if ( !empty( $products_to_add ) ) {
			self::relateObjectsToTerm( $products_to_add, $term_id, $taxonomy );
		}

		unset( $products_to_add, $products_to_delete, $current_products, $all_products );
		do_action( 'mvn_process_smart_categories_rules', $term_id, $term_taxonomy_id, $taxonomy, $smart_rules );
	}

	/**
	 * 
	 * @param array $objects
	 * @param int $term_id
	 * @param int $tt_id
	 * @param string $taxonomy
	 */
	public static function deleteObjectsRelatedToTerm ( $objects, $term_id, $tt_id, $taxonomy ) {
		// TODO: check if it doesn't need a flush cache or a delete relationship with objects
//		$wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id = %d", $tt_id) );
//		$objects = $wpdb->get_col( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $tt_id ) );
		if ( $objects ) {

			if ( !is_array( $objects ) ) {
				$objects = array( $objects );
			}

			foreach ( ( array ) $objects as $object ) {
				$terms = wp_get_object_terms( $object, $taxonomy, array( 'fields' => 'ids', 'orderby' => 'none' ) );
				$terms = array_diff( $terms, array( $term_id ) );
				$terms = array_map( 'intval', $terms );
				wp_set_object_terms( $object, $terms, $taxonomy );
			}
			wp_update_term_count( $tt_id, $taxonomy );
		}
	}

	/**
	 * 
	 * @param array $objects
	 * @param int $term_id
	 * @param string $taxonomy
	 */
	public static function relateObjectsToTerm ( $objects, $term_id, $taxonomy ) {

		if ( $objects ) {
			if ( !is_array( $objects ) ) {
				$objects = array( $objects );
			}

			foreach ( ( array ) $objects as $object ) {
				wp_set_object_terms( $object, ( int ) $term_id, $taxonomy, true );
			}
		}
	}

	/**
	 * 
	 * @global \MavenBooks\Core\type $wpdb
	 * @param int $term_taxonomy_id
	 * @param array $objects
	 * @return int
	 */
	public static function getProductsWithTerm ( $term_taxonomy_id, $objects = array() ) {
		global $wpdb;

		if ( !is_array( $objects ) ) {
			$objects = array( $objects );
		}

		if ( !empty( $objects ) ) {
			$objects_where = " AND object_id IN (" . implode( ',', $objects ) . ")";
		} else {
			$objects_where = '';
		}

		return $wpdb->get_col( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d {$objects_where}", $term_taxonomy_id ) );
	}

	/**
	 * 
	 * @global \MavenBooks\Core\type $wpdb
	 * @param int $term_id
	 * @param int $term_taxonomy_id
	 * @param string $taxonomy
	 * @param array $smart_rules
	 * @param array $objects
	 * @return type
	 */
	public static function getProductsBySmartTerm ( $term_id, $term_taxonomy_id, $taxonomy, $smart_rules = array(), $objects = array() ) {
		global $wpdb;

		if ( !is_array( $objects ) ) {
			$objects = array( $objects );
		}
		if ( !empty( $objects ) ) {
			$objects_where = " AND $wpdb->posts.ID IN (" . implode( ',', $objects ) . ")";
		} else {
			$objects_where = '';
		}
		if ( !isset( $smart_rules[ 'smart_operator' ] ) || ($smart_rules[ 'smart_operator' ] == '') || empty( $smart_rules[ 'smart_operator' ] ) ) {
			$smart_rules[ 'smart_operator' ] = " AND ";
		}

		$term_smart_rules = $smart_rules[ 'smart_rules' ];

		$term_id = ( int ) $term_id;
		$term_taxonomy_id = ( int ) $term_taxonomy_id;
		$join = $where = '';
		$op = " " . $smart_rules[ 'smart_operator' ] . " ";

		if ( empty( $term_smart_rules ) ) {
			$term_smart_rules = self::getSmartRules( $term_taxonomy_id );
		}

		// Group smart rules by field to proccess them in the same where rule
		if ( $term_smart_rules && is_array( $term_smart_rules ) ) {
			$rules = array();
			foreach ( $term_smart_rules as $key => $term_smart_rule ) {
				$meta_table_alias = $where_field = '';
				$operator = $a = $b = $encloser_value = '';
				$field_key = $term_smart_rule[ 'field' ];
				$value = esc_sql( stripslashes( $term_smart_rule[ 'rule' ] ) );
				// identify the operator and match with the correct mysql operator
				switch ( $term_smart_rule[ 'operator' ] ) {
					case 'none':
						$operator = '';
						break;

					case 'is_equal_to':
						$operator = '=';
						break;
					// String or Numeric operations
					case "is_not_equal_to":
						$operator = '!=';
						break;

					// String operations
					case "contains":
						$a = $b = '%';
						$operator = 'LIKE';
						break;
					case "not_contain":
						$a = $b = '%';
						$operator = 'NOT LIKE';
						break;
					case "begins_with":
						$a = '%';
						$b = '';
						$operator = 'LIKE';
						break;
					case "ends_with":
						$a = '';
						$b = '%';
						$operator = 'LIKE';
						break;

					// Numeric operations
					case "is_greater_than":
						$operator = '>';
						break;
					case "is_greater_or_equal_than":
						$operator = '>=';
						break;
					case "is_less_than":
						$operator = '<';
						break;
					case "is_less_or_equal_than":
						$operator = '<=';
						break;
					case "in":
						$encloser_value = '';
						$a = '(';
						$b = ')';
						$operator = 'IN';

						if ( FALSE !== strpos( $value, '|' ) ) {
							$value = implode( ',', array_map( 'trim', explode( '|', $value ) ) );
						}
						break;
					case "not_in":
						// DO not join with the table when it is not in, since it is a table with multiple relationships 
						$join = '';
						$encloser_value = '';
						$a = '(';
						$b = ')';
						$operator = 'NOT IN';
						if ( FALSE !== strpos( $value, '|' ) ) {
							$value = implode( ',', array_map( 'trim', explode( '|', $value ) ) );
						}
						break;
					default:
						break;
				}

				if ( strpos( $field_key, 'meta:' ) !== FALSE ) {
					// get of the prefix meta: to get the meta data key
					$field = str_replace( 'meta:', '', $field_key );
					// create a table alias for this field, it is in case there are another meta values to compare
					$meta_table_alias = "pm_{$field}_{$key}";
					// add the metadata table join
					$join .= " INNER JOIN {$wpdb->postmeta} AS {$meta_table_alias} ON {$wpdb->posts}.ID = {$meta_table_alias}.post_id ";

					$where_field = " {$meta_table_alias}.meta_key = '{$field}' ";
					// add the value rule
					if ( $field === 'mvn_regular_price' && !empty( $operator ) ) {
						$where_field .= " AND ( CAST( {$meta_table_alias}.meta_value AS DECIMAL ) {$operator} '{$a}{$value}{$b}' )";
					} elseif ( !empty( $operator ) ) {
						$where_field .= " AND ( {$meta_table_alias}.meta_value {$operator} '{$a}{$value}{$b}' )";
					}
					// if the field name contains term: it is a term taxonomy comparison so we will need to join the taxonomy table
				} elseif ( strpos( $field_key, 'term:' ) !== FALSE ) {
					// get of the prefix term: to get the taxonomy
					$field = str_replace( 'term:', '', $field_key );
					// create a table alias for this field, it is in case there are another meta values to compare
					$term_table_alias = "tr_{$field}_{$key}";
					// add the metadata table join
					$join .= " INNER JOIN {$wpdb->term_relationships} AS {$term_table_alias} ON ({$wpdb->posts}.ID = {$term_table_alias}.object_id) ";

					if ( !empty( $operator ) ) {
						// TODO: improve this to accept text to find the term, now it only permits IDs
						// TODO: improve this to accept more than one term, maybe sepate them with ,(comma)
						$value = $wpdb->get_col( "SELECT `term_taxonomy_id` FROM {$wpdb->term_taxonomy} WHERE `taxonomy` = '{$field}' AND `term_id` IN ({$value})" );
						if ( is_array( $value ) ) {
							$value = implode( ',', $value );
						}

						if ( $term_smart_rule[ 'operator' ] == 'not_in' ) {
							// add the value rule
							$where_field .= "{$wpdb->posts}.`ID` NOT IN ( SELECT `trb`.`object_id` FROM {$wpdb->term_relationships} AS trb WHERE `trb`.`term_taxonomy_id` IN ( {$value} ) )";
						} else {
							// add the value rule
							$where_field .= "{$term_table_alias}.`term_taxonomy_id` {$operator} {$encloser_value}{$a}{$value}{$b}{$encloser_value}";
						}
//						$value = $wpdb->get_var( "SELECT `term_taxonomy_id` FROM {$wpdb->term_taxonomy} WHERE `taxonomy` = '{$field}' AND `term_id` = " . (int)$value );
//						// add the value rule
//						$where_field = " {$term_table_alias}.`term_taxonomy_id` {$operator} '{$a}{$value}{$b}' ";
					}

					// if the field is the price we need to include a validation to regular and sale
				} elseif ( !empty( $operator ) ) {
					$field = $field_key;
					// if it is not a metadata comparison it is trait as a post field
					$field = esc_sql( stripslashes( $field ) );

					if ( $field === 'post_date' ) {
						// TODO: move this to a methor or in a helper
						if ( !self::is_date( $value ) && is_numeric( $value ) ) {
							$value = absint( $value );
							$value = date( 'Y-m-d', strtotime( "-{$value} day", current_time( 'timestamp' ) ) );
						} elseif ( !self::is_date( $value ) ) {
							// if ther is not date set and is not a range of days set it as today
							$value = date( 'Y-m-d', current_time( 'timestamp' ) );
						}
						$where_field = "DATE({$wpdb->posts}.`{$field}`) {$operator} '{$a}{$value}{$b}'";
					} else {
						$where_field = "{$wpdb->posts}.`{$field}` {$operator} '{$a}{$value}{$b}'";
					}
				}

				$join = apply_filters( 'mvn_smart_rules_field_join', $join, $term_smart_rule, $key );
				$where_field = apply_filters( 'mvn_smart_rules_field_where', $where_field, $term_smart_rule, $key );

				if ( !empty( $where_field ) )
					$rules[] = " ({$where_field}) ";
				//$smart_field_rules[$term_smart_rule['field']][] = $term_smart_rule;
			}

			$where = '';
			if ( !empty( $rules ) )
				$where .= "and ( " . implode( $op, $rules ) . " )";

			$extra_join = apply_filters( 'mvn_smart_rules_products_join', $join, $term_id, $term_taxonomy_id, $taxonomy, $smart_rules );

			$extra_where = apply_filters( 'mvn_smart_rules_products_where', $where, $term_id, $term_taxonomy_id, $taxonomy, $smart_rules );

			$order_by = apply_filters( 'mvn_smart_rules_products_orderby', "{$wpdb->posts}.post_date DESC", $term_id, $term_taxonomy_id, $taxonomy, $smart_rules );

			$limit = apply_filters( 'mvn_smart_rules_products_limit', '', $term_id, $term_taxonomy_id, $taxonomy, $smart_rules );

			$post_status_where = '';
			$posts_status = get_post_stati( array( 'internal' => false ), 'names' );
			if ( $posts_status && is_array( $posts_status ) ) {
				$post_status_where = " AND {$wpdb->posts}.post_status IN ('" . implode( "','", $posts_status ) . "') ";
			}

			$post_type = BooksConfig::bookTypeName;
			// Create the query, it only apply to published products
			$querystr = "
						SELECT DISTINCT {$wpdb->posts}.ID 
						FROM {$wpdb->posts}
						{$extra_join}
						WHERE
							({$wpdb->posts}.post_type = '{$post_type}')
							{$post_status_where}
							{$objects_where}
							{$extra_where}
						ORDER BY {$order_by}
						$limit
					";
			$all_products = $wpdb->get_col( $querystr );
			return $all_products;
		}
	}

	// TODO: should create a option to use smart categories or not.
	public static function isEnabledSmartCategories () {
		return true;
	}

	public static function getSmartOperator ( $termTaxonomyId ) {
		return self::getTermTaxonomyMeta( $termTaxonomyId, 'mvn_smart_operator', true );
	}

	public static function setSmartOperator ( $termTaxonomyId, $value ) {
		return self::updateTermTaxonomyMeta( $termTaxonomyId, 'mvn_smart_operator', $value );
	}

	public static function getSmartRules ( $termTaxonomyId ) {
		return self::getTermTaxonomyMeta( $termTaxonomyId, 'mvn_smart_term_rules', true );
	}

	public static function setSmartRules ( $termTaxonomyId, $value ) {
		return self::updateTermTaxonomyMeta( $termTaxonomyId, 'mvn_smart_term_rules', $value );
	}

	public static function isSmartTerm ( $termTaxonomyId ) {
		return self::getTermTaxonomyMeta( $termTaxonomyId, self::SmartMetaSlug, true );
	}

	public static function setSmartTerm ( $termTaxonomyId, $value ) {
		return self::updateTermTaxonomyMeta( $termTaxonomyId, self::SmartMetaSlug, $value );
	}

//	public static function getAllSmartCategories($taxonomies){
//		
//		$terms = get_terms($taxonomies, array('hide_empty' => FALSE));
//  
//		if( $terms && !is_wp_error($terms) ){
//
//		 foreach ($terms as $term) {
//			// get smart term value
//				$is_smart_term = self::isSmartTerm($term->term_taxonomy_id);
//				// if it is a smart term, relate the products that match with its smart rules
//				if( $is_smart_term ){
//				 self::relateProductsWithSmartCategories($term->term_id, $term->term_taxonomy_id, $term->taxonomy);
////				 $this->log("\tTerm ID: {$term->term_id} \tTerm Taxonomy ID: {$term->term_taxonomy_id} \tTaxonomy: {$term->taxonomy} ");
//				}
//		   }
//		}
//	}
}

//TaxonomiesManager::init();