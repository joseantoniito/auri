<?php
if( !function_exists( 'inspiry_get_breadcrumbs_items' ) ) :
	/**
	 * Returns a array of breadcrumbs items
	 *
	 * @param $post_id  int Post id
	 * @param $breadcrumbs_taxonomy string Taxonomy name
	 * @return mixed|void
	 */
	function inspiry_get_breadcrumbs_items( $post_id, $breadcrumbs_taxonomy ) {

		// Add home at the beginning of the breadcrumbs
		$inspiry_breadcrumbs_items = array(
			array(
				'name' => __( 'Home', 'inspiry' ),
				'url' => esc_url( home_url('/') ),
				'class' => '',
			)
		);

		// Get all assigned terms
		$the_terms = get_the_terms( $post_id, $breadcrumbs_taxonomy );

		if ( $the_terms && ! is_wp_error( $the_terms ) ) :

			$deepest_term = $the_terms[0];
			$deepest_depth = 0;

			// Find the deepest term
			foreach( $the_terms as $term ) {
				$current_term_depth = inspiry_get_term_depth( $term->term_id, $breadcrumbs_taxonomy );
				if ( $current_term_depth > $deepest_depth ) {
					$deepest_depth = $current_term_depth;
					$deepest_term = $term;
				}
			}

			// work on deepest term
			if ( $deepest_term ) {

				// Get ancestors if any and add them to breadcrumbs items
				$deepest_term_ancestors = get_ancestors( $deepest_term->term_id, $breadcrumbs_taxonomy );
				if ( $deepest_term_ancestors && ( 0 < count( $deepest_term_ancestors ) ) ) {
					$deepest_term_ancestors = array_reverse( $deepest_term_ancestors ); // reversing the array is important
					foreach ( $deepest_term_ancestors as $term_ancestor_id ) {
						$ancestor_term = get_term_by( 'id', $term_ancestor_id, $breadcrumbs_taxonomy );
						$inspiry_breadcrumbs_items[] = array(
							'name' => $ancestor_term->name,
							'url' => get_term_link( $ancestor_term, $breadcrumbs_taxonomy ),
							'class' => ''
						);
					}
				}

				// add deepest term
				$inspiry_breadcrumbs_items[] = array(
					'name' => $deepest_term->name,
					'url' => get_term_link( $deepest_term, $breadcrumbs_taxonomy ),
					'class' => ''
				);

			}

		endif;

		// Add the current page / property
		$inspiry_breadcrumbs_items[] = array(
			'name' => get_the_title( $post_id ),
			'url' => '',
			'class' => 'active',
		);

		return apply_filters( 'inspiry_breadcrumbs_items', $inspiry_breadcrumbs_items );

	}

endif;


if( !function_exists( 'inspiry_get_term_depth' ) ) :
	/**
	 * Returns an integer value that tells the term depth in it's hierarchy
	 *
	 * @param $term_id
	 * @param $term_taxonomy
	 * @return int
	 */
	function inspiry_get_term_depth( $term_id, $term_taxonomy ) {
		$term_ancestors = get_ancestors( $term_id, $term_taxonomy );
		if ( $term_ancestors ) {
			return count( $term_ancestors );
		}
		return 0;
	}
endif;