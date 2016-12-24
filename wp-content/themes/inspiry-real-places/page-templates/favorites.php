<?php
/*
 * Template Name: Favorites
 */

get_header();

get_template_part( 'partials/header/banner' );
?>
    <div id="content-wrapper" class="site-content-wrapper site-pages">

        <div id="content" class="site-content layout-boxed">

            <div class="container">

                <div class="row">

                    <div class="col-xs-12 site-main-content">

                        <main id="main" class="site-main">

                            <?php
                            $favorite_properties = array();

                            if ( is_user_logged_in() ) {
                                $user_id = get_current_user_id();
                                $favorite_properties = get_user_meta( $user_id, 'favorite_properties' );
                            } else {
                                if ( isset( $_COOKIE['inspiry_favorites'] ) ) {
                                    $favorite_properties = unserialize( $_COOKIE['inspiry_favorites'] );
                                }
                            }

                            $number_of_properties = count( $favorite_properties );

							if ( $number_of_properties > 0 ) {

								global $paged;
								global $inspiry_options;

								$properties_per_page = intval( $inspiry_options[ 'inspiry_favorites_properties_number' ] );
								if ( ! $properties_per_page ) {
									$properties_per_page = 6;
								}

								$favorites_properties_args = array(
									'post_type' => 'property',
									'posts_per_page' => $properties_per_page,
									'post__in' => $favorite_properties,
									'orderby' => 'post__in',
									'paged' => $paged,
								);

								/* Apply sorting filter */
								$favorites_properties_args = apply_filters( 'inspiry_sort_properties', $favorites_properties_args );

								$favorites_query = new WP_Query( $favorites_properties_args );

								/*
								 * Found properties heading and sorting controls
								 */
								global $found_properties;
								$found_properties = $favorites_query->found_posts;
								get_template_part( 'partials/property/templates/listing-control' );

								/*
								 * Properties List
								 */
								if ( $favorites_query->have_posts() ) :

									global $property_grid_counter;
									$property_grid_counter = 1;

									echo '<div class="row">';

									while ( $favorites_query->have_posts() ) :

										$favorites_query->the_post();

										// display property in grid layout
										get_template_part( 'partials/property/templates/property-for-grid' );

										$property_grid_counter++;

									endwhile;

									echo '</div>';

									inspiry_pagination( $favorites_query );

									wp_reset_postdata();

								endif;

							} else {

								inspiry_message( __( 'Oops', 'inspiry' ), __( 'You have not added any property to favorites!', 'inspiry' ) );

							}
                            ?>

                        </main>
                        <!-- .site-main -->

                    </div>
                    <!-- .site-main-content -->

                </div>
                <!-- .row -->

            </div>
            <!-- .container -->

        </div>
        <!-- .site-content -->

    </div><!-- .site-content-wrapper -->

<?php
get_footer();
?>